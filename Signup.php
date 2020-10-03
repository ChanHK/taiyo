<?php
session_start();
// session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>SignUp</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="css/util.css" />
  <link rel="stylesheet" type="text/css" href="css/signup.css" />
</head>

<body>
  <div class="limiter">
    <div class="signupContainer">
      <div class="signupFormContainer">
        <form class="signupForm p-l-55 p-r-55 p-t-100" method="post" id="thisFORM">
          <div class="m-b-16">
            <input class="formInput" type="text" name="username" id="USERNAME" placeholder="Username" onkeyup="saveValue(this)" />
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $username = $_POST['username'];
              if ($username == "") {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please fill in your username";
                echo "</a>";
              } else if (preg_match("/^(?=.{8,20})(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])/", $username) == 0) {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Error, should have 8-20 characters, no special";
                echo "<br />";
                echo "</a>";
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "characters except '.' and '_'.";
                echo "<br />";
                echo "</a>";
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "Example: Krispy_Honey, Clumsy.Jack99";
                echo "</a>";
              }
            }
            ?>
          </div>

          <div class="m-b-16">
            <input class="formInput" type="text" name="email" id="EMAIL" placeholder="Email" onkeyup="saveValue(this)" />
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $email = $_POST['email'];
              if ($email == "") {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please fill in your email";
                echo "</a>";
              } else if (preg_match("/^[\w\.]+@([\w]+\.)+[\w]{2,4}/", $email) == 0) {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please enter valid email, example: Clumsy@gmail.com";
                echo "<br />";
                echo "</a>";
              }
            }
            ?>
          </div>

          <div class="m-b-16">
            <input class="formInput" type="password" name="password" id="PASSWORD" placeholder="Password" onkeyup="saveValue(this)" />
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $password = $_POST['password'];
              if ($password == "") {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please fill in your password";
                echo "</a>";
              } else if (strlen($password) < 8) {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please enter password of length between 8-15";
                echo "</a>";
              }
              echo "<br />";
            }
            ?>
            <input type="checkbox" onclick="showOrHidePassword()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Show Password </input>
          </div>



          <div class="m-b-16">
            <select name="gender" id="GENDER" class="formInput">
              <option id="defaultOption" value="0" selected='selected' hidden>
                Gender
              </option>
              <option id="genderOption1" value="Male">Male</option>
              <option id="genderOption2" value="Female">Female</option>
              <option id="genderOption3" value="Others">Others</option>
            </select>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $option = isset($_POST['gender']) ? $_POST['gender'] : false;
              if (!$option) {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please select your gender";
                echo "</a>";
              }
            }
            ?>
          </div>


          <div class="p-b-50">
            <input class="formInput" type="text" name="phonenumber" id="PHONENUMBER" placeholder="Mobile Number" onkeyup="saveValue(this)" />
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $mobile = $_POST['phonenumber'];
              if ($mobile == "") {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please fill in your mobile number";
                echo "</a>";
              } else if (preg_match("/^(\+?6?01)[0-46-9]-*[0-9]{7,8}/", $mobile) == 0) {
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "*Please enter valid mobile number, example: ";
                echo "<br />";
                echo "</a>";
                echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
                echo "01x-xxxxxxx";
                echo "</a>";
              }
            }
            ?>
          </div>

          <div class="signupFormButtonContainer p-b-100">
            <button type="submit" class="signupFormButton" onclick="rerun()">Sign Up</button>
          </div>
        </form>

        <?php
        $conn = mysqli_connect("localhost", "root", "", "taiyodb");
        $username = $email = $password = $gender = $mobile = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $username = test_input($_POST["username"]);
          $email = test_input($_POST["email"]);
          $password = test_input($_POST["password"]);
          // $gender = intval($_POST["gender"]);
          $gender = $_POST["gender"];
          $mobile = test_input($_POST["phonenumber"]);
        }

        if ($username !== "" and $email !== "" and $password !== "" and $gender !== "" and $mobile !== "") {
          if ($conn) {
            $usernameCheckSQL = "SELECT username FROM enduser WHERE username = '$username'";
            $emailCheckSQL = "SELECT user_email FROM enduser WHERE user_email = '$email'";
            $sql = "INSERT INTO enduser (user_email, user_password, username, gender, phone_number) VALUES ('$email', '$password', '$username', '$gender', '$mobile')";
            $usernameResult = mysqli_query($conn, $usernameCheckSQL);
            $emailResult = mysqli_query($conn, $emailCheckSQL);
            // print_r($usernameResult);
            // print_r($emailResult);

            $booluser = $boolemail = false;
            $num = mysqli_num_rows($usernameResult);
            if (mysqli_num_rows($usernameResult) != 0) {
              echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold; margin-left: 28px;'>";
              echo "*This username is used, please enter a new one";
              echo "</a>";
              echo "<br />";
            } else {
              $booluser = true;
            }
            if (mysqli_num_rows($emailResult) != 0) {
              echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold; margin-left: 28px;'>";
              echo "*This email is used, please enter a new one";
              echo "</a>";
            } else {
              $boolemail = true;
            }

            echo $boolemail and $booluser;
            if ($boolemail and $booluser) {
              if (mysqli_query($conn, $sql)) {
                $userIDSQL = "SELECT enduser_id FROM enduser WHERE user_email = '$email' AND username = '$username'";
                $userIDResult = mysqli_query($conn, $userIDSQL);
                print_r($userIDResult);
                while ($a = mysqli_fetch_assoc($userIDResult)) {
                  $_SESSION["userID"] = intval($a['enduser_id']);; // store userID to session so all page can use this user ID
                  // header("Location: Cart.php");
                  echo "<script type='text/javascript'>window.top.location='Homepage.php';</script>";
                  exit;
                }
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
    // localStorage.clear();
    // sessionStorage.clear();

    if (localStorage.getItem("item") === null) {
      console.log(document.getElementById("GENDER").value);
      localStorage.setItem("item", document.getElementById("GENDER").value);
    }

    if (localStorage["item"] === "0") {
      document.getElementById("GENDER").style.color = "#848484";
    }

    document.getElementById("genderOption1").style.color = "black";
    document.getElementById("genderOption2").style.color = "black";
    document.getElementById("genderOption3").style.color = "black";
    document.getElementById("GENDER").onchange = function() {
      document.getElementById("GENDER").style.color = "black";
      if (document.getElementById("GENDER").value) {
        // console.log("thisss" + document.getElementById("GENDER").value);
        localStorage["item"] = document.getElementById("GENDER").value;
      }
    }

    function showOrHidePassword() {
      var x = document.getElementById("PASSWORD");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    }

    document.getElementById("USERNAME").value = getSavedValue("USERNAME");
    document.getElementById("EMAIL").value = getSavedValue("EMAIL");
    document.getElementById("PASSWORD").value = getSavedValue("PASSWORD");
    document.getElementById("GENDER").value = getSavedValue("item");
    document.getElementById("PHONENUMBER").value = getSavedValue("PHONENUMBER");

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

    function rerun() {
      document.getElementById("thisFORM").submit();
    }
  </script>
</body>

</html>