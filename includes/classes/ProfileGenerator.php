<?php
require_once("ProfileData.php");
class ProfileGenerator{

	private $con, $userLoggedInObj, $profileId;

	public function __construct($con, $userLoggedInObj, $profileId){
		$this->con = $con;
		$this->userLoggedInObj = $userLoggedInObj;
		$this->profileData = new ProfileData($con, $profileId);
	}

	public function create(){
        $profileId = $this->profileData->getProfileId();

		if(!$this->profileData->userExists()){
			return "User not exist.";
		}

		$coverPhotoSection = $this->createCoverPhotoSection();
		$headerSection = $this->createHeaderSection();
		$tabsSection = $this->createTabsSection();
		$contentSection = $this->createContentSection();

		return "<div class='profileContainer'>
					$coverPhotoSection
					$headerSection
					$tabsSection
					$contentSection
				</div>";
	}

	public function createCoverPhotoSection(){
		$coverPhotoSrc = $this->profileData->getCoverPhoto();
		$name = $this->profileData->getProfileUserFullName();

		return "<div class='coverPhotoContainer'>
                    <span class='channelName'>$name</span>
                    <img src='$coverPhotoSrc' class='coverPhoto'>
                </div>";
	}

	public function createHeaderSection(){
		$profileImage = $this->profileData->getProfilePic();
		$name = $this->profileData->getProfileUserFullName();
		$subCount = $this->profileData->getSubscriberCount();
		$button = $this->createHeaderButton();

		return "<div class='profileHeader'>
					<div class='userInfoContainer'>
						<img class='profileImage' src='$profileImage'>
						<div class='userInfo'>
							<span class='title'>$name</span>
							<span class='subscriberCount'>Подписчики: $subCount</span>
						</div>
					</div>
					<div class='buttonContainer'>
						<div class='buttonItem'>
							$button
						</div>
					</div>
				</div>";
	}

	public function createTabsSection(){
		return "<ul class='nav nav-tabs' role='tablist'>
				  <li class='nav-item'>
				    <a class='nav-link active' id='videos-tab' data-toggle='tab' 
				    	href='#videos' role='tab' aria-controls='home' aria-selected='true'>ВИДЕО</a>
				  </li>
				  <li class='nav-item'>
				    <a class='nav-link' id='about-tab' data-toggle='tab' 
				    	href='#about' role='tab' aria-controls='about' aria-selected='false'>ОПИСАНИЕ</a>
				  </li>
				</ul>";
	}

	public function createContentSection(){

		$videos = $this->profileData->getUsersVideos();

		if(sizeof($videos) > 0){
			$videoGrid = new videoGrid($this->con, $this->userLoggedInObj);
			$videoGridHtml = $videoGrid->create($videos, null, false);
		}else{
			$videoGridHtml = "<span>У этого пользователя нет видео</span>";
		}

		$aboutSection = $this->createAboutSection();

		return "<div class='tab-content channelContent'>
				  <div class='tab-pane fade show active' id='videos' role='tabpanel' aria-labelledby='videos-tab'>
				  	$videoGridHtml
				  </div>
				  <div class='tab-pane fade' id='about' role='tabpanel' aria-labelledby='about-tab'>
				  	$aboutSection
				  </div>
				</div>";
	}

	private function createHeaderButton(){
		if($this->userLoggedInObj->getId() == $this->profileData->getProfileId()){
			return "";
		}else{
			return ButtonProvider::createSubscriberButton(
				$this->con, 
				$this->profileData->getProfileUserObj(),
				$this->userLoggedInObj);
		}
	}

	private function createAboutSection(){
		$html = "<div class='section'>
					<div class='title'>
						<span>Подробности</span>
					</div>
					<div class='values'>";
		$details = $this->profileData->getAllUserDetails();
		foreach($details as $key => $value){
			$html .= "<span>$key: $value</span>";
		}

		$html .= "</div></div>";

		return $html;
	}

}
?>