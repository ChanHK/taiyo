<?php 

session_start();

$conn = mysqli_connect("localhost", "root", "", "taiyodb");
$productResult = null;
$userResult = null;

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

if(isset($_SESSION['substring']))
{
	$substring = $_SESSION['substring'];
}
else
{
	$substring = null;
}
if(isset($_POST['searchbar']))
{
	$_SESSION['substring'] = $_POST['searchbar'];
	$substring = $_POST['searchbar'];
}
if(isset($_POST['filter']))
{
	if($_POST['filter'] == "none")
	{
		$priceFilter = null;
	}
	else
	{
		$priceFilter = $_POST['filter'];
	}
}
else
{
	$priceFilter = null;
}
if(isset($_POST['cat']))
{
	$category = $_POST['cat'];
}
else
{
	$category = null;
}

if($conn)
{
	if($category == null && $priceFilter == null && $substring == null)
	{
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
				ORDER BY product.product_id DESC";
		$productResult = mysqli_query($conn, $sql);
	}
	else if($priceFilter == null && $substring == null)
	{
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
				WHERE ";
		for($i = 0; $i < count($category); $i++)
		{
			$sql = $sql . "category = '" . $category[$i] . "' ";
			if($i != count($category)-1)
			{
				$sql = $sql . "OR ";
			}
		}
			
		$sql = $sql . "ORDER BY product.product_id DESC";
		$productResult = mysqli_query($conn, $sql);
	}
	else if($category == null && $substring == null)
	{
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
				ORDER BY product_price " . $priceFilter;
		$productResult = mysqli_query($conn, $sql);
	}
	else if($category == null && $priceFilter == null)
	{
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
				WHERE product_name LIKE '%" . $substring . "%' OR " . "username LIKE '%" . $substring . "%' ORDER BY product.product_id DESC";
		$productResult = mysqli_query($conn, $sql);
	}
	else if($category == null)
	{
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
				WHERE product_name LIKE '%" . $substring . "%' OR " . "username LIKE '%" . $substring . "%' ORDER BY product_price " . $priceFilter;
		$productResult = mysqli_query($conn, $sql);
	}
	else if($priceFilter == null)
	{
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
				WHERE ";
		for($i = 0; $i < count($category); $i++)
		{
			$sql = $sql . "category = '" . $category[$i] . "' ";
			if($i != count($category)-1)
			{
				$sql = $sql . "OR ";
			}
		}
		$sql = $sql . "AND product_name LIKE '%" . $substring . "%' OR " . "username LIKE '%" . $substring . "ORDER BY product_id DESC";
		$productResult = mysqli_query($conn, $sql);
	}
	else if($substring == null)
	{
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
				WHERE ";
		for($i = 0; $i < count($category); $i++)
		{
			$sql = $sql . "category = '" . $category[$i] . "' ";
			if($i != count($category)-1)
			{
				$sql = $sql . "OR ";
			}
		}
		$sql = $sql . "ORDER BY product_price " . $priceFilter;
		$productResult = mysqli_query($conn, $sql);
	}
	else
	{
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
				WHERE ";
		for($i = 0; $i < count($category); $i++)
		{
			$sql = $sql . "category = '" . $category[$i] . "' ";
			if($i != count($category)-1)
			{
				$sql = $sql . "OR ";
			}
		}
		$sql = $sql . "AND WHERE product_name LIKE '%" . $substring . "%' OR " . "username LIKE '%" . $substring . "ORDER BY product_price " . $priceFilter;
		$productResult = mysqli_query($conn, $sql);
	}
}
else
{
	die ("Connection failed: " . mysqli_connect_error());
}


