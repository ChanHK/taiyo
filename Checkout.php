<?php
$conn = mysqli_connect("localhost", "root", "", "taiyodb");
error_reporting(E_ERROR | E_WARNING | E_PARSE); // remove notice
session_start();
if (isset($_SESSION['userID'])) {
  $user_ID = $_SESSION['userID'];
  $sql = "SELECT profile_photo FROM enduser WHERE enduser_id = $user_ID";
  $userResult = mysqli_query($conn, $sql);
} else {
  $user_ID = null;
}
$cartIDArray = [];
$quantity = $_GET['productQuantity'] ? $_GET['productQuantity'] : null;

if ($_SESSION['cartIDArray'] != null) {
  foreach ($_SESSION['cartIDArray'] as $a) {
    array_push($cartIDArray, $a);
  }
  $_SESSION["productID"] = null;
} else {
  $productID = $_SESSION["productID"];
}
$isReviewExist = $_SESSION['review'];
$productIDStorage = [];
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>checkout</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="css/reset.css" />
  <link rel="stylesheet" type="text/css" href="css/util.css" />
  <link rel="stylesheet" type="text/css" href="css/checkout.css" />
  <link rel="stylesheet" type="text/css" href="css/header.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>

<body>
  <?php
  include "header.php";
  ?>
  <div class="limiter">
    <div class="checkoutContainer">

      <div class="selectedProductsContainer">
        <?php
        if ($productID == null) {
          foreach ($cartIDArray as $cartID) {
            $cartProductUserIDSQL = "SELECT product_id, enduser_id, quantity FROM cart WHERE cart_item_id = $cartID";
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
              $imageSQL = "SELECT product_image FROM productimage WHERE product_id = $a[product_id] limit 1";
              $imageResult = mysqli_query($conn, $imageSQL);
              while ($b = mysqli_fetch_assoc($imageResult)) {
                // print_r($b);
                echo "<div class='productInfoContainer'>";
                echo "<div class='float-l p-t-5'>";
                echo "<img class='pictureSize' src='pictures/product/" . $b['product_image'] . "' alt='product' />";
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

          $imageSQL = "SELECT product_image FROM productimage WHERE product_id = '$productID' limit 1";
          $imageResult = mysqli_query($conn, $imageSQL);
          while ($i = mysqli_fetch_assoc($imageResult)) {
            echo "<div class='productInfoContainer'>";
            echo "<div class='float-l p-t-5'>";
            echo "<img class='pictureSize' src='pictures/product/" . $i['product_image'] . "' alt='product'/>";
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
          $userAddressSQL = "SELECT username, street_1, street_2, city, c_state, postcode FROM enduser WHERE enduser_id = $user_ID";
          $userIDResult = mysqli_query($conn, $userAddressSQL);
          while ($a = mysqli_fetch_assoc($userIDResult)) {
            echo "<i class='fa fa-map-marker p-b-10'><a class='f-w-b p-l-18'>{$a['username']}</a></i>";
            echo "<br />";
            echo "<p class='p-l-27 fs-14'>";
            echo "{$a['street_1']},";

            if ($a['street_2'] !== "") {
              echo " {$a['street_2']},";
            }
            echo "{$a['postcode']},  {$a['city']},  {$a['c_state']}";
            echo "</p>";
          }
          ?>
          <br />

          <?php
          $phoneNumberSQL = "SELECT phone_number FROM enduser WHERE enduser_id = $user_ID";
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
          $emailSQL = "SELECT user_email FROM enduser WHERE enduser_id = $user_ID";
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
            <img id="visa" class="pointer" src="pictures/main/visa.png" alt="visa" onclick="openpaymentMethodModal()" />
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
      <div class="modalContentContainerTheSec">
        <h3 class="p-t-20 p-l-20 p-r-20">
          Shipping Info
          <span class="close" onclick="closeshippingInfoModal()">&times;</span>
        </h3>
        <br />
        <hr class="m-l-20 m-r-20" />

        <form class="editShippingForm" method="post">
          <i class="fa fa-map-marker p-b-10"><a class="f-w-b p-l-18 fs-18">Address</a></i>
          <?php
          $addressSQL = "SELECT street_1, street_2, postcode, city, c_state, phone_number FROM enduser WHERE enduser_id = $user_ID";
          $addressResult = mysqli_query($conn, $addressSQL);
          while ($e = mysqli_fetch_assoc($addressResult)) {
            echo "<br />";
            echo "<a class='p-l-25 fs-14 p-b-5'>Street 1</a>";
            echo "<div class='m-b-16 m-l-20 m-r-20'>";
            echo "<input class='formInput' type='text' name='streetOne' placeholder='Insert your street name here' value='{$e['street_1']}' pattern='^.{3,30}$' required/>";
            echo "</div>";

            echo "<a class='p-l-25 fs-14 p-b-5'>Street 2</a>";
            echo "<div class='m-b-16 m-l-20 m-r-20'>";
            echo "<input class='formInput' type='text' name='streetTwo' placeholder='Insert your street name here (optional)' value='{$e['street_2']}' pattern='^.{3,30}$'/>";
            echo "</div>";

            echo "<a class='p-l-25 fs-14 p-b-5'>Postcode</a>";
            echo "<div class='m-b-16 m-l-20 m-r-20'>";
            echo "<input class='formInput' type='text' name='postcode' placeholder='Insert your postcode here' value='{$e['postcode']}' pattern='\b\d{5}\b' required/>";
            echo "</div>";

            echo "<a class='p-l-25 fs-14 p-b-5'>City</a>";
            echo "<div class='m-b-16 m-l-20 m-r-20'>";
            echo "<input class='formInput' type='text' name='city' placeholder='Insert your city here' value='{$e['city']}' pattern='^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$' required/>";
            echo "</div>";

            echo "<a class='p-l-25 fs-14 p-b-5'>State</a>";
            echo "<div class='m-b-16 m-l-20 m-r-20'>";
            // echo "<input class='formInput' type='text' name='c_state' placeholder='Insert your state here' value='{$e['c_state']}' pattern='^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$' required/>";
            echo "<select name='c_state' id='STATE' class='formInput'>";
            echo "<option id='defaultOption' value='{$e['c_state']}' selected='selected' hidden>";
            echo "{$e['c_state']}";
            echo "</option>";
            echo "<option id='state1' value='Sabah'>Sabah</option>";
            echo "<option id='state2' value='Sarawak'>Sarawak</option>";
            echo "<option id='state3' value='Selangor'>Selangor</option>";
            echo "<option id='state4' value='Perak'>Perak</option>";
            echo "<option id='state5' value='Johor'>Johor</option>";
            echo "<option id='state6' value='Kelantan'>Kelantan</option>";
            echo "<option id='state7' value='Pahang'>Pahang</option>";
            echo "<option id='state8' value='Negeri Sembilan'>Negeri Sembilan</option>";
            echo "<option id='state9' value='Kedah'>Kedah</option>";
            echo "<option id='state10' value='Terengganu'>Terengganu</option>";
            echo "<option id='state11' value='Penang'>Penang</option>";
            echo "<option id='state12' value='Peris'>Peris</option>";
            echo "<option id='state13' value='Malacca'>Malacca</option>";
            echo "</select>";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $option = isset($_POST['c_state']) ? $_POST['c_state'] : false;
              if (!$option) {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please select a state";
                echo "</a>";
                $stateVerified = false;
              } else {
                $stateVerified = true;
              }
            }

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
        $street_1 = $street_2 = $postcode = $city = $c_state = $phone = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $street_1 = test_input($_POST["streetOne"]);
          $street_2 = test_input($_POST["streetTwo"]);
          $postcode = intval(test_input($_POST["postcode"]));
          $city = test_input($_POST["city"]);
          $c_state = test_input($_POST["c_state"]);
          $phone = test_input(strval($_POST["phoneNumber"]));
        }
        if ($street_1 !== "" and $postcode !== "" and $city !== "" and $c_state !== "" and $phone !== "") {
          $updateSQL = "UPDATE enduser SET street_1 = '$street_1', street_2 = '$street_2',  postcode = '$postcode', city = '$city', c_state = '$c_state', phone_number = '$phone' WHERE enduser_id = $user_ID";
          $updateResult = mysqli_query($conn, $updateSQL);
          echo "<script type='text/javascript'>window.top.location='Checkout.php';</script>";
          exit;
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
          <img src="pictures/main/mastercardbig.png" alt="mastercard" class="paymentMethodImg m-r-40 pointer" onclick="selectMastercard()" />
          <img src="pictures/main/paypalbig.png" alt="paypal" class="paymentMethodImg m-r-40 pointer" onclick="selectPaypal()" />
          <img src="pictures/main/visabig.png" alt="visa" class="paymentMethodImg pointer" onclick="selectVisa()" />
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
          Confirm to purchase this/these product(s)?
        </h3>
        <br /><br /><br /><br /><br /><br />
        <form class="buttonContainer" method="post">
          <button class="formButton m-l-auto m-r-15 m-b-20" onclick="closeConfirmationModal()">
            No
          </button>
          <button class="formButton m-r-20 m-b-20" type="submit" name="intoTrans">
            Yes
          </button>
          <?php
          $done = false;
          if (isset($_POST['intoTrans'])) {
            if ($productID == null) {
              foreach ($cartIDArray as $x) {
                $dataSQL = "SELECT quantity, product_id, enduser_id FROM cart WHERE cart_item_id = $x";
                $dataResult = mysqli_query($conn, $dataSQL);
                $getSellerIDSQL = "SELECT product.enduser_id FROM product, cart WHERE cart.product_id = product.product_id";
                $getSellerIDResult = mysqli_query($conn, $getSellerIDSQL);
                while ($g = mysqli_fetch_assoc($getSellerIDResult)) {
                  while ($y = mysqli_fetch_assoc($dataResult)) {
                    $storeTransSQL = "INSERT INTO TransactionHistory (transaction_type, quantity, product_id, belonginguser_id) VALUES ('Bought', '{$y['quantity']}', '{$y['product_id']}', '{$y['enduser_id']}' )";
                    mysqli_query($conn, $storeTransSQL);
                    $storeTransSQL = "INSERT INTO TransactionHistory (transaction_type, quantity, product_id, belonginguser_id, boughtuser_id) VALUES ('Sold', '{$y['quantity']}', '{$y['product_id']}', '{$g['enduser_id']}', '{$y['enduser_id']}' )";
                    mysqli_query($conn, $storeTransSQL);
                    array_push($productIDStorage, $y['product_id']);

                    $getRealProductQuantitySQL = "SELECT quantity FROM product WHERE product_id = '{$y['product_id']}'";
                    $getRealProductQuantityResult = mysqli_query($conn, $getRealProductQuantitySQL);

                    while ($abc = mysqli_fetch_assoc($getRealProductQuantityResult)) {
                      // echo "<script type='text/javascript'> alert('".json_encode($abc)."') </script>";
                      $updateQuantityDSQL = "UPDATE product SET quantity = '{$abc['quantity']}' - '{$y['quantity']}' WHERE product_id = '{$y['product_id']}'";
                      mysqli_query($conn, $updateQuantityDSQL);
                    }
                  }
                }
                $_SESSION["productIDStorage"] = $productIDStorage;
                $deleteSQL = "DELETE FROM cart WHERE cart_item_id = $x";
                mysqli_query($conn, $deleteSQL);
              }
              unset($_SESSION['cartIDArray']);
              $done = true;
            } else {
              $getUserIDSQL = "SELECT enduser_id, quantity FROM product WHERE product_id = $productID";
              $dataResult = mysqli_query($conn, $getUserIDSQL);
              while ($g = mysqli_fetch_assoc($dataResult)) {
                $storeTransSQL = "INSERT INTO TransactionHistory (transaction_type, quantity, product_id, belonginguser_id) VALUES ('Bought', $quantity, $productID, $user_ID)";
                mysqli_query($conn, $storeTransSQL);
                $storeTransSQL = "INSERT INTO TransactionHistory (transaction_type, quantity, product_id, belonginguser_id, boughtuser_id) VALUES ('Sold', $quantity, $productID, '{$g['enduser_id']}', $user_ID)";
                mysqli_query($conn, $storeTransSQL);
                $updateQuantity = "UPDATE product SET quantity = '{$g['quantity']}'- $quantity WHERE product_id = $productID";
                mysqli_query($conn, $updateQuantity);
              }
              $done = true;
            }
          }
          if ($done) {
            $_SESSION['review'] = '1';
            echo "<meta http-equiv='refresh' content='0'>";
          }
          ?>
        </form>
      </div>
    </div>

    <?php
    if ($isReviewExist == '1') {
      echo "<div id='checkOutNoticeModal' class='modalContainer display-b'>";
      echo "<div class='modalContentContainer'>";
      echo "<h3 class='p-t-20 p-l-20 p-r-20'>";
      echo "Notice";
      echo "</h3>";
      echo "<br />";
      echo "<hr class='m-l-20 m-r-20' />";
      echo "<br />";
      echo "<h3 class='p-t-20 p-l-20 p-r-20'>";
      echo "Your order is confirmed! It is on its way.";
      echo "</h3>";
      echo "<br /><br />";
      echo "<div class='carImage'>";
      echo "<img src='https://media.giphy.com/media/8mpR0LykCqObF8t7Y5/giphy.gif' alt='delivery incoming !!!' width=250 >";
      echo "</div>";
      echo "<br /><br />";
      echo "<form class='buttonContainer' method='post'>";
      echo "<button type='button' class='formButton m-l-auto m-r-15 m-b-20' onclick='openReviewModal()'>";
      echo "Review Seller";
      echo "</button>";
      echo "<button class='formButton m-r-20 m-b-20' type='button' onclick='closeNoticeModal()'>Continue Shopping</button>";
      echo "</form>";

      echo "</div>";
      echo "</div>";

      $_SESSION['review'] = null;
    }
    ?>

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
          $sellerNameSQL = "SELECT username FROM enduser, product WHERE product.enduser_id = enduser.enduser_id AND product_id = $productID";
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
          print_r($_SESSION["productIDStorage"]);
          foreach ($_SESSION["productIDStorage"] as $h) {
            $getUsernameSQL = "SELECT username FROM enduser, product WHERE enduser.enduser_id = product.enduser_id AND product_id = $h";
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

            $sellerDataSQL = "SELECT product_name, product_price FROM product WHERE product_id = $h";
            $sellerDataResult = mysqli_query($conn, $sellerDataSQL);
            while ($k = mysqli_fetch_assoc($sellerDataResult)) {
              echo "<h4>Sold: {$k['product_name']}</h4>";
              echo "<h4>Price: RM {$k['product_price']}</h4></div>";
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
        if ($productID !== null) {
          $getSellerIDSQL = "SELECT enduser.enduser_id FROM enduser, product WHERE product.product_id = $productID AND product.enduser_id = enduser.enduser_id";
          $getSellerIDResult = mysqli_query($conn, $getSellerIDSQL);
          while ($m = mysqli_fetch_assoc($getSellerIDResult)) {
            $reviewSQL = "INSERT INTO review (review_message, review_date, reviewer_id, reviewee_id) VALUES ('$review', CURDATE(), $user_ID, {$m['enduser_id']})";
            mysqli_query($conn, $reviewSQL);
          }
          echo "<script type='text/javascript'>window.top.location='Homepage.php';</script>";
          exit;
        } else {
          array_push($reviewArray, $review);
          $goToCart = true;
        }
      }
      if ($goToCart) {
        $countReview = 0;
        foreach ($_SESSION["productIDStorage"] as $n) {

          $getSellerIDSQL = "SELECT enduser.enduser_id FROM enduser, product WHERE enduser.enduser_id = product.enduser_id AND product.product_id = $n";
          $getSellerIDResult = mysqli_query($conn, $getSellerIDSQL);
          while ($m = mysqli_fetch_assoc($getSellerIDResult)) {
            $reviewSQL = "INSERT INTO review (review_message, review_date, reviewer_id, reviewee_id) VALUES ('$reviewArray[$countReview]', CURDATE(), $user_ID, {$m['enduser_id']})";
            mysqli_query($conn, $reviewSQL);
          }

          $countReview++;
        }
        unset($_SESSION['cartIDArray']);
        echo "<script type='text/javascript'>window.top.location='Homepage.php';</script>";
        exit;
      }
    }
  }
  ?>
  <script>
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
      window.top.location = 'Homepage.php';
    }

    function openReviewModal() {
      reviewSellerModal.style.display = "block";
      document.documentElement.style.overflow = "hidden";
      document.body.scroll = "no";
    }

    function closeReviewModal() {
      reviewSellerModal.style.display = "none";
      document.documentElement.style.overflow = "scroll";
      document.body.scroll = "yes";
    }


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
          '<img src="pictures/main/mastercard.png" alt="mastercard" class="pointer" onclick="openpaymentMethodModal()"/>';
      } else if (method === 2) {
        showMethod.innerHTML =
          '<img src="pictures/main/paypal.png" alt="paypal" class="pointer" onclick="openpaymentMethodModal()"/>';
      } else if (method === 3) {
        showMethod.innerHTML =
          '<img src="pictures/main/visa.png" alt="visa" class="pointer" onclick="openpaymentMethodModal()"/>';
      } else {
        showMethod.innerHTML =
          '<img src="pictures/main/visa.png" alt="visa" class="pointer" onclick="openpaymentMethodModal()"/>';
      }
    }
  </script>
  <script src="js/profileModal.js"></script>
</body>

</html>