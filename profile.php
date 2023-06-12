<?php
	require_once("includes/header.php");
	require_once("includes/classes/ProfileGenerator.php");

	if(isset($_GET["id"])){
		$profileId = $_GET["id"];
	}else{
		echo "Канал не найден";
		exit();
	}

	$profileGenerator = new ProfileGenerator($con, $userLoggedInObj, $profileId);
	echo $profileGenerator->create();

	require_once("includes/footer.php")
?>