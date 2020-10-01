<?php
$conn = mysqli_connect("localhost", "root", "", "taiyodb");
error_reporting(E_ERROR | E_WARNING | E_PARSE); // remove notice
session_start();
$userID = $_SESSION["userID"]; // get user ID 
$cartIDArray = [];
$productID = $_SESSION["productID"]; //get from product page
$quantity = $_SESSION["quantity"]; // get from product page
// $productID = 1;
// $quantity = 2;
// echo $userID;
// echo "product $productID";
// echo "quantity $quantity";
// print_r($_SESSION['cartIDArray']);

if ($_SESSION['cartIDArray'] != null) {
  foreach ($_SESSION['cartIDArray'] as $a) {
    array_push($cartIDArray, $a);
  }
}
// print_r($cartIDArray);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>checkout</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="css/util.css" />
  <link rel="stylesheet" type="text/css" href="css/checkout.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body>
  <div class="limiter">
    <div class="checkoutContainer">
      <div class="selectedProductsContainer">
        <?php
        if ($productID == null) {
          foreach ($cartIDArray as $cartID) {
            $cartProductUserIDSQL = "SELECT product_id, user_id, quantity FROM cart WHERE cart_item_id = $cartID";
            $cartProductUseResult = mysqli_query($conn, $cartProductUserIDSQL);
            echo "<div class='productRowBackgroundContainer'>";
            echo "<div class='productRow'>";
            echo "<div class='discount'>";
            echo "<i class='fa fa-check-circle p-l-15'><a class='p-l-13'>RM 4.00</a></i>";
            echo "<br />";
            echo "<a class='p-l-41 fs-12'>Standard Delivery</a>";
            echo "</div>";
            while ($a = mysqli_fetch_assoc($cartProductUseResult)) {
              // print_r($a);
              $imageSQL = "SELECT product_image FROM productimage WHERE product_id = $a[product_id]";
              $imageResult = mysqli_query($conn, $imageSQL);
              while ($b = mysqli_fetch_assoc($imageResult)) {
                // print_r($b);
                echo "<div class='productInfoContainer'>";
                echo "<div class='float-l p-t-5'>";
                echo "<img class='pictureSize' src='{$b['product_image']}' alt='product' />";
                echo "</div>";
              }
              $productNameSQL = "SELECT product_name, product_price FROM product WHERE product_id = $a[product_id]";
              $productNameResult = mysqli_query($conn, $productNameSQL);
              while ($c = mysqli_fetch_assoc($productNameResult)) {
                // print_r($c);
                echo "<div class='productName p-t-10'>";
                echo "<a class='fs-15 f-w-b'>$c[product_name]</a>";
                echo "</div>";

                echo "<div class='productPrice p-t-10'>";
                echo "<a class='fs-15'>RM $c[product_price]</a>";
                echo "</div>";
              }
              echo "<div class='productQty p-t-10'>";
              echo "<a class='fs-15'>Qty: $a[quantity]</a>";
              echo "</div>";
              echo "</div>";
              echo "</div>";
              echo "</div>";
            }
          }
        } else {
          echo "<div class='productRowBackgroundContainer'>";
          echo "<div class='productRow'>";
          echo "<div class='discount'>";
          echo "<i class='fa fa-check-circle p-l-15'><a class='p-l-13'>RM 4.00</a></i>";
          echo "<br />";
          echo "<a class='p-l-41 fs-12'>Standard Delivery</a>";
          echo "</div>";

          $imageSQL = "SELECT product_image FROM productimage WHERE product_id = '$productID'";
          $imageResult = mysqli_query($conn, $imageSQL);
          while ($i = mysqli_fetch_assoc($imageResult)) {
            echo "<div class='productInfoContainer'>";
            echo "<div class='float-l p-t-5'>";
            echo "<img class='pictureSize' src='{$i['product_image']}' alt='product' />";
            echo "</div>";
          }
          $productNameSQL = "SELECT product_name, product_price FROM product WHERE product_id = '$productID'";
          $productNameResult = mysqli_query($conn, $productNameSQL);
          while ($j = mysqli_fetch_assoc($productNameResult)) {
            echo "<div class='productName p-t-10'>";
            echo "<a class='fs-15 f-w-b'>$j[product_name]</a>";
            echo "</div>";

            echo "<div class='productPrice p-t-10'>";
            echo "<a class='fs-15'>RM $j[product_price]</a>";
            echo "</div>";
          }
          echo "<div class='productQty p-t-10'>";
          echo "<a class='fs-15'>Qty: $quantity</a>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
        }
        ?>
      </div>

      <div class="placeOrderContainer">
        <div class="shippingInfoContainer" style="width: 100%;">
          <h3>
            Shipping & Billing
            <span class="float-r"><a class="edit fs-15" href="#" onclick="openshippingInfoModal()">Edit</a></span>
          </h3>



          <?php
          $userAddressSQL = "SELECT user_address, username FROM user WHERE user_id = $userID";
          $userIDResult = mysqli_query($conn, $userAddressSQL);
          while ($a = mysqli_fetch_assoc($userIDResult)) {
            echo "<i class='fa fa-map-marker p-b-10'><a class='f-w-b p-l-18'>{$a['username']}</a></i>";
            echo "<br />";
            if ($a['user_address'] == null) {
              echo "<p class='p-l-27 fs-14'>";
              echo "Please add your own address";
              echo "</p>";
            } else {
              echo "<p class='p-l-27 fs-14'>";
              echo "{$a['user_address']}";
              echo "</p>";
            }
          }
          ?>
          <br />

          <?php
          $phoneNumberSQL = "SELECT phone_number FROM user WHERE user_id = $userID";
          $phoneNumberResult = mysqli_query($conn, $phoneNumberSQL);
          while ($a = mysqli_fetch_assoc($phoneNumberResult)) {
            // print_r($a);
            if ($a['phone_number'] == null) {
              echo "<i class='fa fa-phone p-b-20'>";
              echo "<a class='p-l-13'>";
              echo "Please enter your phone number";
              echo "</a>";
              echo "</i>";
            } else {
              echo "<i class='fa fa-phone p-b-20'>";
              echo "<a class='p-l-13'>";
              echo "{$a['phone_number']}";
              echo "</a>";
              echo "</i>";
            }
          }
          ?>
          <br />

          <?php
          $emailSQL = "SELECT user_email FROM user WHERE user_id = $userID";
          $emailResult = mysqli_query($conn, $emailSQL);
          while ($a = mysqli_fetch_assoc($emailResult)) {
            // print_r($a);
            echo "<i class='fa fa-envelope p-b-20'>";
            echo "<a class='p-l-13'>";
            echo "{$a['user_email']}";
            echo "</a>";
            echo "</i>";
          }
          ?>

          <br />
          <h3>Payment Method</h3>
          <div id="showMethod">
            <img id="visa" class="pointer" src="pictures/visa.png" alt="visa" onclick="openpaymentMethodModal()" />
          </div>

          <br />
          <h3>Order Summary</h3>

          <?php
          $totalPrice = $totalDeliveryFee = 0.00;
          if ($productID == null) {
            foreach ($cartIDArray as $cartID) {
              $priceSQL = "SELECT product_price FROM product,cart WHERE cart.product_id = product.product_id AND cart_item_id = $cartID";
              $priceResult = mysqli_query($conn, $priceSQL);
              $quantitySQL = "SELECT quantity FROM cart WHERE cart_item_id = $cartID";
              $quantityResult = mysqli_query($conn, $quantitySQL);
              while ($e = mysqli_fetch_assoc($quantityResult)) {
                while ($d = mysqli_fetch_assoc($priceResult)) {
                  $totalPrice = $totalPrice + ($d['product_price'] * $e['quantity']);
                  $totalDeliveryFee = $totalDeliveryFee + 4.00;
                }
              }
            }
          } else {
            $priceSQL = "SELECT product_price FROM product WHERE product_id = '$productID'";
            $priceResult = mysqli_query($conn, $priceSQL);
            while ($d = mysqli_fetch_assoc($priceResult)) {
              $totalPrice = $totalPrice + ($d['product_price'] * $quantity);
              $totalDeliveryFee = $totalDeliveryFee + 4.00;
            }
          }
          $total = $totalPrice + $totalDeliveryFee;
          echo "<div class='p-t-10'>";
          echo "<a>Subtotal</a>";
          echo "<a class='float-r'>RM ";
          echo number_format((float)$totalPrice, 2, '.', '');
          echo "</a>";
          echo "</div>";

          echo "<div class='p-t-10 p-b-10'>";
          echo "<a>Shipping Fee</a>";
          echo "<a class='float-r'>RM ";
          echo number_format((float)$totalDeliveryFee, 2, '.', '');;
          echo "</a>";
          echo "</div>";

          echo "<div class='p-b-20'>";
          echo "<a>Total:</a>";
          echo "<a class='float-r'>RM ";
          echo number_format((float)$total, 2, '.', '');;
          echo "</a>";
          echo "</div>";
          ?>

          <div>
            <button class="placeOrderButton" onclick="openConfirmationModal()">
              Place order
            </button>
          </div>
        </div>
      </div>
    </div>

    <div id="shippingInfoModal" class="modalContainer">
      <div class="modalContentContainer">
        <h3 class="p-t-20 p-l-20 p-r-20">
          Shipping Info
          <span class="close" onclick="closeshippingInfoModal()">&times;</span>
        </h3>
        <br />
        <hr class="m-l-20 m-r-20" />

        <form class="editShippingForm" method="post">
          <i class="fa fa-map-marker p-b-10"><a class="f-w-b p-l-18 fs-18">Address</a></i>
          <?php
          $addressSQL = "SELECT user_address, phone_number FROM user WHERE user_id = $userID";
          $addressResult = mysqli_query($conn, $addressSQL);
          while ($e = mysqli_fetch_assoc($addressResult)) {
            echo "<div class='m-b-16 m-l-20 m-r-20'>";
            echo "<input class='formInput' type='text' name='address' placeholder='Insert your address here' value='{$e['user_address']}' required/>";
            echo "</div>";

            echo "<i class='fa fa-phone p-b-10'><a class='p-l-13 fs-18 f-w-b'>Phone Number</a></i>";
            echo "<div class='m-b-50 m-l-20 m-r-20'>";
            echo "<input class='formInput' type='tel' name='phoneNumber' placeholder='Insert your phone number here' value='{$e['phone_number']}' pattern = '(\+?6?01)[0-46-9]-*[0-9]{7,8}' required/>";
            echo "</div>";
          }
          ?>

          <div class="buttonContainer">
            <button class="formButton m-l-auto m-r-15" onclick="closeshippingInfoModal()">
              Cancel
            </button>
            <button class="formButton m-r-20" type="submit" name="save">Save</button>
          </div>
        </form>

        <?php
        $address = $phone = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $address = test_input($_POST["address"]);
          $phone = test_input(strval($_POST["phoneNumber"]));
        }
        if ($address !== "" and $phone !== "") {
          $updateSQL = "UPDATE user SET user_address = '$address', phone_number = '$phone' WHERE user_id = $userID";
          $updateResult = mysqli_query($conn, $updateSQL);
          header("Refresh:0");
        }

        function test_input($data)
        {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        }
        ?>
      </div>
    </div>

    <div id="paymentMethodModal" class="modalContainer">
      <div class="modalContentContainer">
        <h3 class="p-t-20 p-l-20 p-r-20">
          Payment Method
          <span class="close" onclick="closepaymentMethodModal()">&times;</span>
        </h3>
        <br />
        <hr class="m-l-20 m-r-20" />
        <br />
        <a class="m-l-20">Select a payment method</a>
        <div class="paymentMethodContainer">
          <img src="pictures/mastercardbig.png" alt="mastercard" class="paymentMethodImg m-r-40 pointer" onclick="selectMastercard()" />
          <img src="pictures/paypalbig.png" alt="paypal" class="paymentMethodImg m-r-40 pointer" onclick="selectPaypal()" />
          <img src="pictures/visabig.png" alt="visa" class="paymentMethodImg pointer" onclick="selectVisa()" />
        </div>
      </div>
    </div>

    <div id="checkOutConfirmationModal" class="modalContainer">
      <div class="modalContentContainer">
        <h3 class="p-t-20 p-l-20 p-r-20">
          Confirmation
          <span class="close" onclick="closeConfirmationModal()">&times;</span>
        </h3>
        <br />
        <hr class="m-l-20 m-r-20" />
        <br />
        <h3 class="p-t-20 p-l-20 p-r-20">
          Confirm to purchase this products?
        </h3>
        <br /><br /><br /><br /><br /><br />
        <div class="buttonContainer">
          <button class="formButton m-l-auto m-r-15 m-b-20" onclick="closeConfirmationModal()">
            No
          </button>
          <button class="formButton m-r-20 m-b-20" onclick="openNoticeModal(),closeConfirmationModal()">
            Yes
          </button>
        </div>
      </div>
    </div>

    <div id="checkOutNoticeModal" class="modalContainer">
      <div class="modalContentContainer">
        <h3 class="p-t-20 p-l-20 p-r-20">
          Notice
        </h3>
        <br />
        <hr class="m-l-20 m-r-20" />
        <br />
        <h3 class="p-t-20 p-l-20 p-r-20">
          Your order is confirmed ! It is on its way.
        </h3>
        <br /><br />
        <img src="https://media.giphy.com/media/8mpR0LykCqObF8t7Y5/giphy.gif" alt="delivery incoming !!!" width=250 class="m-l-290" />
        <br /><br />
        <form class="buttonContainer" method="post">
          <button type="button" class="formButton m-l-auto m-r-15 m-b-20" onclick="closeNoticeModal(),openReviewModal()">
            Review Seller
          </button>
          <button class="formButton m-r-20 m-b-20" type="submit" name="shop">Continue Shopping</button>
        </form>
        <?php
        $done = false;
        if (isset($_POST['shop'])) {
          if ($productID == null) {
            foreach ($cartIDArray as $x) {
              $dataSQL = "SELECT quantity, product_id, user_id FROM cart WHERE cart_item_id = $x";
              $dataResult = mysqli_query($conn, $dataSQL);
              while ($y = mysqli_fetch_assoc($dataResult)) {
                $storeTransSQL = "INSERT INTO TransactionHistory (transaction_type, quantity, product_id, user_id) VALUES ('Buy', '{$y['quantity']}', '{$y['product_id']}', '{$y['user_id']}' )";
                mysqli_query($conn, $storeTransSQL);
              }
              $deleteSQL = "DELETE FROM cart WHERE cart_item_id = $x";
              mysqli_query($conn, $deleteSQL);
            }
            unset($_SESSION['cartIDArray']);
            $done = true;
          } else {
            $storeTransSQL = "INSERT INTO TransactionHistory (transaction_type, quantity, product_id, user_id) VALUES ('Buy', $quantity, $productID, $userID)";
            mysqli_query($conn, $storeTransSQL);
            $done = true;
          }
        }
        if ($done) {
          // header("Location: Wishlist.php",  true,  301 );
          echo "<script type='text/javascript'>window.top.location='Wishlist.php';</script>";
          // echo "<script type='text/javascript'>window.top.location='Homepage.php';</script>";
          exit;
        }
        ?>
      </div>
    </div>

    <div id="reviewSellerModal" class="modalContainer">
      <div class="modalContentContainerTheSec">
        <h3 class="p-t-20 p-l-20 p-r-20">
          Review Seller
        </h3>
        <br />
        <hr class="m-l-20 m-r-20" />

        <?php
        $sellerCount = 1;
        if ($productID != null) {
          $sellerNameSQL = "SELECT username FROM user, product WHERE product.user_id = user.user_id AND product_id = $productID";
          $sellerNameResult = mysqli_query($conn, $sellerNameSQL);
          while ($o = mysqli_fetch_assoc($sellerNameResult)) {
            echo "<form class='editShippingForm' method='post'>";
            echo "<i class='fa fa-users p-b-10'>";
            echo "<a class='f-w-b p-l-18 fs-18'>";
            echo "Seller Info (Seller ";
            echo $sellerCount;
            echo ")";
            echo "</a></i>";
            echo "<div class='m-b-16 m-l-20 m-r-20'>";
            echo "<h4>Seller name: {$o['username']}</h4>";
          }
          $sellerDataSQL = "SELECT product_name, product_price FROM product WHERE product_id = $productID";
          $sellerDataResult = mysqli_query($conn, $sellerDataSQL);
          while ($u = mysqli_fetch_assoc($sellerDataResult)) {
            echo "<h4>Sold: {$u['product_name']}</h4>";
            echo "<h4>Price: RM {$u['product_price']}</h4></div>";
          }

          echo "<i class='fa fa-certificate p-b-10'><a class='p-l-13 fs-18 f-w-b'>Review</a></i>";
          echo "<div class='m-b-50 m-l-20 m-r-20'>";
          echo "<input class='formInput' type='text' name='review[]' placeholder='Enter your review here' />";
          echo "</div>";

          echo "<div class='buttonContainer'>";
          echo "<button type='submit' class='formButton m-l-auto m-r-15' name='cancelReview'>";
          echo "Cancel";
          echo "</button>";
          echo "<button class='formButton m-r-20' name='saveReview' type='submit'>Save</button>";
          echo "</div>";
          echo "</form>";
        } else {
          foreach ($cartIDArray as $h) {
            $getUsernameSQL = "SELECT username FROM user, cart WHERE user.user_id = cart.user_id AND cart_item_id = $h";
            $getUsernameResult = mysqli_query($conn, $getUsernameSQL);
            while ($k = mysqli_fetch_assoc($getUsernameResult)) {
              echo "<form class='editShippingForm' method='post'>";
              echo "<i class='fa fa-users p-b-10'>";
              echo "<a class='f-w-b p-l-18 fs-18'>";
              echo "Seller Info (Seller ";
              echo $sellerCount;
              echo ")";
              echo "</a></i>";
              echo "<div class='m-b-16 m-l-20 m-r-20'>";
              echo "<h4>Seller name: {$k['username']}</h4>";
            }
            $getProductIDSQL = "SELECT product_id FROM cart where cart_item_id = $h";
            $getProductIDResult = mysqli_query($conn, $getProductIDSQL);
            while ($u = mysqli_fetch_assoc($getProductIDResult)) {
              $sellerDataSQL = "SELECT product_name, product_price FROM product WHERE product_id = {$u['product_id']}";
              $sellerDataResult = mysqli_query($conn, $sellerDataSQL);
              while ($k = mysqli_fetch_assoc($sellerDataResult)) {
                echo "<h4>Sold: {$k['product_name']}</h4>";
                echo "<h4>Price: RM {$k['product_price']}</h4></div>";
              }
            }
            echo "<i class='fa fa-certificate p-b-10'><a class='p-l-13 fs-18 f-w-b'>Review</a></i>";
            echo "<div class='m-b-50 m-l-20 m-r-20'>";
            echo "<input class='formInput' type='text' name='review[]' placeholder='Enter your review here' />";
            echo "</div>";
            $sellerCount++;
          }
          echo "<div class='buttonContainer'>";
          echo "<button type='submit' class='formButton m-l-auto m-r-15' name='cancelReview'>";
          echo "Cancel";
          echo "</button>";
          echo "<button class='formButton m-r-20' name='saveReview' type='submit'>Save</button>";
          echo "</div>";
          echo "</form>";
        }
        ?>
      </div>
    </div>
  </div>
  <?php
  $conn = mysqli_connect("localhost", "root", "", "taiyodb");
  if (isset($_POST['cancelReview'])) {
    echo "<script type='text/javascript'>window.top.location='Homepage.php';</script>";
    exit;
  }
  if (isset($_POST['saveReview'])) {
    if (!empty($_POST['review'])) {
      $reviewSQL = "";
      $goToCart = false;
      $reviewArray = [];
      foreach ($_POST['review'] as $review) {
        // echo $review;
        if ($productID != null) {
          $getSellerIDSQL = "SELECT user.user_id FROM user, product WHERE product.product_id = $productID AND product.user_id = user.user_id";
          $getSellerIDResult = mysqli_query($conn, $getSellerIDSQL);
          // print_r($getSellerIDResult);
          while ($m = mysqli_fetch_assoc($getSellerIDResult)) {
            $reviewSQL = "INSERT INTO review (review_message, reviewer_id, reviewee_id) VALUES ('$review', $userID, {$m['user_id']})";
            mysqli_query($conn, $reviewSQL);
          }

          $storeTransSQL = "INSERT INTO TransactionHistory (transaction_type, quantity, product_id, user_id) VALUES ('Buy', $quantity, $productID, $userID)";
          mysqli_query($conn, $storeTransSQL);
          echo "<script type='text/javascript'>window.top.location='Homepage.php';</script>";
          exit;
        } else {
          array_push($reviewArray, $review);
          $goToCart = true;
        }
      }
      if ($goToCart) {
        $countReview = 0;
        foreach ($cartIDArray as $n) {
          $getProductIDSQL = "SELECT product_id FROM cart WHERE cart_item_id = $n";
          $getProductIDResult = mysqli_query($conn, $getProductIDSQL);
          while ($p = mysqli_fetch_assoc($getProductIDResult)) {
            print_r($p);
            $getSellerIDSQL = "SELECT user.user_id FROM user, product WHERE user.user_id = product.user_id AND product.product_id = {$p['product_id']}";
            $getSellerIDResult = mysqli_query($conn, $getSellerIDSQL);
            // print_r($getSellerIDResult);
            // echo $reviewArray[$countReview];
            // print_r($reviewArray);
            while ($m = mysqli_fetch_assoc($getSellerIDResult)) {
              // print_r($m);
              $reviewSQL = "INSERT INTO review (review_message, reviewer_id, reviewee_id) VALUES ('$reviewArray[$countReview]', $userID, {$m['user_id']})";
              mysqli_query($conn, $reviewSQL);
            }
          }
          $countReview++;
        }
        foreach ($cartIDArray as $x) {
          $dataSQL = "SELECT quantity, product_id, user_id FROM cart WHERE cart_item_id = $x";
          $dataResult = mysqli_query($conn, $dataSQL);
          while ($y = mysqli_fetch_assoc($dataResult)) {
            $storeTransSQL = "INSERT INTO TransactionHistory (transaction_type, quantity, product_id, user_id) VALUES ('Buy', '{$y['quantity']}', '{$y['product_id']}', '{$y['user_id']}' )";
            mysqli_query($conn, $storeTransSQL);
          }
          $deleteSQL = "DELETE FROM cart WHERE cart_item_id = $x";
          mysqli_query($conn, $deleteSQL);
        }
        unset($_SESSION['cartIDArray']);
        echo "<script type='text/javascript'>window.top.location='Homepage.php';</script>";
        exit;
      }
    }
  }
  ?>
  <script>
    // window.onbeforeunload = function () {return false;} 

    var shippingInfoModal = document.getElementById("shippingInfoModal");
    var paymentMethodModal = document.getElementById("paymentMethodModal");
    var checkOutConfirmationModal = document.getElementById(
      "checkOutConfirmationModal"
    );

    function openshippingInfoModal() {
      shippingInfoModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }

    function closeshippingInfoModal() {
      shippingInfoModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }

    function openpaymentMethodModal() {
      paymentMethodModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }

    function closepaymentMethodModal() {
      paymentMethodModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }

    function openConfirmationModal() {
      checkOutConfirmationModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }

    function closeConfirmationModal() {
      checkOutConfirmationModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }

    function openNoticeModal() {
      checkOutNoticeModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }

    function closeNoticeModal() {
      checkOutNoticeModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }

    function openReviewModal() {
      reviewSellerModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }
    // window.onclick = function(event) {
    //   if (event.target == shippingInfoModal) {
    //     shippingInfoModal.style.display = "none";
    //   }
    //   if (event.target == paymentMethodModal) {
    //     paymentMethodModal.style.display = "none";
    //   }
    //   if (event.target == checkOutConfirmationModal) {
    //     checkOutConfirmationModal.style.display = "none";
    //   }
    // };

    function selectMastercard() {
      paymentMethodModal.style.display = "none";
      loadMethod(1);
    }

    function selectPaypal() {
      paymentMethodModal.style.display = "none";
      loadMethod(2);
    }

    function selectVisa() {
      paymentMethodModal.style.display = "none";
      loadMethod(3);
    }

    function loadMethod(method) {
      var showMethod = document.getElementById("showMethod");

      if (method === 1) {
        showMethod.innerHTML =
          '<img src="pictures/mastercard.png" alt="mastercard" class="pointer" onclick="openpaymentMethodModal()"/>';
      } else if (method === 2) {
        showMethod.innerHTML =
          '<img src="pictures/paypal.png" alt="paypal" class="pointer" onclick="openpaymentMethodModal()"/>';
      } else if (method === 3) {
        showMethod.innerHTML =
          '<img src="pictures/visa.png" alt="visa" class="pointer" onclick="openpaymentMethodModal()"/>';
      } else {
        showMethod.innerHTML =
          '<img src="pictures/visa.png" alt="visa" class="pointer" onclick="openpaymentMethodModal()"/>';
      }
    }
  </script>
</body>

</html>