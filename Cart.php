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
$noID = false;
// echo $user_ID;
if ($user_ID == null) {
  $noID = true;
  $sql = "SELECT * FROM cart WHERE enduser_id = '0'";
  $result = mysqli_query($conn, $sql);
} else if ($conn) {
  $noID = false;
  $sql = "SELECT * FROM cart WHERE enduser_id = $user_ID";
  $result = mysqli_query($conn, $sql);
}
$usernameCount = 0;

$getProductDataSQL = "SELECT product.product_id, product.quantity FROM product,cart WHERE cart.product_id = product.product_id AND cart.enduser_id = $user_ID";
$getProductDataResult = mysqli_query($conn, $getProductDataSQL);
while ($pd = mysqli_fetch_assoc($getProductDataResult)) {
  // print_r($pd);
  $getCartProductDataSQL = "SELECT quantity FROM cart WHERE product_id = '{$pd['product_id']}'";
  $getCartProductDataResult = mysqli_query($conn, $getCartProductDataSQL);
  while ($cd = mysqli_fetch_assoc($getCartProductDataResult)) {
    // print_r($cd);
    if ($cd['quantity'] > $pd['quantity']) {
      $deleteSQL = "DELETE FROM cart WHERE product_id = '{$pd['product_id']}'";
      mysqli_query($conn, $deleteSQL);
      echo "<meta http-equiv='refresh' content='0'>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>cart</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="css/util.css" />
  <link rel="stylesheet" type="text/css" href="css/cart.css" />
  <link rel="stylesheet" type="text/css" href="css/header.css" />
</head>

<body>
  <div class="limiter">
    <div class="cartContainer">
      <?php
      include "header.php";
      ?>
      <div class="cartButtonContainer">
        <label class="selectAllCheckBoxContainer">
          <input class="checkBox" type="checkbox" onclick="toggle(this)" />
          <a>Select All</a>
        </label>

        <div class="float-r p-t-15 p-b-15 p-r-20">
          <button class="removeCheckOutButton" form="form">remove</button>
        </div>

        <form class="float-r p-t-15 p-b-15 p-l-20 p-r-20">
          <button type="submit" class="removeCheckOutButton" form="form" name="co">check out</button>
        </form>
      </div>

      <form id="form" method="post" class="form">
        <?php
        $count = $result->num_rows;
        if ($noID) {
          echo "<div class='cartRowContainer'>";
          echo "<div class='rowBar'>";
          echo "<div class='productInfoContainer'>";
          echo "<div class='productInfo text-right'>";
          echo "<h3>Please sign in or register to view cart</h3>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
        } else if ($count == 0) {
          echo "<div class='cartRowContainer'>";
          echo "<div class='rowBar'>";
          echo "<div class='productInfoContainer'>";
          echo "<div class='productInfo text-right'>";
          echo "<h3>There are no items in this cart</h3>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
        }
        ?>
        <?php
        while ($rows = mysqli_fetch_assoc($result)) {
        ?>
          <div class="cartRowContainer">
            <div class="rowBar">
              <?php
              echo "<div class='checkBoxContainer'>";
              echo "<input class='checkBox' type='checkbox' name='cb[]' value='{$rows['cart_item_id']}' />";
              echo "</div>"
              ?>

              <?php
              $imageSQL = "SELECT product_image, product.product_id FROM productimage, product, cart WHERE productimage.product_id=product.product_id AND {$rows['product_id']}=product.product_id limit 1";
              $image = mysqli_query($conn, $imageSQL);
              echo "<div class='imageContainer'>";
              while ($a = mysqli_fetch_assoc($image)) {
                echo "<a href='Product.php?product_id={$a['product_id']}'>";
                echo "<img src='pictures/product/" . $a['product_image'] . "' alt='product'/>";
                echo "</a>";
              }
              echo "</div>";
              ?>

              <div class="productInfoContainer">
                <div class="productInfo">
                  <?php
                  $productNameSQL = "SELECT product_name FROM product WHERE product.product_id = {$rows['product_id']}";
                  $productName = mysqli_query($conn, $productNameSQL);
                  while ($a = mysqli_fetch_assoc($productName)) {
                    echo "<h3>{$a['product_name']}</h3>";
                  }
                  $productSellerSQL = "SELECT enduser.username FROM enduser, product, cart WHERE cart.enduser_id = {$rows['enduser_id']} AND cart.product_id = product.product_id AND enduser.enduser_id = product.enduser_id";
                  $productSeller = mysqli_query($conn, $productSellerSQL);
                  $unCount = 0;
                  while ($b = mysqli_fetch_assoc($productSeller)) {
                    if ($unCount == $usernameCount) {
                      echo "<h4>Seller: {$b['username']}</h4>";
                      break;
                    }
                    $unCount++;
                  }

                  $productPriceSQL = "SELECT product_price FROM product WHERE product.product_id = {$rows['product_id']}";
                  $productPrice = mysqli_query($conn, $productPriceSQL);
                  while ($c = mysqli_fetch_assoc($productPrice)) {
                    echo "<h4>Price: RM{$c['product_price']}</h4>";
                  }
                  $productQtySQL = "SELECT quantity FROM cart WHERE cart.product_id = {$rows['product_id']} AND cart.cart_item_id = {$rows['cart_item_id']}";
                  $productQty = mysqli_query($conn, $productQtySQL);
                  while ($d = mysqli_fetch_assoc($productQty)) {
                    echo "<h4>Quantity: {$d['quantity']}</h4>";
                  }
                  $usernameCount++;
                  ?>
                </div>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </form>
      <?php
      $conn = mysqli_connect("localhost", "root", "", "taiyodb");
      if (isset($_POST["co"])) {
        if (isset($_POST['cb']) && is_array($_POST['cb'])) {
          $cartIDArray = [];
          foreach ($_POST['cb'] as $value) {
            array_push($cartIDArray, $value);
          }
          $_SESSION['cartIDArray'] = $cartIDArray;
          echo "<script type='text/javascript'>window.top.location='Checkout.php';</script>";
          exit;
        }
      } else {
        if (!empty($_POST['cb'])) {
          foreach ($_POST['cb'] as $check) {
            $deleteSQL = "DELETE FROM cart WHERE cart_item_id = $check";
            if (mysqli_query($conn, $deleteSQL)) {
              echo "<meta http-equiv='refresh' content='0'>";
            }
          }
        }
      }
      ?>
    </div>
  </div>
  <script>
    let checkboxes = document.getElementsByName("cb[]");

    function toggle(source) {
      for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
      }
    }

    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }

    function directToProduct() {
      window.top.location = 'Product.php';
      exit;
    }
  </script>
</body>

</html>