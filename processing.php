<?php
require_once("includes/header.php");
require_once("includes/classes/VideoUploadData.php");
require_once("includes/classes/VideoProcessor.php");

if(!isset($_POST["uploadButton"])){
	echo "Не выбран файл.";
	exit();
}

$videoUploadData = new VideoUploadData(
                            $_FILES["fileInput"], 
                            $_POST["titleInput"],
                            $_POST["descriptionInput"],
                            $_POST["privacyInput"],
                            $_POST["categoryInput"],
                            $userLoggedInObj->getId()
                        );


$VideoProcessor = new VideoProcessor($con);
$wasSuccessful = $VideoProcessor->upload($videoUploadData);

if($wasSuccessful){
	echo "Загрузка прошла успешно";
}
?>