<?php require_once("includes/header.php"); ?>


<div class="video section">
	<?php
		$subscriptionProvider = new subscriptionProvider($con, $userLoggedInObj);
		$subscriptionVideos = $subscriptionProvider->getVideos();

		$videoGrid = new VideoGrid($con, $userLoggedInObj);

		echo $videoGrid->createForIndex(null, "Рекомендованные", false);

        if(User::isLoggedIn() && sizeof($subscriptionVideos) > 0){
            echo $videoGrid->createForIndex($subscriptionVideos, "Подписки", false);
        }
	?>
</div>


<?php require_once("includes/footer.php"); ?>