<?php
$conn = mysqli_connect("localhost", "root", "", "taiyodb");
// error_reporting(E_ERROR | E_WARNING | E_PARSE); // remove notice
session_start();
if (isset($_SESSION['userID'])) {
  $user_ID = $_SESSION['userID'];
  $sql = "SELECT profile_photo FROM enduser WHERE enduser_id = $user_ID";
  $userResult = mysqli_query($conn, $sql);
} else {
  $user_ID = null;
}
// $productID = $_SESSION['productID']; //get from home page (1)
$_SESSION['cartIDArray'] = null;
if(isset($_GET['product_id']))
{
	$productID = $_GET['product_id'];
}
else
{
	header("Location: Homepage.php");
	exit();
}
$_SESSION['productID'] = $productID;

$sql = "SELECT * FROM product WHERE product_id = $productID";
$productResult = mysqli_query($conn, $sql);
$product = mysqli_fetch_array($productResult);
$dotCount = 0; // slide dots count
?>

<html>

<head lang="en">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />
  <link rel="stylesheet" type="text/css" href="css/reset.css" />
  <link rel="stylesheet" type="text/css" href="css/product.css" />
  <link rel="stylesheet" type="text/css" href="css/header.css" />
  <link rel="stylesheet" type="text/css" href="css/util.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body onLoad="showSlides(1)">
  <?php
  include "header.php";
  ?>

  <section>
    <!-- Slideshow container -->
    <div class="slideshow-container">
      <!-- Full-width images with number and caption text -->

      <?php
      $getPicSQL = "SELECT product_image FROM productimage WHERE product_id = $productID";
      $getPicResult = mysqli_query($conn, $getPicSQL);
      while ($a = mysqli_fetch_assoc($getPicResult)) {
        echo "<div class='mySlides fade'>";
        echo "<img src='pictures/product/" . $a['product_image'] . "' class='w-full' />";
        echo "</div>";
        $dotCount++;
      }
	  
	  $getQuantity = "SELECT quantity FROM product WHERE product_id = $productID";
	  $getQuantityResult = mysqli_query($conn, $getQuantity);
      ?>

      <!-- Next and previous buttons -->
      <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
      <a class="next" onclick="plusSlides(1)">&#10095;</a>

      <?php
	  $getQuantity = "SELECT quantity FROM product WHERE product_id = $productID";
	  $getQuantityResult = mysqli_query($conn, $getQuantity);
	  $quantityRow = mysqli_fetch_array($getQuantityResult);
	  
	  
      if ($user_ID !== null) {
        $islogin = "true";
		$cartExists = false;
		$wishExists = false;

      } else {
        $islogin = "false";
		$cartExists = true;
		$wishExists = true;

      }
      

		if($islogin == "true")
		{
		  $checkProductIsInCartSQL = "SELECT count(*) FROM cart WHERE product_id = $productID AND enduser_id = $user_ID";
		  $checkProductIsInCartResult = mysqli_query($conn, $checkProductIsInCartSQL);
		  while ($cpCount = mysqli_fetch_assoc($checkProductIsInCartResult)) {
			if ($cpCount['count(*)'] == 0) {
			  $cartExists = true;
			}
		  }

		  $checkProductIsInWishlistSQL = "SELECT count(*) FROM wishlist WHERE product_id = $productID AND enduser_id = $user_ID";
		  $checkProductIsInWishlistResult = mysqli_query($conn, $checkProductIsInWishlistSQL);
		  while ($wpCount = mysqli_fetch_assoc($checkProductIsInWishlistResult)) {
			if ($wpCount['count(*)'] == 0) {
			  $wishExists = true;
			}
		  }
		  
		  if($quantityRow['quantity'] <= 0)
		  {
			  $cartExists = false;
			  $wishExists = false;
		  }
		  
		  if($cartExists == false || $wishExists == false)
		  {
			  $cartExists = false;
			  $wishExists = false;
		  }
		}
      if ($product['enduser_id'] != $user_ID) {
        if ($cartExists == 1) {
          echo "<a href='#' onclick='openQuantityCartModal($islogin)'><span class='cart'></span></a>";
        }
        if ($wishExists == 1) {
          echo "<a href='#' onclick='openWishlistModal($islogin)'><span class='wish'></span></a>";
        }
      }
	  else
	  {
		  echo "<a href='ProductEdit.php?product_id=".$productID."'><span class='edit'></span></a>";
	  }
      ?>
    </div>
    <br />

    <!-- The dots/circles -->
    <div style="text-align: center">
      <?php
      for ($i = 0; $i < $dotCount; $i++) {
        echo "<span class='dot' onclick='currentSlide($i+1)'></span>";
      }
      ?>
    </div>
  </section>

  <hr class="m-l-20 m-r-20" />
  <aside class="m-b-40">
    <div>
      <?php
      $getProfilePicSQL = "SELECT profile_photo, product.enduser_id, username FROM product LEFT JOIN enduser ON product.enduser_id = enduser.enduser_id WHERE product_id = $productID";
      $getProfilePicResult = mysqli_query($conn, $getProfilePicSQL);
      while ($b = mysqli_fetch_assoc($getProfilePicResult)) {
        echo "<a href='Listing.php?user_id=".$b['enduser_id']."'>";
        echo "<img class='profile' src='pictures/profile/" . $b['profile_photo'] . "' alt='" . $b['profile_photo'] . "' title='".$b['profile_photo']."' />";
        echo "</a>";
		echo "<a href='Listing.php?user_id=" . $b['enduser_id'] . "' class='userlink'>";
        echo "<p class='text-center'><b>{$b['username']}</b></p>";
		echo "</a>";
        echo "<br />";
      }

      echo "<div class='w-full'>";
      echo "<p class='m-l-100 m-r-100'>";
      $getAddressDataSQL = "SELECT street_1, street_2, city, c_state, postcode, phone_number, user_email FROM product LEFT JOIN enduser ON product.enduser_id = enduser.enduser_id WHERE product_id = $productID";
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
		if($c['quantity'] > 0)
		{
			echo "<p class='desc'>Quantity: {$c['quantity']}</p>";
		}
		else
		{
			echo "<p class='desc'>Out of Stock</p>";
		}
        echo "<hr /> <br />";
        echo "<p class='desc'>";
        echo $c['product_description'];
        echo "</p>";
      }

      ?>
      <?php
      if ($user_ID !== null) {
        $checkLogin = "true";
      } else {
        $checkLogin = "false";
      }
      if ($product['enduser_id'] != $user_ID && $quantityRow['quantity'] > 0) {
        echo "<button class='purchasebutton bgred' onclick='openModal($checkLogin)'>";
        echo "Purchase";
        echo "</button>";
      }
      ?>
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

      <form class="editShippingForm m-r-40" method="get" action="Checkout.php" id="purchaseModal">
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
          <button class="formButton m-l-auto m-r-30" type="button" onclick="closeModal()">
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
      <!-- <iframe name="votar" style="display:none;"></iframe> -->
      <form class="editShippingForm m-r-40" method="post">
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
          <button class="formButton m-r-40" type="submit" name="addToCart" onclick="closeQuantityCartModal()">
            Add to cart
          </button>
        </div>
        <?php
        if (isset($_POST['addToCart'])) {
          $quantity = $_POST['productQuantityCart'];
          $insertCartSQL = "INSERT INTO cart (quantity, product_id, enduser_id) VALUES ($quantity, $productID, $user_ID)";
          mysqli_query($conn, $insertCartSQL);
          // target="votar"
          echo "<meta http-equiv='refresh' content='0'>";

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
      <a class="m-l-20">Do you want to add this product into your wishlist?</a>
      <form class="buttonContainer" method="post">
        <button class="formButton m-l-auto m-r-30 m-b-20" onclick="closeWishlistModal()">
          No
        </button>
        <button class="formButton m-r-20 m-b-20" type="submit" name="addToWish" onclick="closeWishlistModal()">
          Yes
        </button>
        <?php
        if (isset($_POST['addToWish'])) {
          $insertWishlistSQL = "INSERT INTO wishlist (product_id, enduser_id) VALUES ($productID, $user_ID)";
          mysqli_query($conn, $insertWishlistSQL);
          echo "<meta http-equiv='refresh' content='0'>";
        }
        ?>
      </form>
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

    function openModal(x) {
      if (x) {
        quantityModal.style.display = "block";
        document.documentElement.style.overflow = "hidden";
        document.body.scroll = "no";
      } else {
        window.top.location = 'Login.php';
      }
    }

     function closeModal() {
      quantityModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
      // document.getElementById("purchaseModal").onsubmit = function() {
      //   return isValidForm();
      // };
    }

    function openWishlistModal(x) {
      if (x) {
        wishlistNoticeModal.style.display = "block";
        document.documentElement.style.overflow = "hidden";
        document.body.scroll = "no";
      } else {
        window.top.location = 'Login.php';
      }
    }

    function closeWishlistModal() {
      wishlistNoticeModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }

    function openQuantityCartModal(x) {
      if (x) {
        quantityCartModal.style.display = "block";
        document.documentElement.style.overflow = "hidden";
        document.body.scroll = "no";
      } else {
        window.top.location = 'Login.php';
      }
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
  <script src="js/profileModal.js"></script>
</body>

</html>