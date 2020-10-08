<?php
	session_start();
	unset($_SESSION['substring']);
	header("Location: homepage.php");
	die();
?>