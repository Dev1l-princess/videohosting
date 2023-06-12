<?php
class NavigationMenuProvider{

	private $con, $userLoggedInObj;

	public function __construct($con, $userLoggedInObj){
		$this->con = $con;
		$this->userLoggedInObj = $userLoggedInObj;
	}

	public function create(){
		$menuHtml = $this->createNavItem("Главная", "assets/images/icons/home.png", "index.php");
		$menuHtml .= $this->createNavItem("Популярное", "assets/images/icons/trending.png", "trending.php");

		if(User::isLoggedIn()){
            $menuHtml .= $this->createNavItem("Подписки", "assets/images/icons/subscriptions.png", "subscriptions.php");
            $menuHtml .= $this->createNavItem("Понравившиеся", "assets/images/icons/thumb-up.png", "likedVideos.php");
            $menuHtml .= $this->createNavItem("История просмотров", "assets/images/icons/history.png", "watchedVideos.php");
			$menuHtml .= $this->createNavItem("Настройки", "assets/images/icons/settings.png", "settings.php");
			$menuHtml .= $this->createNavItem("Выйти", "assets/images/icons/logout.png", "logout.php");
            $menuHtml .= $this->createSubscriptionSection();
		}
		


		return "<div class='navigationItems'>
					$menuHtml
				</div>";
	}

	private function createNavItem($text, $icon, $link){
		return "<div class='navigationItem'>
					<a href='$link'>
						<img src='$icon'>
						<span>$text</span>
					</a>
				</div>";
	}

	private function createSubscriptionSection(){
		$subscriptions = $this->userLoggedInObj->getSubscriptions();

		$html = "<span class='heading'>Подписки</span>";
		foreach ($subscriptions as $sub){
			$subUsername = $sub->getUsername();
            $subUserId = $sub->getId();
			$html .= $this->createNavItem($subUsername, $sub->getProfilePic(), "profile.php?id=$subUserId");
		}
		return $html;
	}

}
?>