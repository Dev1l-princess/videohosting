<?php

class ButtonProvider{

	public static $signInFunction = "notSignIn()";

	public static function createLink($link){
		return User::isLoggedIn() ? $link : ButtonProvider::$signInFunction;
	}

	public static function createButton($text, $imageSrc, $action, $class){

		$image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>";

		$action = ButtonProvider::createLink($action);

		return "<button class='$class' onclick='$action'>
					$image
					<span class='text'>$text</span>
				</button>";
	}

	public static function createHyperlinkButton($text, $imageSrc, $href, $class){

		$image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>";

		return "<a href='$href'>
					<button class='$class'>
						$image
						<span class='text'>$text</span>
					</button>
				</a>";
	}

	public static function createUserProfileButton($con, $id){
		$userObj = new User($con, $id);
		$profilePic = $userObj->getProfilePic();
		$link = "profile.php?id=$id";

		return "<a href='$link'>
					<img src='$profilePic' class='profilePicture'>
				</a>";
	}

	public static function createEditVideoButton($videoId){
		$href = "editVideo.php?videoId=$videoId";

		$button = ButtonProvider::createHyperlinkButton("Редактировать", null, $href, "edit button");

		return "<div class='editVideoButtonContainer'>
					$button
				</div>";
	}

	public static function createSubscriberButton($con, $userToObj, $userLoggedInObj){
		$userTo = $userToObj->getId();
		$userLoggedIn = $userLoggedInObj->getId();

		$isSubscribedTo = $userLoggedInObj->isSubscribedTo($userTo);
		$buttonText = $isSubscribedTo ? "ВЫ ПОДПИСАНЫ" : "ПОДПИСАТЬСЯ";
		$buttonText .= " " . $userToObj->getSubscriberCount();

		$buttonClass = $isSubscribedTo ? "unsubscribe button" : "subscribe button";
		$action = "subscribe(\"$userTo\", \"$userLoggedIn\", this)";

		$button = ButtonProvider::createButton($buttonText, null, $action, $buttonClass);
		return "<div class='subscribeButtonContainer'>
					$button
				</div>";
	}

	public static function createUserProfileNavigationButton($con, $id){
		if(User::isLoggedIn()){
			return ButtonProvider::createUserProfileButton($con, $id);
		}else{
			return "<a href='signIn.php'>
						<span class='signInLink'>ВОЙТИ</span>
					</a>";
		}
	}
}

?>