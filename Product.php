<?php
$conn = mysqli_connect("localhost", "root", "", "taiyodb");
// error_reporting(E_ERROR | E_WARNING | E_PARSE); // remove notice
session_start();
$userID = $_SESSION['userID']; // get user ID 
// $productID = $_SESSION['productID']; //get from home page (1)
$_SESSION['cartIDArray'] = null;
// $productID = $_GET['product_id']; //get from home page (2)
$dotCount = 0; // slide dots count
?>

<html>

<head lang="en">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />
  <link rel="stylesheet" type="text/css" href="css/reset.css" />
  <link rel="stylesheet" type="text/css" href="css/product.css" />
  <link rel="stylesheet" type="text/css" href="css/util.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body onLoad="showSlides(1)">
  <header class="w-full bggrey">
    <div class="bgblack" style="text-align: right">
      <ul>
        <li><a href="#">Register</a></li>
        <li><a href="#">Login</a></li>
      </ul>
    </div>
    <div class="flex-row aligni">
      <a href="#"><img class="taiyou" src="pictures/main/Taiyou.png" alt="Logo" title="Logo" /></a>
      <form>
        <input class="searchbar" type="search" name="searchbar" id="searchbar" placeholder=" Search..." />
        <button class="searchbutton" type="submit" name="search" id="search"></button>
      </form>
      <button class="sellbutton bgred" name="sell" id="sell">Sell</button>
    </div>
  </header>



  <section>
    <!-- Slideshow container -->
    <div class="slideshow-container">
      <!-- Full-width images with number and caption text -->

      <?php
      $getPicSQL = "SELECT product_image FROM productimage WHERE product_id = $productID";
      $getPicResult = mysqli_query($conn, $getPicSQL);
      while ($a = mysqli_fetch_assoc($getPicResult)) {
        echo "<div class='mySlides fade'>";
        echo "<img src='{$a['product_image']}' class='w-full' />";
        echo "</div>";
        $dotCount++;
      }
      ?>


      <!-- Next and previous buttons -->
      <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
      <a class="next" onclick="plusSlides(1)">&#10095;</a>

      <a href="#" onclick="openQuantityCartModal()"><span class="cart"></span></a>

      <a href="#" onclick="openWishlistModal()"><span class="wish"></span></a>
    </div>
    <br />

    <!-- The dots/circles -->
    <div style="text-align: center">
      <?php
      for ($i = 0; $i < $dotCount; $i++) {
        echo "<span class='dot' onclick='currentSlide($i)'></span>";
      }
      ?>
    </div>

  </section>


  <hr class="m-l-20 m-r-20" />
  <aside class="m-b-40">
    <div>
      <?php
      $getProfilePicSQL = "SELECT profile_photo, username FROM enduser WHERE enduser_id = $userID";
      $getProfilePicResult = mysqli_query($conn, $getProfilePicSQL);
      while ($b = mysqli_fetch_assoc($getProfilePicResult)) {
        echo "<a href='#'>";
        echo "<img class='profile' src='{$b['profile_photo']}' alt='Profile Pic' title='Profile Pic' />";
        echo "</a>";
        echo "<p class='text-center'><b>{$b['username']}</b></p>";
        echo "<br />";
      }

      echo "<div class='w-full'>";
      echo "<p class='m-l-100 m-r-100'>";
      $getAddressDataSQL = "SELECT street_1, street_2, city, c_state, postcode, phone_number, user_email FROM enduser WHERE enduser_id = $userID";
      $getAddressDataResult = mysqli_query($conn, $getAddressDataSQL);
      while ($c = mysqli_fetch_assoc($getAddressDataResult)) {
        echo "<i class='fa fa-map-marker'> &nbsp; </i> {$c['street_1']} {$c['street_2']}, {$c['postcode']} {$c['city']}, {$c['c_state']}";
        echo "</p>";
        echo "<p class='m-l-100'><i class='fa fa-phone'>&nbsp;</i>{$c['phone_number']}</p>";
        echo "<p class='m-l-100'>";
        echo " <i class='fa fa-envelope'>&nbsp;</i>{$c['user_email']}";
        echo "</p>";
        echo "</div>";
      }
      
      ?>
    </div>
  </aside>

  <section class="sectiondesc">
    <div>
      <?php
      $getProductDataSQL = "SELECT product_name, product_price, product_state, quantity, product_description FROM product WHERE product_id = $productID";
      $getProductDataResult = mysqli_query($conn, $getProductDataSQL);
      while ($c = mysqli_fetch_assoc($getProductDataResult)) {
        echo "<p class='desc titleDesc'>{$c['product_name']}</p>";
        echo "<p class='desc'>Price: RM {$c['product_price']}</p>";
        echo "<p class='desc'>State: {$c['product_state']}</p>";
        echo "<p class='desc'>Quantity: {$c['quantity']}</p>";

        echo "<hr /> <br />";
        echo "<p class='desc'>";
        echo $c['product_description'];
        echo "</p>";
      }

      ?>
      <button class="purchasebutton bgred" onclick="openModal()">
        Purchase
      </button>
    </div>
  </section>

  <div id="quantityModal" class="modalContainer">
    <div class="modalContentContainer">
      <h3 class="p-t-20 p-l-20 p-r-20">
        Select quantity
        <span class="close" onclick="closeModal()">&times;</span>
      </h3>
      <br />
      <hr class="m-l-20 m-r-20" />

      <form class="editShippingForm m-r-40" method="get" action="Checkout.php">
        <?php
        $getQuantitySQL = "SELECT quantity FROM product WHERE product_id = $productID";
        $getQuantityResult = mysqli_query($conn, $getQuantitySQL);
        while ($a = mysqli_fetch_assoc($getQuantityResult)) {
          echo "<a class='f-w-b p-l-18 fs-18'>Quantity (Total products available : {$a['quantity']})</a>";
          echo "<div class='quantityContainer p-l-18'>";
          echo "<input class='formInput m-r-60 p-l-18 m-t-20' name='productQuantity' type='number' min=1 max={$a['quantity']} placeholder=1 onKeyDown='return false' value='1'/>";
          echo "</div>";
        }
        ?>
        <div class="buttonContainer">
          <button class="formButton m-l-auto m-r-30" onclick="closeModal()">
            Cancel
          </button>
          <button class="formButton m-r-40" type="submit" name="checkOut">check out</button>
        </div>
      </form>
    </div>
  </div>

  <div id="quantityCartModal" class="modalContainer">
    <div class="modalContentContainer">
      <h3 class="p-t-20 p-l-20 p-r-20">
        Select quantity
        <span class="close" onclick="closeQuantityCartModal()">&times;</span>
      </h3>
      <br />
      <hr class="m-l-20 m-r-20" />
      <iframe name="votar" style="display:none;"></iframe>
      <form class="editShippingForm m-r-40" method="post" target="votar">
        <?php
        $getQuantitySQL = "SELECT quantity FROM product WHERE product_id = $productID";
        $getQuantityResult = mysqli_query($conn, $getQuantitySQL);
        while ($b = mysqli_fetch_assoc($getQuantityResult)) {
          echo "<a class='f-w-b p-l-18 fs-18'>Quantity (Total products available : {$b['quantity']})</a>";
          echo "<div class='quantityContainer p-l-18'>";
          echo "<input class='formInput m-r-60 p-l-18 m-t-20' name='productQuantityCart' type='number' min=1 max={$b['quantity']} placeholder=1 onKeyDown='return false' value='1'/>";
          echo "</div>";
        }
        ?>
        <div class="buttonContainer">
          <button class="formButton m-l-auto m-r-30" onclick="closeQuantityCartModal()">
            Cancel
          </button>
          <button class="formButton m-r-40" type="submit" name="addToCart" onclick="openCartModal(),closeQuantityCartModal()">
            add to cart
          </button>
        </div>
        <?php
        if (isset($_POST['addToCart'])) {
          $quantity = $_POST['productQuantityCart'];
          $insertCartSQL = "INSERT INTO cart (quantity, product_id, user_id) VALUES ($quantity, $productID, $userID)";
          mysqli_query($conn, $insertCartSQL);
        }

        ?>
      </form>
    </div>
  </div>

  <div id="wishlistNoticeModal" class="modalContainer">
    <div class="modalContentContainer">
      <h3 class="p-t-20 p-l-20 p-r-20">
        Notice
      </h3>
      <br />
      <hr class="m-l-20 m-r-20" />
      <a class="m-l-20">Added to wishlist</a>
      <form class="buttonContainer" method="post">
        <button class="formButton m-l-auto m-r-30 m-b-20" type="submit" name="wishlist">
          view wishlist
        </button>
        <button class="formButton m-r-20 m-b-20" onclick="closeWishlistModal()">
          continue shopping
        </button>
        <?php
        if (isset($_POST['wishlist'])) {
          $insertWishlistSQL = "INSERT INTO wishlist (product_id, user_id) VALUES ($productID, $userID)";
          mysqli_query($conn, $insertWishlistSQL);
          echo "<script type='text/javascript'>window.top.location='Wishlist.php';</script>";
          exit;
        }
        ?>
      </form>
    </div>
  </div>

  <div id="cartNoticeModal" class="modalContainer">
    <div class="modalContentContainer">
      <h3 class="p-t-20 p-l-20 p-r-20">
        Notice
      </h3>
      <br />
      <hr class="m-l-20 m-r-20" />
      <a class="m-l-20">Added to cart</a>
      <div class="buttonContainer">
        <button class="formButton m-l-auto m-r-30 m-b-20" name="openCart" onclick="openCart()">
          view cart
        </button>
        <button class="formButton m-r-20 m-b-20" onclick="closeCartModal()">
          continue shopping
        </button>
      </div>
    </div>
  </div>

  <script>
    var slideIndex = 1;
    showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
      showSlides((slideIndex += n));
    }

    // Thumbnail image controls
    function currentSlide(n) {
      showSlides((slideIndex = n));
    }

    function showSlides(n) {
      var i;
      var slides = document.getElementsByClassName("mySlides");
      var dots = document.getElementsByClassName("dot");
      if (n > slides.length) {
        slideIndex = 1;
      }
      if (n < 1) {
        slideIndex = slides.length;
      }
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex - 1].style.display = "block";
      dots[slideIndex - 1].className += " active";
    }

    function openModal() {
      quantityModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }

    function closeModal() {
      quantityModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }

    function openWishlistModal() {
      wishlistNoticeModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }

    function closeWishlistModal() {
      wishlistNoticeModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }

    function openCartModal() {
      cartNoticeModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }

    function closeCartModal() {
      cartNoticeModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }

    function openQuantityCartModal() {
      quantityCartModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }

    function closeQuantityCartModal() {
      quantityCartModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }

    function openCart() {
      window.top.location = 'Cart.php';
    }
  </script>
</body>

</html>