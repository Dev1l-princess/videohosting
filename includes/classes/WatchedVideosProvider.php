<?php

class WatchedVideosProvider
{
    private $con, $userLoggedInObj;

    public function __construct($con, $userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function getVideos() {
        $videos = array();
        $userId = $this->userLoggedInObj->getId();

        $query = $this->con->prepare("SELECT videoId FROM watchHistory WHERE userID=:userId ORDER BY viewDate DESC");
        $query->bindParam(":userId", $userId);
        $query->execute();

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $video = new Video($this->con, $row["videoId"], $this->userLoggedInObj);
            array_push($videos, $video);
        }

        return $videos;
    }
}