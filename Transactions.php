<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "taiyodb");

$soldTransactionResult = null;
$boughtTransactionResult = null;
$transactionPictureResult = null;
$userResult = null;
$visitedUserResult = null;

$visitedUserID = 3;

if(isset($_SESSION['userID']))
{
	$userID = $_SESSION['userID'];
	$sql = "SELECT profile_photo FROM enduser WHERE enduser_id = $userID";
	$userResult = mysqli_query($conn, $sql);
}
else
{
	$userID = null;
}
$sql = "SELECT * FROM enduser WHERE enduser_id = $visitedUserID";
$visitedUserResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM transactionhistory LEFT JOIN product ON product.product_id = transactionhistory.product_id LEFT JOIN enduser ON transactionhistory.boughtuser_id = enduser.enduser_id WHERE belonginguser_id =" . $visitedUserID . " AND transaction_type = 'Sold'";
$soldTransactionResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM transactionhistory, product, enduser WHERE transactionhistory.product_id = product.product_id AND product.enduser_id = enduser.enduser_id WHERE belonginguser_id =" . $visitedUserID . "AND transaction_type = 'Bought'";
$boughtTransactionResult = mysqli_query($conn, $sql);

$sql = "SELECT * FROM productimage";
$transactionPictureResult = mysqli_query($conn, $sql);

?>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
		<link rel="stylesheet" type="text/css" href="css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/transactions.css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	</head>
	<body>
		<?php
			include "header.php";
		?>
		<aside>
		<?php
			$row = mysqli_fetch_array($visitedUserResult);
			echo "<a href='#'><img src='pictures/profile/" . $row['profile_photo'] . "' class='profile'". "alt='" .$row['profile_photo']. "' title=' " . $row['profile_photo'] . "'/></a>";
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
				echo "My Transaction Method:</br>";
				echo "</br>";
				echo "<a href='#'><img src='pictures/main/paypal1.jpg' class='payment' alt='payment' title='payment'/></a>";
				echo "</br>";
				echo "<hr>";
				echo $row['user_description']. "</br>";
				echo "<button class='sellbutton bgred' name='sell' id='sell'>Sell</button>";
			?>
			</div>
		</aside>
		<section>
			<div class="coverdiv">
			</div>
			<?php
				echo "<div class='navigation bggrey'>";
					echo "<button class='navbutton' name='listings' id='listings'>Listings</button>";
					echo "<button class='navbutton' name='review' id='review'>Reviews</button>";
				if($userID == $visitedUserID)
				{
					echo "<button class='active' name='transactions' id='transactions' disabled>Transactions</button>";
					
				}
				echo "</div>";
			?>
			<div class="soldboughtdiv">
				<div class="soldbought">
						Sold
				</div>
				<div class="scroll">
					<?php
					if($soldTransactionResult != null && mysqli_num_rows($soldTransactionResult) > 0)
					{
						while($row = mysqli_fetch_array($soldTransactionResult))
						{
							echo "<div class='transitem'>";
								while($row2 = mysqli_fetch_array($transactionPictureResult))
								{
									if($row2['product_id'] == $row['product_id'])
									{
										echo "<img class='itempic' src='pictures/product/" . $row2['product_image'] . "' alt= '" . $row2['product_image'] . "' title= ".  $row2['product_image'] ."'/>";
										mysqli_data_seek($transactionPictureResult, 0);
										break;
									}
								}
								echo "</br>";
								echo "<p>".$row['product_name']."</p>";
								echo "</br>";
								echo "<p style='display:flex; flex-direction: row; align-items:center; justify-content: center'>User Bought: &nbsp;" . "<img class = 'loginprofile' src='pictures/profile/" . $row['profile_photo']. "' alt = '". $row['profile_photo']. "' title = '".$row['profile_photo']."'/> &nbsp;". $row['username']. "</p>";
								echo "<a href='Product.php?product_id=". $row['product_id']. "'><span class='linkspanner'></span></a>";
							echo "</div>";
						}
					}
					else
					{
						echo "&nbsp; &nbsp; Whoops! You have not sold any items!";
					}
					?>
				</div>
				<hr class="nospace">
				<div class="soldbought">
					Bought
				</div>
				<div class="scroll">
					<?php
					if($boughtTransactionResult != null && mysqli_num_rows($boughtTransactionResult) > 0)
					{
						while($row = mysqli_fetch_array($boughtTransactionResult))
						{
							echo "<div class='transitem'>";
								while($row2 = mysqli_fetch_array($transactionPictureResult))
								{
									if($row2['product_id'] == $row['product_id'])
									{
										echo "<img class='itempic' src='pictures/product/" . $row2['product_image'] . "' alt= '" . $row2['product_image'] . "' title= ".  $row2['product_image'] ."'/>";
										mysqli_data_seek($transactionPictureResult, 0);
										break;
									}
								}
								echo "</br>";
								echo "<p>".$row['product_name']."</p>";
								echo "</br>";
								echo "<p style='display:flex; flex-direction: row; align-items:center; justify-content: center'>User Sold: &nbsp;" . "<img class = 'loginprofile' src='pictures/profile/" . $row['profile_photo']. "'alt = '". $row['profile_photo']. "' title = '".$row['profile_photo']."'/> &nbsp;". $row['username']. "</p>";
								echo "<a href='Product.php?product_id=". $row['product_id']. "'><span class='linkspanner'></span></a>";
							echo "</div>";
						}
					}
					else
					{
						echo "&nbsp; &nbsp; Whoops! You have not bought any items!";
					}
					?>
				</div>
			</div>
		</section>
	</body>