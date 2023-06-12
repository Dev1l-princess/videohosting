<?php
	require_once("includes/config.php");
	require_once("includes/classes/FormSanitizer.php");
	require_once("includes/classes/Account.php");
	require_once("includes/classes/Constants.php");
	
	$account = new Account($con);

	if(isset($_POST["submitButton"])){
		$firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
		$lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);

		$username = FormSanitizer::sanitizeFormUsername($_POST["username"]);

		$email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
		$email2 = FormSanitizer::sanitizeFormEmail($_POST["email2"]);

		$password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
		$password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);

		$wasSuccessful = $account->register($firstName, $lastName, $username, $email, $email2, $password, $password2);

		if($wasSuccessful){
			$_SESSION["userLoggedIn"] = $username;
			header("Location: index.php");
		}
	}

	function getInputValue($value){
		if(isset($_POST[$value])){
			echo $_POST[$value];
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Videohosting</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css"> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>
<body>
	<div class="signInContainer">
		<div class="column">
			<div class="header">
                <a href="index.php"><img src="assets/images/icons/logo.png" title="logo" alt="Site logo"></a>
				<h3>Регистрация</h3>
				<span>для продолжения</span>
			</div>
			<div class="loginForm">
				<form action="signUp.php" method="POST">

					<?php echo $account->getError(Constants::$firstNameCharacters);?>
					<input type="text" name="firstName" value="<?php getInputValue('firstName');?>" placeholder="Имя" autocomplete="off" required>
					<?php echo $account->getError(Constants::$lastNameCharacters);?>
					<input type="text" name="lastName" value="<?php getInputValue('lastName');?>" placeholder="Фамилия" autocomplete="off" required>
					
					<?php echo $account->getError(Constants::$usernameCharacters);?>
					<?php echo $account->getError(Constants::$usernameTaken);?>
					<input type="text" name="username" value="<?php getInputValue('username');?>" placeholder="Логин" autocomplete="off" required>

					<?php echo $account->getError(Constants::$emailsDoNotMatch);?>
					<?php echo $account->getError(Constants::$emailInvalid);?>
					<?php echo $account->getError(Constants::$emailTaken);?>
					<input type="email" name="email" value="<?php getInputValue('email');?>" placeholder="Email" autocomplete="off" required>
					<input type="email" name="email2" value="<?php getInputValue('email2');?>" placeholder="Подтверждение email" autocomplete="off" required>

					<?php echo $account->getError(Constants::$passwordsDoNotMatch);?>
					<?php echo $account->getError(Constants::$passwordNotAlphanumeric);?>
					<?php echo $account->getError(Constants::$passwordLength);?>
					<input type="password" name="password" placeholder="Пароль" autocomplete="off" required>
					<input type="password" name="password2" placeholder="Подтверждение пароля" autocomplete="off" required>

					<input type="submit" name="submitButton" value="ЗАРЕГИСТРИРОВАТЬСЯ">
				</form>
			</div>
			<a class="signInMessage" href="signIn.php">Уже есть аккаунт? Вход!</a>
		</div>
	</div>
</body>
</html>