<?php
$conn = mysqli_connect("localhost", "root", "", "taiyodb");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
$userID = $_SESSION["userID"]; // get user ID 
$noID = false;

if ($userID == null) {
  $noID = true;
  $sql = "SELECT * FROM wishlist WHERE enduser_id = '0'";
  $result = mysqli_query($conn, $sql);
} else if ($conn) {
  $noID = false;
  $sql = "SELECT * FROM wishlist WHERE enduser_id = $userID";
  $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>wishlist</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="css/util.css" />
  <link rel="stylesheet" type="text/css" href="css/cart.css" />
</head>

<body>
  <div class="limiter">
    <div class="cartContainer">
      <div class="cartButtonContainer">
        <label class="selectAllCheckBoxContainer">
          <input class="checkBox" type="checkbox" onclick="toggle(this)" />
          <a>Select All</a>
        </label>

        <form class="float-r p-t-15 p-b-15 p-r-20" method="post">
          <button type="submit" class="removeCheckOutButton" name="remove" form="form">Remove</button>
        </form>

        <form class="float-r p-t-15 p-b-15 p-l-20 p-r-20" method="post">
          <button type="submit" class="removeCheckOutButton" name="addToCart" form="form">Add to cart</button>
        </form>
      </div>


      <form id="form" method="post" class="form">
        <?php
        // print_r($result);
        $count = $result->num_rows;

        if ($noID) {
          echo "<div class='cartRowContainer'>";
          echo "<div class='rowBar'>";
          echo "<div class='productInfoContainer'>";
          echo "<div class='productInfo text-right'>";
          echo "<h3>Please sign in or register to view wishlist</h3>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
        } else if ($count == 0) {
          echo "<div class='cartRowContainer'>";
          echo "<div class='rowBar'>";
          echo "<div class='productInfoContainer'>";
          echo "<div class='productInfo text-right'>";
          echo "<h3>There are no items in this wishlist</h3>";
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
              echo "<input class='checkBox' type='checkbox' name='cb[]' value='{$rows['wishlist_item_id']}' />";
              echo "</div>"
              ?>

              <?php
              $imageSQL = "SELECT product_image FROM productimage, product, wishlist WHERE productimage.product_id=product.product_id AND {$rows['product_id']}=product.product_id limit 1";
              $image = mysqli_query($conn, $imageSQL);
              echo "<div class='imageContainer'>";
              while ($a = mysqli_fetch_assoc($image)) {
                echo "<img src='{$a['product_image']}' alt='product' />";
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

                  $productSellerSQL = "SELECT username FROM enduser WHERE enduser.enduser_id = {$rows['enduser_id']}";
                  $productSeller = mysqli_query($conn, $productSellerSQL);
                  while ($b = mysqli_fetch_assoc($productSeller)) {
                    echo "<h4>Seller: {$b['username']}</h4>";
                  }

                  $productPriceSQL = "SELECT product_price FROM product WHERE product.product_id = {$rows['product_id']}";
                  $productPrice = mysqli_query($conn, $productPriceSQL);
                  while ($c = mysqli_fetch_assoc($productPrice)) {
                    echo "<h4>Price: RM{$c['product_price']}</h4>";
                  }
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

      if (array_key_exists('addToCart', $_POST)) {
        if (!empty($_POST['cb'])) {
          $wishlistID = [];
          foreach ($_POST['cb'] as $check) {
            array_push($wishlistID, $check);
          }
          $productID = [];
          $userID = [];
          foreach ($wishlistID as $wl) {
            $productSQL = "SELECT product_id FROM wishlist WHERE wishlist_item_id = $wl";
            $product = mysqli_query($conn, $productSQL);
            while ($a = mysqli_fetch_assoc($product)) {
              array_push($productID, $a['product_id']);
            }

            $userSQL = "SELECT enduser_id FROM wishlist WHERE wishlist_item_id = $wl";
            $user = mysqli_query($conn, $userSQL);
            while ($a = mysqli_fetch_assoc($user)) {
              array_push($userID, $a['enduser_id']);
            }
          }

          $i = 0;
          while ($i < count($productID)) {
            $addToCartSQL = "INSERT INTO cart (quantity, product_id, enduser_id) VALUES (1,$productID[$i], $userID[$i])";
            if (mysqli_query($conn, $addToCartSQL)) {
              $deleteWishListSQL = "DELETE FROM wishlist WHERE wishlist_item_id = $wishlistID[$i]";
              if (mysqli_query($conn, $deleteWishListSQL)) {
                echo "<meta http-equiv='refresh' content='0'>";
              }
            }
            $i++;
          }
        }
      } else if (array_key_exists('remove', $_POST)) {
        if (!empty($_POST['cb'])) {
          foreach ($_POST['cb'] as $check) {
            $deleteSQL = "DELETE FROM wishlist WHERE wishlist_item_id = $check";
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
  </script>
</body>

</html>