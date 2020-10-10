<?php
session_start();


$conn = mysqli_connect("localhost", "root", "", "taiyodb");

$reviewResult = null;
$visitedUserResult = null;

$visitedUserID = 1;

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

$sql = "SELECT review_id, review_message, review_date, reviewer_id, t1.username as reviewer_username, t1.profile_photo as reviewer_profile, reviewee_id FROM review INNER JOIN enduser t1 ON t1.enduser_id = review.reviewer_id INNER JOIN enduser t2 ON t2.enduser_id = review.reviewee_id WHERE reviewee_id = " . $visitedUserID . " ORDER BY review_date, review_id ASC";
$reviewResult = mysqli_query($conn, $sql);

if(isset($_POST['reviewtext']))
{
	$sql = "INSERT INTO review (review_message, review_date, reviewer_id, reviewee_id) VALUES ('". $_POST['reviewtext'] . "' , CURDATE(), ". $user_ID . ", " . $visitedUserID . ");";
	mysqli_query($conn, $sql);
}

?>

<script type="text/javascript">



</script>

<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
		<link rel="stylesheet" type="text/css" href="css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/review.css"/>
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
				echo "<a href='#'><img src='pictures/profile/" . $row['profile_photo'] . "' class='profile'". "alt='" .$row['profile_photo']. "' title=' " . $row['profile_photo'] . "'/></a>";
			}
			else
			{
				echo "<a href='#'><img src='pictures/profile/anonymous.png' class='profile'". "alt='no-picture' title='no-picture'/></a>";
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
				echo "My Transaction Method:</br>";
				echo "</br>";
				echo "<a href='#'><img src='pictures/main/paypal1.jpg' class='payment' alt='payment' title='payment'/></a>";
				echo "</br>";
				echo "<hr>";
				echo $row['user_description']. "</br>";
				echo "<button class='editbutton bgred' name='edit' id='edit'>Edit</button>";
			?>
			</div>
		</aside>
		<section>
			<div class="coverdiv">
			</div>
			<div class="navigation bggrey">
			<?php
				echo "<div class='navigation bggrey'>";
					echo "<button class='navbutton' name='listings' id='listings'>Listings</button>";
					echo "<button class='active' name='review' id='review' disabled>Reviews</button>";
				if($user_ID == $visitedUserID)
				{
					echo "<a href='Transactions.php'><button class='navbutton' name='transactions' id='transactions'>Transactions</button></a>";
					
				}
				echo "</div>";
			?>
			</div>
			<h2 class="reviewtitle">Reviews</h2>
			<div class="reviewdiv">
				<div class="reviewscroll">
					<?php
						if($reviewResult != null && mysqli_num_rows($reviewResult)>0)
						{
							while($row = mysqli_fetch_array($reviewResult))
							{
								echo "<div class='reviewitem'>";
								echo "<div class='flex-row aligni'>";
								echo "<a href='#'><img class='revprofile' src='pictures/profile/".$row['reviewer_profile']."' alt='".$row['reviewer_profile']."' title='". $row['reviewer_profile'] . "'/></a>&nbsp;<a href='#'>".$row['reviewer_username']."</a>";
								echo "<p>&nbsp; &nbsp; Posted on <time>".$row['review_date']."</time></p>";
								echo "</div>";
								echo "</br>";
								echo $row['review_message'];
								echo "<hr>";
								echo "</div>";
							}
						}
						else
						{
							echo "<p style='color: grey'>&nbsp; &nbsp; There are no reviews</p>";
						}
					?>
				</div>
				<?php
				if($visitedUserID != $user_ID)
				{
				?>
				<div class = "newreview">
					<form method="post" action="Review.php" class="nospace reviewform">
						<textarea id="reviewtext" name="reviewtext" rows="10" cols="25" maxlength="255"></textarea>
						<input type="submit" class="reviewbutton bgred" name="reviewbutton" id="reviewbutton" value = "Review"/>
						<!-- <button type = "button" class="reviewbutton bgred" name="reviewbutton" id="reviewbutton" onclick="openModal()">Review</button> -->
					</form>
					<div id="myModal" class="modal">
						<!-- Modal content -->
						<div class="modal-content">
							</br>
							<p>Review has been submitted.</p>
							</br>
							<form class="okbuttonlink" action="Review.php">								
								<button type="submit" class="okbutton bgred" name="okButton">OK</button>
							</form>
						</div>
					</div>
					<p id="error" style = "color: red; display:none;"> You need to insert at least one character to publish the review!</p> 
				</div>
				<?php
				}
				?>
			</div>
		</section>
	</body>
	<script>
		
		var reviewButton = document.getElementById("reviewbutton");
		reviewButton.addEventListener('click', function(e)
		{
			var textbox = document.getElementById("reviewtext");
			if(textbox.value.length > 0)
			{
				e.submit();
			}
			else
			{
				e.preventDefault();
				var errorBox = document.getElementById("error");
				errorBox.style.display = "block";
			}
			
		});		
		// Get the modal
		var modal = document.getElementById("myModal");

		// When the user clicks on the button, open the modal
		function openModal() {
			modal.style.display = "block";
		}
		
		function close() {
			modal.style.display = "none";
		}
		
		var close = function close() {
			modal.style.display = "none";
		}
		
		
		<?php if(isset($_POST['reviewbutton'])) { ?> /* Your (php) way of checking that the form has been submitted */

			openModal();

		<?php } ?>                                    /* /form has been submitted */
		
	</script>
	<script src="js/profileModal.js"></script>
</html>