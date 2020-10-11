<?php
	session_start();
	$conn = mysqli_connect("localhost", "root", "", "taiyodb");
	if (isset($_SESSION['userID'])) {
	  $user_ID = $_SESSION['userID'];
	  $sql = "SELECT profile_photo FROM enduser WHERE enduser_id = $user_ID";
	  $userResult = mysqli_query($conn, $sql);
	} else {
	  $user_ID = null;
	}
	if(isset($_GET['product_id']))
	{
		$productID = $_GET['product_id'];
	}
	else
	{
		header("Location: Homepage.php");
		exit();
	}
	
	$sql="SELECT * FROM product WHERE product_id = ".$productID;
	$product = mysqli_query($conn, $sql);
	$productRow = mysqli_fetch_array($product);
	
	$defaultProductName = $productRow['product_name'];
	$defaultQuantity = $productRow['quantity'];
	$defaultRinggit = (explode(".", (number_format($productRow['product_price'],2,'.',''))))[0];
	$defaultCents = (explode(".", (number_format($productRow['product_price'],2,'.',''))))[1];
	$defaultState = $productRow['product_state'];
	$defaultCategory = $productRow['category'];
	$defaultDesc = $productRow['product_description'];
	
	
	$productname = null;
	$quantity = null;
	$price = null;
	$state = null;
	$category = null;
	$description = null;
	
	if(isset($_POST['yesButton']))
	{
		$sql = "SELECT * FROM productimage WHERE product_id =".$productID;
		$productImages = mysqli_query($conn, $sql);
		
		while($productImageRow = mysqli_fetch_array($productImages))
		{
			unlink("pictures/product/".$productImageRow['product_image']);
		}
		
		$sql = "DELETE FROM product WHERE product_id = $productID";
		mysqli_query($conn, $sql);
		
		header("Location: Homepage.php");
		exit();
	}
	
	if(isset($_POST['productname']))
	{
		$productname = $_POST['productname'];
	}
	
	if(isset($_POST['quantity']))
	{
		$quantity = intval($_POST['quantity']);
	}
	
	if(isset($_POST['price']))
	{
		$ringgit = $_POST['price'];
	}
	else
	{
		$ringgit = "0";
	}
	
	if(isset($_POST['point']))
	{
		$cents = $_POST['point'];
	}
	else
	{
		$cents = "00";
	}
	
	$price = $ringgit .".". $cents;
	$price = floatval($price);
	
	if(isset($_POST['state']))
	{
		$state = $_POST['state'];
	}
	
	if(isset($_POST['category']))
	{
		$category = $_POST['category'];
	}
	
	if(isset($_POST['description']))
	{
		$description = $_POST['description'];
	}
	
	if($productname != null && $quantity != null && $price != null && $state != null && $category != null && $description != null)
	{
		$sql = "UPDATE product SET 
				product_name = '$productname', 
				product_price = $price, 
				product_description = '$description',
				product_state = '$state', 
				category = '$category', 
				quantity = $quantity
				WHERE product_id = $productID;";
		mysqli_query($conn, $sql);
		if(isset($_FILES['files']))
		{
			
			$files = $_FILES['files'];
			
			$validExtensions = array("png", "jpg", "jpeg");
			$wrongExtension = false;
			for($i=0; $i<count($files['name']); $i++)
			{
				$fileExt = explode(".",$files['name'][$i]);
				$fileExt = end($fileExt);
				if(!in_array($fileExt, $validExtensions))
				{
					$wrongExtension = true;
				}				
			}
			
			$sizeOverload = false;
			for($i=0; $i<count($files['size']); $i++)
			{
				if($files['size'][$i] > 5242880)
				{
					$sizeOverload = true;
				}				
			}
			
			if(count($files['size'])>3)
			{
				$tooManyFiles = true;
			}
			else
			{
				$tooManyFiles = false;
			}
			
			if($wrongExtension == false && $sizeOverload == false && $tooManyFiles == false)
			{
				$sql= "SELECT * FROM productimage WHERE product_id = ".$productID;
				$images = mysqli_query($conn, $sql);
				
				for($i=0; $i<mysqli_num_rows($images); $i++)
				{
					$row = mysqli_fetch_array($images);
					unlink("pictures/product/".$row['product_image']);
				}
				$sql= "DELETE FROM productimage WHERE product_id = ".$productID;
				mysqli_query($conn, $sql);
				for($i=0; $i<count($files['tmp_name']); $i++)
				{
					$fileExt = explode(".",$files['name'][$i]);
					$fileExt = end($fileExt);
					$fileName = "product".$productID.chr(97+$i).".".$fileExt;
					$targetFile = "pictures/product/".$fileName;
					
					$sql = "INSERT INTO productimage (product_id, product_image) VALUES ($productID, '$fileName')";
					mysqli_query($conn, $sql);
					move_uploaded_file($files['tmp_name'][$i], $targetFile);
				}
			}

		}
		header("Location: Product.php?product_id=".$productID);
		exit();
	}
