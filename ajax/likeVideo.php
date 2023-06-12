<?php
	require_once("../includes/config.php");
	require_once("../includes/classes/Video.php");
	require_once("../includes/classes/User.php");

	$userId = $_SESSION["userLoggedIn"];
	$videoId = $_POST["videoId"];

	$userLoggedInObj = new User($con, $userId);
	$video = new Video($con, $videoId, $userLoggedInObj);

	echo $video->like();
?>