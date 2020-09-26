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
        <form class="loginForm p-l-55 p-r-55 p-t-100" name="thisForm" method="post" onsubmit="return validateForm()">
          <div class="m-b-16">
            <input class="formInput" type="text" name="email" id="EMAIL" placeholder="Email" onkeyup="saveValue(this)" />
            <a id="formInputEmail" style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'></a>
          </div>

          <div class="p-b-50">
            <input class="formInput" type="password" name="password" id="PASSWORD" placeholder="Password" onkeyup="saveValue(this)" />
            <a id="formInputPassword" style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'><br /></a>
            <input type="checkbox" onclick="showOrHidePassword()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Show Password </input>
          </div>

          <div class="loginFormButtonContainer p-b-100">
            <button class="loginFormButton">Login</button>
          </div>
        </form>
        <?php
        $conn = mysqli_connect("localhost", "root", "", "taiyodb");
        $email = $password = "";
        $userID = 0;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $email = test_input($_POST["email"]);
          $password = test_input($_POST["password"]);

          if ($conn) {
            $sql = "SELECT user_id FROM user WHERE user_email = '$email' AND user_password = '$password'";
            $result = mysqli_query($conn, $sql);
            if (!$result || mysqli_num_rows($result) == 0) {
              echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold; margin-left: 20px;'>";
              echo "*The email or password does not exist, please check again";
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
  <script type='text/javascript'>
    document.getElementById("EMAIL").value = getSavedValue("EMAIL");
    document.getElementById("PASSWORD").value = getSavedValue("PASSWORD");

    function saveValue(e) {
      var id = e.id;
      var val = e.value;
      localStorage.setItem(id, val);
    }

    function getSavedValue(v) {
      if (!localStorage.getItem(v)) {
        return "";
      }
      return localStorage.getItem(v);
    }

    function showOrHidePassword() {
      var x = document.getElementById("PASSWORD");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    }

    function validateForm() {
      var emailInput = document.forms["thisForm"]["email"].value;
      var passwordInput = document.forms["thisForm"]["password"].value;
      console.log('this' + '"' + emailInput + '"');
      if (emailInput == "") {
        document.getElementById("formInputEmail").innerHTML = "*Please fill in your email address";
        return false;
      }
      if (passwordInput == "") {
        document.getElementById("formInputPassword").innerHTML = "*Please fill in your password <br />";
        return false;
      }

    }
  </script>
</body>

</html>