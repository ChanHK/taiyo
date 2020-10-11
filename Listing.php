<?php
session_start();


$conn = mysqli_connect("localhost", "root", "", "taiyodb");

$listingResult = null;
$visitedUserResult = null;

if(isset($_GET['user_id']))
{
	$visitedUserID = $_GET['user_id'];
}
else
{
	header("Location: Homepage.php");
	exit();

}
if(isset($_SESSION['userID']))
{
	$user_ID = $_SESSION['userID'];
	$sql = "SELECT profile_photo FROM enduser WHERE enduser_id = $user_ID";
	$userResult = mysqli_query($conn, $sql);
}
else
{
	$user_ID = null;
}
$sql = "SELECT * FROM enduser WHERE enduser_id = $visitedUserID";
$visitedUserResult = mysqli_query($conn, $sql);

$sql = "SELECT product_image_id,
		product_image, 
		product.product_id, 
		product_name, 
		product_price, 
		product_description, 
		product_state, 
		category, 
		quantity, 
		product.enduser_id, 
		user_email,
		username, 
		user_description, 
		gender, 
		phone_number, 
		street_1, 
		street_2, 
		city, 
		c_state, 
		postcode, 
		age, 
		profile_photo 
		FROM productimage 
		LEFT JOIN product ON productimage.product_id = product.product_id 
		LEFT JOIN enduser ON product.enduser_id = enduser.enduser_id
		WHERE product.enduser_id = $visitedUserID";
				
$listingResult = mysqli_query($conn, $sql);

?>

<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
		<link rel="stylesheet" type="text/css" href="css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/listing.css"/>
		<link rel="stylesheet" type="text/css" href="css/header.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

	</head>
	<body>
		<?php
			include "header.php";
		?>
		<aside>
		<?php
			$row = mysqli_fetch_array($visitedUserResult);
			if($row['profile_photo'] != null)
			{
				echo "<a href='Listing.php?user_id=".$visitedUserID."'><img src='pictures/profile/" . $row['profile_photo'] . "' class='profile'". "alt='" .$row['profile_photo']. "' title=' " . $row['profile_photo'] . "'/></a>";
			}
			else
			{
				echo "<a href='Listing.php?user_id=".$visitedUserID."'><img src='pictures/profile/anonymous.png' class='profile'". "alt='no-picture' title='no-picture'/></a>";
			}
			echo "<div class='asidediv'>";
				echo "<p style='text-align:center'>".$row['username']."</p>";
				echo "</br>";
				echo "</br>";
				echo "<p>";
					echo "<i class='fa fa-map-marker'> &nbsp; </i>";
					echo $row['street_1'] . "</br>";
					echo $row['street_2'] . "</br>";
					echo $row['city'] . " " . $row['postcode'] . " </br>";		
					echo $row['c_state'];
				echo "</p>";
				echo "</br>";
				echo "<p>";
					echo "<i class='fa fa-phone'>&nbsp;</i>";
					echo $row['phone_number'];
				echo "</p>";
				echo "</br>";
				echo "<p>";
					echo "<i class='fa fa-envelope'>&nbsp;</i>";
					echo $row['user_email'];
				echo "</p>";
				echo "</br>";
				echo "<p>";
					echo "Age: " . $row['age'];
				echo "</p>";
				echo "</br>";
				echo "<p>";
					echo "Gender: ". $row['gender'];
				echo "</p>";
				echo "</br>";
				echo "<hr>";
				echo $row['user_description']. "</br>";
				if($user_ID == $visitedUserID)
				{
					echo "<a href='ProfileEdit.php'><button class='editbutton bgred' name='edit' id='edit'>Edit</button></a>";
				}
			?>
			</div>
		</aside>
		<section>
			<div class="coverdiv">
			</div>
			<div class="navigation bggrey">
			<?php
				echo "<div class='navigation bggrey'>";
					echo "<button class='active' name='listings' id='listings disabled'>Listings</button>";
					echo "<a href='Review.php?user_id=".$visitedUserID."'><button class='navbutton' name='review' id='review'>Reviews</button></a>";
				if($user_ID == $visitedUserID)
				{
					echo "<a href='Transactions.php?user_id=".$visitedUserID."'><button class='navbutton' name='transactions' id='transactions'>Transactions</button></a>";
					
				}
				echo "</div>";
			?>
			</div>
				<h2 class="listingtitle">Listings</h2>
					<div class="listscroll">
					<?php
						if($listingResult == null || mysqli_num_rows($listingResult) <= 0)
						{
							echo "<p style='color: grey'>&nbsp; &nbsp; No products found.</p>";
						}
						else
						{
							$prevProduct = 0;
							while($row = mysqli_fetch_array($listingResult))
							{
								if($row["product_id"] != $prevProduct)
								{			
									echo "<div class='productitem'>";
										echo "<img src='pictures/product/" . $row["product_image"] . "' class='productimg' alt='" . $row["product_image"] . "' title= '" . $row["product_image"]. "'/>";
										echo "</br>";
										echo "<p class='productdesc'><strong>".$row['product_name']."</strong></p>";
										echo "<p class='productdesc'>" . "RM" . $row["product_price"]. "</p>";
										echo "<a href='Product.php?product_id=" . $row["product_id"] . "' class='linkspanner'><span></span></a>";
									echo "</div>";
								}
								$prevProduct = $row["product_id"];
							}
						}
					?>
					</div>
				</div>
			</div>
		</section>
	</body>
	<script src="js/profileModal.js"></script>
</html>