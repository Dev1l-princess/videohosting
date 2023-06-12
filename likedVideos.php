<?php
require_once("includes/header.php");
require_once("includes/classes/LikedVideosProvider.php");

if(!User::isLoggedIn()){
	header("Location: signIn.php");
}

$likedVideosProvider = new LikedVideosProvider($con, $userLoggedInObj);
$videos = $likedVideosProvider->getVideos();
$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="largeVideoGridContainer">
	<?php
		if(sizeof($videos) > 0){
			echo $videoGrid->createLarge($videos, "Видео которые вам понравились", false);
		}else{
			echo "Нет понравившихся видео";
		}
	?>
</div>