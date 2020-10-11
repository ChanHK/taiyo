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
	
	$productname = null;
	$quantity = null;
	$price = null;
	$state = null;
	$category = null;
	$description = null;
	
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
				$sql = "INSERT INTO product(product_name, product_price, product_description, product_state, category, quantity, enduser_id)
				VALUES ('$productname', $price, '$description', '$state', '$category', $quantity, $user_ID);";
				mysqli_query($conn, $sql);
				$sql = "SELECT product_id FROM product ORDER BY product_id DESC LIMIT 1;";
				$lastRow = mysqli_query($conn, $sql);
				$row = mysqli_fetch_array($lastRow);
				$latestProdID = $row['product_id'];
				for($i=0; $i<count($files['tmp_name']); $i++)
				{
					$fileExt = explode(".",$files['name'][$i]);
					$fileExt = end($fileExt);
					$fileName = "product".$latestProdID.chr(97+$i).".".$fileExt;
					$targetFile = "pictures/product/".$fileName;
					
					$sql = "INSERT INTO productimage (product_id, product_image) VALUES ($latestProdID, '$fileName');";  
					mysqli_query($conn, $sql);
					move_uploaded_file($files['tmp_name'][$i], $targetFile);
				}
				header("Location: Sell.php?post=1");
			}

		}
	}
?>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
		<link rel="stylesheet" type="text/css" href="css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/header.css"/>
		<link rel="stylesheet" type="text/css" href="css/sell.css"/>
	</head>
	<body style="background-color: #e0e0e0">
		<?php
			include "header.php"
		?>
		<h1> Create listing </h1>
		<div class="productdetails">
			<form class="productform" enctype="multipart/form-data" method="post" action="Sell.php">
				<div class="flex-column aligni marginup">
					<label class="marginup" for="pictures">Upload 1-3 pictures about your product:</label>
					<input class="marginup"  type="file" name="files[]" id="pictures" accept=".png, .jpg, .jpeg" multiple  />
					</br>
					<div class="preview" id="preview">
					</div>
					</br>
					<p id="fileerror1">^No files were uploaded.^</p>
					<p id="fileerror2">^Please ensure only 1-3 pictures are uploaded.^</p>
					<p id="fileerror3">^One or more of the files are too large in size! Only files of 5MB or lower is accepted.^</p>
					<p id="fileerror4">^Wrong type of file was selected! Acceptable files are .png, .jpeg or .jpg only.^</p>
					</br>
					</br>
				</div>
				<div class="flex-row aligni marginup">
					<label class="marginleft" for="productname">Product Name: </label>
					<input class="marginleft inputproduct" type="text" id="productname" name="productname" maxlength="30">
				</div>
				</br>
				<p id="nameerror">^Please put in your product name^</p>
				</br>
				</br>
				<div>
					<label class="marginleft" for="quantity">Quantity: </label>
					<input class="marginleft inputquantity" type="number" id="quantity" name="quantity" min="1" max="99" onkeypress="return isNumberKey(event)" maxlength="2">
					<label class="marginleft"  for="price">Price (RM): </label>
					<input class="marginleft inputprice" type="text" id="price" name="price" onkeypress="return isNumberKey(event)" maxlength="5"/>
					<label for="price"><b>.</b></label>
					<input class="inputpricepoint" type="text" id="point" name="point" onkeypress="return isNumberKey(event)" maxlength="2"/>
				</div>
				</br>
				<p id="inputerror">^One of these boxes are either invalid or empty.^</p>
				<p id="inputerror2">^Your price must be a non-zero number.^</p>
				<p id="inputerror3">^Your quantity must be within the range of 1-99.^</p>
				</br>
				</br>
				<div class="flex-row aligni marginup">
					<label class="marginleft" for="state"> State: </label>
					<select class="marginleft" id="state" name="state">
						<option value="New">New</option>
						<option value="Secondhand">Secondhand</option>
					</select>
					<label class="marginleft" for="category">Category: </label>
					<select class="marginleft" id="category" name="category">	
						<option value="Games">Games</option>
						<option value="Consoles">Consoles</option>
						<option value="Controllers">Controllers</option>
						<option value="Computers">Computers</option>
						<option value="Accessories">Accessories</option>
						<option value="VR">VR</option>
					</select>
				</div>
				</br>
				<div class="flex-column aligni marginup">				
					<label class="marginup" for="decription">Say some things about your product: </label>
					<textarea class="marginup description" name="description" id="description" rows="10" cols="100" maxlength="255"></textarea>
					</br>
					<p id="texterror">^Please ensure the text box is not empty^</p>
					</br>
					</br>
				</div>
				<input style="margin:4em; margin-right:8em; align-self:flex-end;" class="submitbutton bgred" type="submit" name="post" id="post" value="Post"/>
			</form>
			<div id="myModal" class="modal">
				<!-- Modal content -->
				<div class="modal-content">
					</br>
					<p>The listing has been posted.</p>
					</br>								
					<a href="Homepage.php" class="okbuttonlink"><button class="okbutton bgred" name="okButton">OK</button></a>
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
		
		var sbmtButton = document.getElementById("post");
		sbmtButton.addEventListener("click", function(e)
		{
			var numFiles;
			numFiles = fileInput.files.length;
			if(numFiles > 3)
			{
				document.getElementById("fileerror2").style.display = "block";
			}
			else if (numFiles<=0)
			{
				document.getElementById("fileerror1").style.display = "block";
			}
			else
			{
				document.getElementById("fileerror1").style.display = "none";
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
			
			if((numFiles > 3 || numFiles <=0) || wrongExtension == true || exceedFileSize == true || invalidProdName == true || invalidQty == true || invalidPrice == true || invalidText == true)
			{
				e.preventDefault();
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
		
		
		<?php if(isset($_GET['post'])) { ?> /* Your (php) way of checking that the form has been submitted */

			openModal();

		<?php } ?>                                    /* /form has been submitted */
	</script>
	<script src="js/profileModal.js"></script>
</html>