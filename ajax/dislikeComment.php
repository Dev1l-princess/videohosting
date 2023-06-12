<?php
	require_once("../includes/config.php");
	require_once("../includes/classes/Comment.php");
	require_once("../includes/classes/User.php");

	$userId = $_SESSION["userLoggedIn"];
	$videoId = $_POST["videoId"];
	$commentId = $_POST["commentId"];

	$userLoggedInObj = new User($con, $userId);
	$comment = new Comment($con, $commentId, $userLoggedInObj, $videoId);

	echo $comment->dislike();
?>