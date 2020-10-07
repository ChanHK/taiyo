<header class="w-full bggrey">
	<div class="bgblack" style="text-align: right;">
		<ul>
			<?php
				if($user_ID != null)
				{
					echo "<li style='flex: 0'><a href='Wishlist.php'>Wishlist</a></li>";
					echo "<li style='flex: 0'><a href='Cart.php'>Cart</a></li>";
				}
			?>
			<li style="flex: 1"><a href="Signup.php">Register</a></li>
			<?php
				if($user_ID != null)
				{
					$row = mysqli_fetch_array($userResult);
					if($row['profile_photo'] == null)
					{
						echo "<li style='flex: 0'><a href='#'><img class='loginprofile' src='pictures/profile/anonymous.png' alt='no-photo' title='no-photo'/></a></li>";
					}
					else
					{
						echo "<li style='flex: 0'><a href='#'><img class='loginprofile' src='pictures/profile/". $row['profile_photo'] . "' alt = '" . $row['profile_photo'] . "' title = '" . $row['profile_photo'] . "'/></a></li>";
					}
				}
				else
				{
					echo "<li><a href='Login.php'>Login</a></li>";
				}
			?>
		</ul>
	</div>
	<div class="flex-row aligni">
		<a href="redirect.php"><img class="taiyou" src="pictures/main/Taiyou.png" alt="Logo" title="Logo"/></a>
		<form method="post" action="homepage.php">
			<input class="searchbar" type="search" name="searchbar" id="searchbar" placeholder=" Search..."/>
			<button class="searchbutton" type="submit" name="search" id="search"></button>
		</form>
		<?php
			if(!isset($visitedUserID) || $user_ID != $visitedUserID)
			{
				echo "<button class='sellbutton bgred' name='sell' id='sell'>Sell</button>";
			}
		?>
	</div>
</header>