<!DOCTYPE html>
<html lang="en">

<head>
  <title>Login</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="css/util.css" />
  <link rel="stylesheet" type="text/css" href="css/login.css" />
</head>

<body>
  <div class="limiter">
    <div class="loginContainer">
      <div class="loginFormContainer">
        <form class="loginForm p-l-55 p-r-55 p-t-100" method="post">

          <div class="m-b-16">
            <input class="formInput" type="text" name="Email" id="email" placeholder="Email" onkeyup="saveValue(this)" />
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $email = $_POST['Email'];
              if ($email == "") {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please fill in your email address";
                echo "</a>";
              }
            }

            ?>
          </div>

          <div class="p-b-50">
            <input class="formInput" type="password" name="Password" id="password" placeholder="Password" onkeyup="saveValue(this)" />
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $email = $_POST['Password'];
              if ($email == "") {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please fill in your password";
                echo "</a>";
                echo "<br />";
              }
            }
            ?>
            <input type="checkbox" onclick="showOrHidePassword()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Show Password </input>
          </div>

          <div class="loginFormButtonContainer p-b-100">
            <button type="submit" class="loginFormButton" id="submitButton">Login</button>
          </div>
        </form>

        <?php
        $conn = mysqli_connect("localhost", "root", "", "taiyodb");
        $email = $password = "";
        $continue = false;
        $userID = 0;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $email = test_input($_POST["Email"]);
          $password = test_input($_POST["Password"]);

          if ($conn) {
            $sql = "SELECT user_id FROM user WHERE user_email = '$email' AND user_password = '$password'";
            $result = mysqli_query($conn, $sql);
            if (!$result || mysqli_num_rows($result) == 0) {
              echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold; margin-left: 28px;'>";
              echo "*This email or password does not exist, please check again";
              echo "</a>";
            } else {
              while ($a = mysqli_fetch_assoc($result)) {
                $user_id = $a['user_id'];
                $userID = intval($user_id);
                header("Location: Cart.php");
              }
            }
          }
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
  </div>
  <script>
    // if (window.history.replaceState) {
    //   window.history.replaceState(null, null, window.location.href);
    // }

    document.getElementById("email").value = getSavedValue("email");
    document.getElementById("password").value = getSavedValue("password");

    function saveValue(e) {
      var id = e.id;
      var val = e.value;
      sessionStorage.setItem(id, val);
    }

    function getSavedValue(v) {
      if (!sessionStorage.getItem(v)) {
        return "";
      }
      return sessionStorage.getItem(v);
    }


    function showOrHidePassword() {
      console.log("aaaa");
      var x = document.getElementById("password");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    }
  </script>

</body>

</html>