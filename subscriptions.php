<?php
require_once("includes/header.php");

if(!User::isLoggedIn()){
	header("Location: signIn.php");
}

$subscriptionProvider = new SubscriptionProvider($con, $userLoggedInObj);
$videos = $subscriptionProvider->getVideos();
$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="largeVideoGridContainer">
	<?php
		if(sizeof($videos) > 0){
			echo $videoGrid->createLarge($videos, "Подписки", false);
		}else{
			echo "Нет видео";
		}
	?>
</div>