?>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
		<link rel="stylesheet" type="text/css" href="css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/header.css"/>
		<link rel="stylesheet" type="text/css" href="css/edit.css"/>
	</head>
	<body style="background-color: #e0e0e0;">
		<?php
			include "header.php"
		?>
		<h1> Edit listing </h1>
		<div class="productdetails">
		<?php
			echo "<form class='productform' enctype='multipart/form-data' method='post' action='ProductEdit.php?product_id=".$productID."'>";
		?>
				<div class="flex-column aligni marginup">
					<label class="marginup" for="pictures">Upload 1-3 pictures about your product (do not upload if you want to use your old photos):</label>
					<input class="marginup"  type="file" name="files[]" id="pictures" accept=".png, .jpg, .jpeg" multiple  />
					</br>
					<div class="preview" id="preview">
					</div>
					</br>
					<p id="fileerror2">^Please ensure only 1-3 pictures are uploaded.^</p>
					<p id="fileerror3">^One or more of the files are too large in size! Only files of 5MB or lower is accepted.^</p>
					<p id="fileerror4">^Wrong type of file was selected! Acceptable files are .png, .jpeg or .jpg only.^</p>
					</br>
					</br>
				</div>
				<div class="flex-row aligni marginup">
					<label class="marginleft" for="productname">Product Name: </label>
					<?php
						echo "<input class='marginleft inputproduct' type='text' id='productname' name='productname' maxlength='30' value='$defaultProductName'/>";
					?>
				</div>
				</br>
				<p id="nameerror">^Please put in your product name^</p>
				</br>
				</br>
				<div>
					<label class="marginleft" for="quantity">Quantity: </label>
					<?php
						echo "<input class='marginleft inputquantity' type='number' id='quantity' name='quantity' min='1' max='99' onkeypress='return isNumberKey(event)' maxlength='2' value='$defaultQuantity'/>";
					?>
					<label class="marginleft"  for="price">Price (RM): </label>
					<?php
						echo "<input class='marginleft inputprice' type='text' id='price' name='price' onkeypress='return isNumberKey(event)' maxlength='5' value='$defaultRinggit'/>";
					?>
					<label for="price"><b>.</b></label>
					<?php
						echo "<input class='inputpricepoint' type='text' id='point' name='point' onkeypress='return isNumberKey(event)' maxlength='2' value='$defaultCents'/>";
					?>
				</div>
				</br>
				<p id="inputerror">^One of these boxes are either invalid or empty.^</p>
				<p id="inputerror2">^Your price must be a non-zero number.^</p>
				<p id="inputerror3">^Your quantity must be within the range of 1-99.^</p>
				</br>
				</br>
				<div class="flex-row aligni marginup">
					<label class="marginleft" for="state"> State: </label>
					<?php
						$states = array("New", "Secondhand");
						echo "<select class='marginleft' id='state' name='state'>";
						for($i=0; $i<count($states); $i++)
						{
							if($defaultState == $states[$i])
							{
								echo "<option value='".$states[$i]."' selected='selected'>".$states[$i]."</option>";
							}
							else
							{
								echo "<option value='".$states[$i]."'>".$states[$i]."</option>";
							}
						}
						echo "</select>";
					?>
					<label class="marginleft" for="category">Category: </label>
					<?php
						$categories = array("Games", "Consoles", "Controllers", "Computers", "Accessories", "VR");
						echo "<select class='marginleft' id='category' name='category'>";
						for($i=0; $i<count($categories); $i++)
						{
							if($defaultCategory == $categories[$i])
							{
								echo "<option value='".$categories[$i]."' selected='selected'>".$categories[$i]."</option>";
							}
							else
							{
								echo "<option value='".$categories[$i]."'>".$categories[$i]."</option>";
							}
						}
						echo "</select>";
					?>
				</div>
				</br>
				<div class="flex-column aligni marginup">				
					<label class="marginup" for="decription">Say some things about your product: </label>
					<textarea class="marginup description" name="description" id="description" rows="10" cols="100" maxlength="255"><?php echo $defaultDesc ?></textarea>
					</br>
					<p id="texterror">^Please ensure the text box is not empty^</p>
					</br>
					</br>
				</div>
				<div class="flex-row aligni">
					<input style="margin:4em; margin-left:8em; align-self:flex-start" class="submitbutton bgred" type="button" name="delete" id="delete" value="Delete" onclick="openConfirmModal()"/> 
					<input style="margin:4em; margin-right:8em; align-self:flex-end;" class="submitbutton bgred" type="submit" name="edit" id="edit" value="Edit"/>
				</div>
			</form>
			<div id="confirmModal" class="modal">
				<!-- Modal content -->
				<div class="modal-content">
					<span class="close" onclick="closeConfirmModal()">&times;</span>
					</br>
					<p>Are you sure you want to delete this product?</p>
					</br>
					<?php echo "<form method='post' action='ProductEdit.php?product_id=".$productID."'>" ?>
						<div style="align-content: flex-end; justify-content: flex-end" class="flex-row">
							<button type="submit" class="yesnobuttons bgred" name="yesButton">Yes</button>
							<button class="yesnobuttons bgred" type="button" name="noButton" onclick="closeConfirmModal()">No</button>
						</div>
					</form>
				</div>
			</div>
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
		
		var fileInput = document.getElementById("pictures");
		fileInput.addEventListener("change", function(event)
		{
			var numFiles = this.files.length;
			if(numFiles > 3)
			{
				document.getElementById("fileerror2").style.display = "block";
			}
			else
			{
				document.getElementById("fileerror2").style.display = "none";
			}
			var wrongExtension = false;
			for(var i=0; i<this.files.length; i++)
			{
				if(this.files[i].name.split(".").pop() != "jpg" && this.files[i].name.split(".").pop() != "png" && this.files[i].name.split(".").pop() != "jpeg")
				{
					wrongExtension = true;
				}
			}
			if(wrongExtension == true)
			{
				document.getElementById("fileerror4").style.display = "block";
			}
			else
			{
				document.getElementById("fileerror4").style.display = "none";
			}
			var exceedFileSize = false;
			if(wrongExtension == false)
			{
				for(var i=0; i<this.files.length; i++)
				{
					if(this.files[i].size > 5242880)
					{
						exceedFileSize = true;
					}
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
			
			
			var gallery = document.getElementById("preview");
			gallery.innerHTML = "";
			
			if (this.files && wrongExtension == false) {
				var filesAmount = this.files.length;
				if(filesAmount > 3)
				{
					filesAmount = 3
				}
				for (i = 0; i < filesAmount; i++) {
					var reader = new FileReader();

					reader.onload = function(event) {
						gallery.innerHTML = gallery.innerHTML + "<img class = 'previewimg' src='" + event.target.result + "' alt='preview' title='preview'/>"; 					
					}
					
					reader.readAsDataURL(this.files[i]);
				}
			}
			
		});
		
		var sbmtButton = document.getElementById("edit");
		sbmtButton.addEventListener("click", function(e)
		{
			var numFiles;
			numFiles = fileInput.files.length;
			if(numFiles > 3)
			{
				document.getElementById("fileerror2").style.display = "block";
			}
			else
			{
				document.getElementById("fileerror2").style.display = "none";
			}
			var wrongExtension = false;
			if(fileInput.files != null)
			{
				for(var i=0; i<fileInput.files.length; i++)
				{
					if(fileInput.files[i].name.split(".").pop() != "jpg" && fileInput.files[i].name.split(".").pop() != "png" && fileInput.files[i].name.split(".").pop() != "jpeg")
					{
						wrongExtension = true;
					}
				}
			}
			if(wrongExtension == true)
			{
				document.getElementById("fileerror4").style.display = "block";
			}
			else
			{
				document.getElementById("fileerror4").style.display = "none";
			}
			var exceedFileSize = false;
			if(wrongExtension == false && fileInput.files != null)
			{
				for(var i=0; i<fileInput.files.length; i++)
				{
					if(fileInput.files[i].size > 5242880)
					{
						exceedFileSize = true;
					}
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
			
			var productname = document.getElementById("productname").value;
			var invalidProdName = false;
			if(productname.length <= 0)
			{
				document.getElementById("nameerror").style.display = "block";
				invalidProdName = true;
			}
			else
			{
				document.getElementById("nameerror").style.display = "none";
			}
			
			var qty = document.getElementById("quantity").value;
			var invalidQty = false;
			if(qty.length <= 0)
			{
				document.getElementById("inputerror").style.display = "block";
				invalidQty = true;
			}
			else
			{
				var regExp = /[a-zA-Z]/g;
				var testString = qty;
				if(regExp.test(qty))
				{
					document.getElementById("inputerror3").style.display = "block";
					invalidQty = true;
				}	
				else if(parseInt(qty) <= 0 || parseInt(qty) > 99)
				{
					document.getElementById("inputerror3").style.display = "block";
					invalidQty = true;
				}
				else
				{
					document.getElementById("inputerror").style.display = "none"
					document.getElementById("inputerror3").style.display = "none";
				}
			}
			
			var ringgit = document.getElementById("price").value;
			var cents = document.getElementById("point").value;
			var price;
			var invalidPrice = false;
			if(ringgit.length <=0 && cents.length<=0)
			{
				document.getElementById("inputerror").style.display = "block";
				invalidPrice = true;
			}
			else
			{
				var regExp = /[a-zA-Z]/g;
				var testString = ringgit;
				if(regExp.test(ringgit) || regExp.test(cents))
				{
					document.getElementById("inputerror").style.display = "block";
					invalidPrice = true;
				}
				else
				{
					document.getElementById("inputerror").style.display = "none";
					if(ringgit.length <= 0)
					{
						ringgit = "0";
					}
					if(cents.length <=0)
					{
						cents = ".00";
					}
					price = ringgit + cents;
					price = parseFloat(price);
				}
			}
			
			if(price <= 0)
			{
				document.getElementById("inputerror2").style.display = "block";
				invalidPrice = false;
			}
			else
			{
				document.getElementById("inputerror2").style.display = "none";
			}
			
			var text = document.getElementById("description").value;
			var invalidText = false;
			if(text.length <= 0)
			{
				document.getElementById("texterror").style.display = "block";
				invalidText = true;
			}
			else
			{
				document.getElementById("texterror").style.display = "none";
			}
			
			if(numFiles > 3 || wrongExtension == true || exceedFileSize == true || invalidProdName == true || invalidQty == true || invalidPrice == true || invalidText == true)
			{
				e.preventDefault();
			}
		});
		
		var confirm = document.getElementById("confirmModal");
		
		function openConfirmModal()
		{
			confirm.style.display = "block";
		}
		
		function closeConfirmModal()
		{
			confirm.style.display = "none";
		}
		
		window.addEventListener("click", function(event) {
			if (event.target == confirm) 
			{

				confirm.style.display = "none";
			}
		});
		
	</script>
	<script src="js/profileModal.js"></script>
</html>