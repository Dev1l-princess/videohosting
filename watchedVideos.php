<?php
require_once("includes/header.php");
require_once("includes/classes/WatchedVideosProvider.php");

if(!User::isLoggedIn()){
    header("Location: signIn.php");
}

$watchedVideosProvider = new watchedVideosProvider($con, $userLoggedInObj);
$videos = $watchedVideosProvider->getVideos();
$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="largeVideoGridContainer">
    <?php
    if(sizeof($videos) > 0){
        echo $videoGrid->createLarge($videos, "Видео которые вы смотрели", false);
    }else{
        echo "История просмотров пуста";
    }
    ?>
</div>