?>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
		<link rel="stylesheet" type="text/css" href="css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/header.css"/>
		<link rel="stylesheet" type="text/css" href="css/homepage.css"/>
	</head>
	<body>
		<?php
			include "header.php";
		?>
		<section>
			<div class= "filter">
				<form method="post" action="Homepage.php">
					<div class="filterform bgred">
						<label for="filter">Filter based on price: </label>
						<select id="filter" name="filter">
							<option value="none">-</option>
							<option value="asc">Ascending</option>
							<option value="desc">Descending</option>
						</select>
						<input type="submit" name="filterbutton" value="Filter">
					</div>
					<div class="whitespace">
						<p style="text-align: center; color: black; margin-top: 1em;">Selected categories to filter:</p>
						<div class="category">
							<div>
								<label>
									<input type="checkbox" class="checkbox" name="cat[]" value="Games"> 
									<div class= "item">
										<img src = "pictures/main/Games.png" class="categoryanim" alt="games" title="games"/>
										</br>
										<p class="itemname">Games</p>
									</div>
								</label>
							</div>
							<div>
								<label>
									<input type="checkbox" class="checkbox" name="cat[]" value="Consoles"> 
									<div class= "item">
										<img src = "pictures/main/PSZ.png" class="categoryanim" alt="consoles" title="consoles"/>
										</br>
										<p class="itemname">Consoles</p>
									</div>
								</label>
							</div>
							<div>
								<label>
									<input type="checkbox" class="checkbox" name="cat[]" value="Controllers"> 
									<div class= "item">
										<img src = "pictures/main/Controller.png" class="categoryanim" alt="controllers" title="controllers"/>
										</br>
										<p class="itemname">Controllers</p>
									</div>
								</label>
							</div>
							<div>
								<label>
									<input type="checkbox" class="checkbox" name="cat[]" value="Computers"> 
									<div class= "item">
										<img src = "pictures/main/Computer.png" class="categoryanim" alt="computers" title="computers"/>
										</br>
										<p class="itemname">Computers</p>
									</div>
								</label>
							</div>
							<div>
								<label>
									<input type="checkbox" class="checkbox" name="cat[]" value="Accessories"> 
									<div class= "item">
										<img src = "pictures/main/Accessory.png" class="categoryanim" alt="accessories" title="accessories"/>
										</br>
										<p class="itemname">Accessories</p>
									</div>
								</label>
							</div>
							<div>
								<label>
									<input type="checkbox" class="checkbox" name="cat[]" value="VR"> 
									<div class= "item">
										<img src = "pictures/main/VR.png" class="categoryanim" alt="vr" title="vr"/>
										</br>
										<p class="itemname">VR</p>
									</div>
								</label>
							</div>
						</div>
					</div>
				</form>
		</section>
		<section class="productsect">
			Products
			<hr>
			<div class="products">
				<?php
					if($productResult == null || mysqli_num_rows($productResult) <= 0)
					{
						echo "No products found.";
					}
					else
					{
						$prevProduct = 0;
						while($row = mysqli_fetch_array($productResult))
						{
							
							if($row["product_id"] != $prevProduct && $row['quantity'] > 0 )
							{			
								echo "<div class='productitem'>";
									echo "<img src='pictures/product/" . $row["product_image"] . "' class='productimg' alt=" . $row["product_image"] . "title= " . $row["product_image"]. "/>";
									echo "</br>";
									echo "<p class='productdesc'><strong>".$row['product_name']."</strong></p>";
									echo "<p class='productdesc'>" . "RM" . $row["product_price"]. "</p>";
									if($row['profile_photo'] != null)
									{
										echo "<p class='productdesc flex-row aligni' style='margin-top: 0.5em'><a href='Listing.php?user_id=".$row['enduser_id']."' class='userlink'><img class='profileicon' src='pictures/profile/" . $row["profile_photo"] . "' alt = '" . $row["profile_photo"] . "' title = '" . $row["profile_photo"]. "'/></a>" . "&nbsp; <a href='Listing.php?user_id=".$row['enduser_id']."' class='userlink'>". $row["username"] . "</a></p>";
									}
									else
									{
										echo "<p class='productdesc flex-row aligni' style='margin-top: 0.5em'><a href='Listing.php?user_id=".$row['enduser_id']."' class='userlink'><img class='profileicon' src='pictures/profile/anonymous.png' alt='no-picture' title='no-picture'/></a>" . "&nbsp; <a href='Listing.php?user_id=".$row['enduser_id']."' class='userlink'>". $row["username"] . "</a></p>";
									}
									echo "<a href='Product.php?product_id=" . $row["product_id"] . "' class='linkspanner'><span></span></a>";
								echo "</div>";
							}
							$prevProduct = $row["product_id"];
						}
					}
				?>
			</div>
		</section>
	</body>
	<script src="js/profileModal.js"></script>
</html>