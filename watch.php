<?php
	require_once("includes/header.php");
	require_once("includes/classes/VideoPlayer.php");
	require_once("includes/classes/VideoInfoSection.php");
	require_once("includes/classes/Comment.php");
	require_once("includes/classes/CommentSection.php");

	if(!isset($_GET["id"])){
		echo "URL-адрес не передан на страницу";
		exit();
	}

	$video = new Video($con, $_GET["id"], $userLoggedInObj);

    if($video->getId() == null){
        echo "Видео не существует";
        exit();
}

	//$video->incrementViews();
?>

<div class="watchLeftColumn">

<?php
	$videoPlayer = new VideoPlayer($video);
	echo $videoPlayer->create(true);

	$VideoInfoSection = new VideoInfoSection($con, $video, $userLoggedInObj);
	echo $VideoInfoSection->create();

	$commentSection = new CommentSection($con, $video, $userLoggedInObj);
	echo $commentSection->create();
?>

</div>
    <script src="assets/js/videoPlayerActions.js"></script>
    <script src="assets/js/commentActions.js"></script>
<div class="suggestions">
	<?php
		$videoGrid = new VideoGrid($con, $userLoggedInObj);
		echo $videoGrid->create(null, null, false);
	?>
</div>


<?php require_once("includes/footer.php"); ?>