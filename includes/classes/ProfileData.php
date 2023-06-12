<?php
	class ProfileData{

		private $con, $profileUserObj,
            $months = [
            'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        ];


        public function __construct($con, $profileId){
			$this->con = $con;
			$this->profileUserObj = new User($con, $profileId);
		}

		public function getProfileUserObj(){
			return $this->profileUserObj;
		}

		public function getProfileUsername(){
			return $this->profileUserObj->getUsername();
		}

        public function getProfileId(){
            return $this->profileUserObj->getId();
        }

		public function userExists(){
			$query = $this->con->prepare("SELECT * FROM users WHERE id = :id");
			$query->bindParam(":id", $profileId);
            $profileId = $this->getProfileId();
			$query->execute();

			return $query->rowCount() != 0;
		}

		public function getCoverPhoto(){
			return "assets/images/coverPhotos/default-cover-photo.jpg";
		}

		public function getProfileUserFullName(){
			return $this->profileUserObj->getName();
		}

		public function getProfilePic(){
			return $this->profileUserObj->getProfilePic();
		}

		public function getSubscriberCount(){
			return $this->profileUserObj->getSubscriberCount();
		}

		public function getUsersVideos(){
			$query = $this->con->prepare("SELECT * FROM videos WHERE uploadedBy=:userId ORDER BY uploadDate DESC");
			$query->bindParam(":userId", $userId);
			$userId = $this->getProfileId();
			$query->execute();

			$videos = array();
			
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$videos[] = new Video($this->con, $row, $userId);
			}
			return $videos;
		}

		public function getAllUserDetails(){
			return array(
				"Имя" => $this->getProfileUserFullName(),
				"Логин" => $this->getProfileUsername(),
				"Подписчиков" => $this->getSubscriberCount(),
				"Всего просмотров" => $this->getTotalViews(),
				"Дата регистрации" => $this->getSignUpDate()
			);
		}

		private function getTotalViews(){
            $userId = $this->getProfileId();
			$query = $this->con->prepare("SELECT SUM(views) FROM videos WHERE uploadedBy=:uploadedBy");
			$query->bindParam(":uploadedBy", $userId);
			$query->execute();

			return $query->fetchColumn();
		}

		private function getSignUpDate(){
			$date = $this->profileUserObj->getSignUpDate();
            $month = date('n', strtotime($date))-1;
            return $this->months[$month].', '.date("j, Y", strtotime($date));
		}
	}
?>