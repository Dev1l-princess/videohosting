<?php
require_once("includes/header.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/SettingsFormProvider.php");

if(!User::isLoggedIn()){
	header("Location: signIn.php");
}

$detailsMessage = "";
$passwordMessage = "";
$formProvider = new SettingsFormProvider();

if(isset($_POST["saveDetailButton"])){
	$account = new Account($con);
    $username = FormSanitizer::sanitizeFormString($_POST["username"]);
	$firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
	$lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
	$email = FormSanitizer::sanitizeFormString($_POST["email"]);

	if($account->updateDetails($username, $firstName, $lastName, $email, $userLoggedInObj->getId())){
		$detailsMessage = "<div class='alert alert-success'>
							<strong>Успешно!</strong> Подробности изменены!
						   </div>";
	}else{
		$errorMessage = $account->getFirstError();

		if($errorMessage == ""){
			$errorMessage = "Что-то пошло не так";
		}

		$detailsMessage = "<div class='alert alert-danger'>
							<strong>Ошибка!</strong> $errorMessage !
						   </div>";
	}
}

if(isset($_POST["savePasswordButton"])){
	$account = new Account($con);
	$oldPassword = FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
	$newPassword = FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
	$newPassword2 = FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);


	if($account->updatePassword($oldPassword, $newPassword, $newPassword2, $userLoggedInObj->getId())){
		$passwordMessage = "<div class='alert alert-success'>
							<strong>Успешно!</strong> Пароль изменен!
						   </div>";
	}else{
		$errorMessage = $account->getFirstError();

		if($errorMessage == ""){
			$errorMessage = "Что-то пошло не так";
		}

		$passwordMessage = "<div class='alert alert-danger'>
							<strong>Ошибка!</strong> $errorMessage !
						   </div>";
	}
}

?>

<div class='settingsContainer column'>
	<div class='formSection'>
		<div class="message">
			<?php echo $detailsMessage;?>
		</div>
		<?php
			echo $formProvider->createUserDetailsForm(
                isset($_POST["username"]) ? $_POST["username"] : $userLoggedInObj->getUsername(),
				isset($_POST["firstName"]) ? $_POST["firstName"] : $userLoggedInObj->getFirstName(),
				isset($_POST["lastName"]) ? $_POST["lastName"] : $userLoggedInObj->getLastName(),
				isset($_POST["email"]) ? $_POST["email"] : $userLoggedInObj->getEmail()
			);
		?>
	</div>

	<div class='formSection'>
		<div class="message">
			<?php echo $passwordMessage;?>
		</div>
		<?php
			echo $formProvider->createPasswordForm();
		?>
	</div>
</div>