<?php
	class SubscriptionProvider{

		private $con, $userLoggedInObj;

		public function __construct($con, $userLoggedInObj){
			$this->con = $con;
			$this->userLoggedInObj = $userLoggedInObj;
		}

		public function getVideos(){
			$videos = array();
			$subscriptions = $this->userLoggedInObj->getSubscriptions();

			if(sizeof($subscriptions) > 0){
				for($i=0; $i<sizeof($subscriptions); $i++){
					if($i == 0){
						$subsArrayString = "?";
					}else{
						$subsArrayString .= ", ?";
					}
				}

				$videoSql = "SELECT * FROM videos WHERE uploadedBy IN ($subsArrayString) ORDER BY uploadDate DESC";
				$videoQuery = $this->con->prepare($videoSql);

				
				$i = 1;
				foreach($subscriptions as $sub){
					$subId = $sub->getId();
					$videoQuery->bindValue($i++, $subId);
				}
				
				$videoQuery->execute();
				while($row = $videoQuery->fetch(PDO::FETCH_ASSOC)){
					$video = new Video($this->con, $row, $this->userLoggedInObj);
					array_push($videos, $video);
				}

			}
			return $videos;
		}
	}
?>