<?php
require_once("includes/header.php");
require_once("includes/classes/SearchResultProvider.php");

if(!isset($_GET["term"]) || $_GET["term"] == ""){
	echo "Вы должны ввести поисковый запрос";
	exit();
}

$term = $_GET["term"];

if(!isset($_GET["orderBy"]) || $_GET["orderBy"] == "views"){
	$orderBy = "views";
}else{
	$orderBy = "uploadDate";
}

$searchResultProvider = new SearchResultProvider($con, $userLoggedInObj);
$videos = $searchResultProvider->getVideos($term, $orderBy);

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="largeVideoGridContainer">
	<?php
		if(sizeof($videos) > 0){
			echo $videoGrid->createLarge($videos,"Результатов найдено: " . sizeof($videos), true);
		}else{
			echo "Ничего не найдено";
		}
	?>
</div>

<?php require_once("includes/footer.php") ?>