<?php
	session_start();
	unset($_SESSION['substring']);
	header("Location: Homepage.php");
	die();
?>