<?php
	require_once("includes/config.php");
	require_once("includes/classes/FormSanitizer.php");
	require_once("includes/classes/Account.php");
	require_once("includes/classes/Constants.php");

	$account = new Account($con);
	
	if(isset($_POST["submitButton"])){
		
		$username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
		$password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

		$wasSuccessful = $account->login($username, $password);

		if($wasSuccessful){

			$_SESSION["userLoggedIn"] = $wasSuccessful;
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
				<h3>Вход</h3>
				<span>для продолжения</span>
			</div>
			<div class="loginForm">
				<form action="signIn.php" method="POST">
					<?php echo $account->getError(Constants::$loginFailed);?>
					<input type="text" name="username" value="<?php getInputValue('username');?>" placeholder="Логин" required autocomplete="off">
					<input type="password" name="password" placeholder="Пароль" required>
					<input type="submit" name="submitButton" value="ВОЙТИ">
				</form>
			</div>
			<a class="signInMessage" href="signUp.php">Нет аккаунта? Регистрация!</a>
		</div>
	</div>
</body>
</html>