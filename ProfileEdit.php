<?php
ob_start();
session_start();
$conn = mysqli_connect("localhost", "root", "", "taiyodb");
if (isset($_SESSION['userID'])) {
  $user_ID = $_SESSION['userID'];
  $sql = "SELECT * FROM enduser WHERE enduser_id = $user_ID";
  $userResult = mysqli_query($conn, $sql);
} else {
  $user_ID = null;
}

$usernameVerified =  $genderVerified = $phVerified = $ageVerified = $streetOneVerified = $streetTwoVerified = $postcodeVerified = $cityVerified = $stateVerified = false;
?>

<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
		<link rel="stylesheet" type="text/css" href="css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/header.css"/>
		<link rel="stylesheet" type="text/css" href="css/profileedit.css"/>
	</head>
	<body style="background-color: e0e0e0";>
		<?php
			include "header.php";
		?>
		<div class="maindiv">
			<form method="post" action="ProfileEdit.php" enctype="multipart/form-data">
				<section class="profileimageinput">
					<p style="text-align:center;"> Profile picture </p>
					<div id="imagepreview" class="imagepreview">
						<?php
							if($row['profile_photo'] != null)
							{
								echo "<img src='pictures/profile/".$row['profile_photo']."' alt='".$row['profile_photo']."' title='" .$row['profile_photo']. "' class='previewimage'/>";
							}
							else
							{
								echo "<img src='pictures/profile/anonymous.png' alt='no-photo' title='no-photo' class='previewimage'/>";
							}
						?>
					</div>
					</br>
					<input type="file" name="file" id="picture" accept=".png, .jpg, .jpeg"/>
					</br>
					</br>
					<p id='inputerror1'><a style='text-align:center; padding: 1em; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>File is too large in size! Only files of 5MB or lower is accepted.</a></p>
					<p id='inputerror2'><a style='text-align:center; padding: 1em; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>Wrong type of file was selected! Acceptable files are .png, .jpeg or .jpg only.</a></p>
				</section>
				<section class="userdetails">
					<p> Account Details </p>
					</br>
					<?php
						echo "<input class='inputbox' type='text' name='username' id='username' maxlength='30' placeholder='Username' value='".$row['username']."'/>";
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						  $username = $_POST['username'];
						  if ($username == "") {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please fill in your username";
							echo "</a>";
							$usernameVerified = false;
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
							$usernameVerified = false;
						  } else {
							$username = $_POST['username'];
							$usernameVerified = true;
						  }
					}
					?>
					</br>
					<?php
					$genders = array("Male", "Female", "Others");
					echo "<select class='selectbox' name='gender' id='gender'>";
						for($i=0; $i<count($genders); $i++)
						{
							if($genders[$i] == $row['gender'])
							{
								echo "<option value='$genders[$i]' selected='selected'>$genders[$i]</option>";
							}
							else
							{
								echo "<option value='$genders[$i]'>$genders[$i]</option>";
							}
						}
					echo "</select>";
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
					  $option = isset($_POST['gender']) ? $_POST['gender'] : false;
					  if (!$option) {
						echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
						echo "*Please select your gender";
						echo "</a>";
						$genderVerified = false;
					  } else {
						$gender = $_POST['gender'];
						$genderVerified = true;
					  }
					}
					?>
					</br>
					<?php
						echo "<input class='inputBox' type='text' name='number' id='number' maxlength='15' placeholder='Mobile Number' value='".$row['phone_number']."'/>";
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						  $mobile = $_POST['number'];
						  if ($mobile == "") {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please fill in your mobile number";
							echo "</a>";
							$phVerified = false;
						  } else if (preg_match("/^(\+?6?01)[0-46-9]-*[0-9]{7,8}/", $mobile) == 0) {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please enter valid mobile number, example: ";
							echo "<br />";
							echo "</a>";
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "01x-xxxxxxx";
							echo "</a>";
							$phVerified = false;
						  } else {
							$mobile = $_POST['number'];
							$phVerified = true;
						  }
						}
					?>
					</br>
					<?php
						echo "<input class='inputBox' type='number' name='age' id='age' placeholder='Age' onkeydown='return isNumberKey(event)' value='".$row['age']."'/>";
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						  $age = $_POST['age'];
						  if ($age == "") {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please fill in your age";
							echo "</a>";
							$ageVerified = false;
						  } else if ($age < 0 || $age > 110) {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please enter valid age";
							echo "</a>";
							$ageVerified = false;
						  } else {
							$age = $_POST['age'];
							$ageVerified = true;
						  }
						}
					?>
					</br>
					<?php
						echo "<input class='inputBox' type='text' name='street1' id='street1' maxlength='255' placeholder='Street Address 1' value='".$row['street_1']."'/>";
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						  $street_1 = $_POST['street1'];
						  if ($street_1 == "") {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please fill in street address";
							echo "</a>";
							$streetOneVerified = false;
						  } else if (preg_match("/^.{3,30}$/", $street_1) == 0) {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please enter valid street address, Eg: 'Jalan Ampang'";
							echo "</a>";
							$streetOneVerified = false;
						  } else {
							$street_1 = $_POST['street1'];
							$streetOneVerified = true;
						  }
						}
					?>
					</br>
					<?php
						echo "<input class='inputBox' type='text' name='street2' id='street2' maxlength='255' placeholder='Street Address 2' value='".$row['street_2']."'/>";
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						  $street_2 = $_POST['street2'];
						  if ($street_2 == "") {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please fill in street address";
							echo "</a>";
							$streetTwoVerified = false;
						  } else if (preg_match("/^.{3,30}$/", $street_2) == 0) {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please enter valid street address, Eg: 'Jalan Ampang' ";
							echo "</a>";
							$streetTwoVerified = false;
						  } else {
							$street_2 = $_POST['street2'];
							$streetTwoVerified = true;
						  }
						}
					?>
					</br>
					<?php
						echo "<input class='inputBox' type='text' name='postcode' id='postcode' maxlength='5' placeholder='Postcode' onkeydown='return isNumberKey(event)' value='".$row['postcode']."'/>";
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						  $postcode = $_POST['postcode'];
						  if ($postcode == "") {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please fill in postcode";
							echo "</a>";
							$postcodeVerified = false;
						  } else if (preg_match("/\b\d{5}\b/", $postcode) == 0) {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please enter valid postcode";
							echo "</a>";
							$postcodeVerified = false;
						  } else {
							$postcode = $_POST['postcode'];
							$postcodeVerified = true;
						  }
						}
					?>
					</br>
					<?php
						echo "<input class='inputBox' type='text' name='city' id='city' maxlength='130' placeholder='City' value='".$row['city']."'/>";
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						  $city = $_POST['city'];
						  if ($city == "") {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please fill in city name";
							echo "</a>";
							$cityVerified = false;
						  } else if (preg_match("/^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/", $city) == 0) {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please enter valid city name";
							echo "</a>";
							$cityVerified = false;
						  } else {
							$city = $_POST['city'];
							$cityVerified = true;
						  }
						}
					?>
					</br>
					<?php 
					$states = array("Sabah","Sarawak","Selangor", "Perak", "Johor", "Kelantan", "Pahang", "Negeri Sembilan", "Kedah", "Terengganu", "Penang", "Perlis", "Malacca");
					echo "<select class='selectbox' name='state' id='state'>";
					for($i=0; $i<count($states); $i++)
					{
						if($states[$i] == $row['c_state'])
						{
							echo "<option value='$states[$i]' selected='selected'>$states[$i]</option>";
						}
						else
						{
							echo "<option value='$states[$i]'>$states[$i]</option>";
						}
					}
					echo "</select>";
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
					  $option = isset($_POST['state']) ? $_POST['state'] : false;
					  if (!$option) {
						echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
						echo "*Please select a state";
						echo "</a>";
						$stateVerified = false;
					  } else {
						$state = $_POST['state'];
						$stateVerified = true;
					  }
					}
					?>
					</br>
					<?php
						echo "User Description: ";
						echo "</br>";
						echo "<textarea class='userdesc' name='userdesc' id='userdesc' maxlength='255'/>".$row['user_description']."</textarea>";
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
						  $text = isset($_POST['userdesc']); 
						  if (!$text) {
							echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold;'>";
							echo "*Please input some text for your description";
							echo "</a>";
							$stateVerified = false;
						  } else {
							$text = mysqli_real_escape_string($conn, $_POST['userdesc']);
							$textVerified = true;
						  }
						}
					?>
					<?php
						$conn = mysqli_connect("localhost", "root", "", "taiyodb");

						
						if ($usernameVerified == 1 and  $genderVerified == 1 and $phVerified == 1 and $ageVerified == 1 and $streetOneVerified == 1 and $streetTwoVerified == 1 and $postcodeVerified == 1 and $cityVerified == 1 and $stateVerified == 1 and $textVerified == 1)
						{
							if ($conn) 
							{
								
								$usernameCheckSQL = "SELECT username FROM enduser WHERE username = '$username'";
								$sql = "UPDATE enduser SET
										username = '$username',
										user_description = '$text',
										gender = '$gender',
										phone_number = '$mobile',
										age = $age,
										street_1 = '$street_1',
										street_2 = '$street_2',
										postcode = '$postcode',
										city = '$city',
										c_state = '$state'
										WHERE enduser_id = $user_ID";
								$usernameResult = mysqli_query($conn, $usernameCheckSQL);
								
								$num = 0;
								$booluser = false;
								while ($userRow = mysqli_fetch_array($usernameResult))
								{
									if($userRow['username'] !=  $row['username'])
									{
										$num++;
									}
								}
								if ($num != 0) 
								{
								  echo "<a style='padding: 0 35px 0 35px; color: rgb(226, 37, 37); font-size: 14px; font-weight: bold; margin-left: 28px;'>";
								  echo "*This username is used, please enter a new one";
								  echo "</a>";
								  echo "<br />";
								}
								else
								{
									mysqli_query($conn, $sql);
									if(isset($_FILES['file']))
									{
										echo "Hello";
										$files = $_FILES['file'];
										
										$validExtensions = array("png", "jpg", "jpeg");
										$wrongExtension = false;
										$fileExt = explode(".",$files['name']);
										$fileExt = end($fileExt);
										if(!in_array($fileExt, $validExtensions))
										{
											$wrongExtension = true;
										}				
										
										
										$sizeOverload = false;
										if($files['size'] > 5242880)
										{
											$sizeOverload = true;
										}

										if($wrongExtension == false && $sizeOverload == false)
										{
											$sql = "SELECT * FROM enduser WHERE enduser_id = $user_ID";
											if(file_exists("pictures/profile/".$row['profile_photo']));
											{
												unlink("pictures/profile/".$row['profile_photo']);
											}
											
											$fileExt = explode(".",$files['name']);
											$fileExt = end($fileExt);
											$fileName = "profile".$user_ID.".".$fileExt;
											
											move_uploaded_file($files['tmp_name'], "pictures/profile/$fileName");
											
											$sql = "UPDATE enduser SET profile_photo = '$fileName' WHERE enduser_id = $user_ID";
											mysqli_query($conn, $sql);
										}
									}
									header("Location: Listing.php?user_id=".$user_ID);
									ob_enf_fluch();
									exit();
								}
							}
						}
					?>
					</br>
					</br>
					<input type="submit" class="submitbutton bgred" id="update" name="update" value="Update"/> 
				</section>

			</form>
		</div>
	</body>
	<script>
		function isNumberKey(evt)
		{
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57))
			 return false;

			return true;
		}
		
		var fileInput = document.getElementById("picture");
		fileInput.addEventListener("change", function(event)
		{
			var wrongExtension = false;
		
			if(this.files[0].name.split(".").pop() != "jpg" && this.files[0].name.split(".").pop() != "png" && this.files[0].name.split(".").pop() != "jpeg")
			{
				wrongExtension = true;
			}
			if(wrongExtension == true)
			{
				document.getElementById("inputerror2").style.display = "block";
			}
			else
			{
				document.getElementById("inputerror2").style.display = "none";
			}
			var exceedFileSize = false;
			if(wrongExtension == false)
			{
				if(this.files[0].size > 5242880)
				{
					exceedFileSize = true;
				}
				
			}
			if(exceedFileSize == true)
			{
				document.getElementById("inputerror1").style.display = "block";
			}
			else
			{
				document.getElementById("inputerror1").style.display = "none";
			}
			
			
			var gallery = document.getElementById("imagepreview");
			
			if (this.files && wrongExtension == false) 
			{
				console.log("hey");
				var reader = new FileReader();

				reader.onload = function(event) {
					gallery.innerHTML = "<img class = 'previewimage' src='" + event.target.result + "' alt='preview' title='preview'/>"; 					
				}
				
				reader.readAsDataURL(this.files[0]);
			}
		
			
		});
		
		var sbmtButton = document.getElementById("update");
		sbmtButton.addEventListener("click", function(e)
		{
	
			var wrongExtension = false;
			if(fileInput.files != null)
			{
				if(fileInput.files[0].name.split(".").pop() != "jpg" && fileInput.files[0].name.split(".").pop() != "png" && fileInput.files[0].name.split(".").pop() != "jpeg")
				{
					wrongExtension = true;
				}
			}
			if(wrongExtension == true)
			{
				document.getElementById("inputerror2").style.display = "block";
			}
			else
			{
				document.getElementById("inputerror2").style.display = "none";
			}
			var exceedFileSize = false;
			if(wrongExtension == false && fileInput.files != null)
			{
				if(fileInput.files[0].size > 5242880)
				{
					exceedFileSize = true;
				}
			}
			if(exceedFileSize == true)
			{
				document.getElementById("fileerror3").style.display = "block";
			}
			else
			{
				document.getElementById("fileerror3").style.display = "none";
			}
			if(wrongExtension == true || exceedFileSize == true)
			{
				e.preventDefault();
			}
		});
		
	</script>
	<script src="js/profileModal.js"></script>
</html>