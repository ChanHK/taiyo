<?php
$conn = mysqli_connect("localhost", "root", "", "taiyodb");

if ($conn) {
  $sql = "Select * from cart";
  $result = mysqli_query($conn, $sql);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>cart</title>
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



        <div class="float-r p-t-15 p-b-15 p-r-20">
          <button class="removeCheckOutButton" form="form">remove</button>
        </div>

        <div class="float-r p-t-15 p-b-15 p-l-20 p-r-20">
          <button onclick="checkOut()" class="removeCheckOutButton">check out</button>
        </div>

      </div>

      <form id="form" method="post" class="form">
        <?php
        // print_r($result);
        $count = $result->num_rows;
        // echo $count;
        // echo gettype($count);
        if ($count == 0) {
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
              // print_r ($rows);
              $imageSQL = "SELECT product_image FROM productimage, product, cart WHERE productimage.product_id=product.product_id AND {$rows['product_id']}=product.product_id limit 1";
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

                  $productSellerSQL = "SELECT username FROM user WHERE user.user_id = {$rows['user_id']}";
                  $productSeller = mysqli_query($conn, $productSellerSQL);
                  while ($b = mysqli_fetch_assoc($productSeller)) {
                    echo "<h4>Seller: {$b['username']}</h4>";
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


      if (!empty($_POST['cb'])) {
        foreach ($_POST['cb'] as $check) {
          echo $check;
          $deleteSQL = "DELETE FROM cart WHERE cart_item_id = $check";
          if (mysqli_query($conn, $deleteSQL)) {
            echo "<meta http-equiv='refresh' content='0'>";
          }
        }
      }
      // print_r($_POST['cb']);
      ?>

      <!-- <div class="cartRowContainer">
        <div class="rowBar">
          <div class="checkBoxContainer">
            <input class="checkBox" type="checkbox" checked="checked" />
          </div>
          <div class="imageContainer">
            <img src="pictures/ps5-controller.jpg" alt="product" />
          </div>
          <div class="productInfoContainer">
            <div class="productInfo">
              <h3>magna etiam tempor orci eu</h3>
              <h4>Seller: egestas congue quisque</h4>
              <h4>Price: RM 10000</h4>
            </div>
          </div>
        </div>
      </div> -->
    </div>
  </div>
  <script>
    let checkboxes = document.getElementsByName("cb[]");

    function toggle(source) {
      for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
      }
    }

    function validateChecked() {
      let checkBoxValues = [];
      for (i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
          checkBoxValues.push(checkboxes[i].value);
        }
      }
      return checkBoxValues;
    }

    function checkOut() {
      var checkBoxValues = validateChecked();

      if (checkBoxValues.length === 0) {
        console.log("please select at least a products")
      } else {
        sessionStorage.setItem('checkBoxValues', checkBoxValues);
      }
      // for (let i = 0; i < checkBoxValues.length; i++) {
      //   console.log(checkBoxValues[i]);
      // }
    }
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>

</html>