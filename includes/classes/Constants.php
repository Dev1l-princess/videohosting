<?php
	class Constants{
		public static $firstNameCharacters = "Длина имени должна быть от 2 до 25 символов";
		public static $lastNameCharacters = "Длина фамилии должна быть от 2 до 25 символов";
		public static $usernameCharacters = "Длина логина должна быть от 5 до 25 символов";
		public static $usernameTaken = "Логин уже используется";
		public static $emailsDoNotMatch = "Адреса электронной почты не совпадают";
		public static $emailInvalid = "Пожалуйста введите коррекктный адрес электронной почты";
		public static $emailTaken = "Адрес электронной почты уже используется";
		public static $passwordsDoNotMatch = "Пароли не совпадают";
		public static $passwordNotAlphanumeric = "Пароль может содержать только латинские буквы и цифры";
		public static $passwordLength = "Длина пароля должна быть от 5 до 25 символов";

		public static $loginFailed = "Неверный логин или пароль";
		public static $passwordIncorrect = "Неверный пароль";
	}
?>