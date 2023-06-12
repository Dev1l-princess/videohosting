<?php
	class SettingsFormProvider{

		public function createUserDetailsForm($username, $firstName, $lastName, $email){
            $usernameInput = $this->createUsernameInput($username);
			$firstNameInput = $this->createFirstNameInput($firstName);
			$lastNameInput = $this->createLastNameInput($lastName);
			$emailInput = $this->createEmailInput($email);
			$saveButton = $this->createSaveUserDetailsButton();

			return "<form action='settings.php' method='POST' enctype='multipart/form-data'>
						<span class='title'>Информация о пользователе</span>
						$usernameInput
						$firstNameInput
						$lastNameInput
						$emailInput
						$saveButton
					</form>";
		}

		public function createPasswordForm(){
			$oldPasswordInput = $this->createPasswordInput("oldPassword", "Старый пароль");
			$newPasswordInput = $this->createPasswordInput("newPassword", "Новый пароль");
			$newPasswordInput2 = $this->createPasswordInput("newPassword2", "Подтвердите новый пароль");

			$saveButton = $this->createSavePasswordButton();

			return "<form action='settings.php' method='POST' enctype='multipart/form-data'>
						<span class='title'>Сменить пароль</span>
						$oldPasswordInput
						$newPasswordInput
						$newPasswordInput2
						$saveButton
					</form>";
		}

        private function createUsernameInput($value){
            if($value == null) $value = "";
            return "<div class='form-group'>
						<input class='form-control' type='text' placeholder='Логин' name='username' value='$value' required>
					</div>";
        }
		private function createFirstNameInput($value){
			if($value == null) $value = "";
			return "<div class='form-group'>
						<input class='form-control' type='text' placeholder='Имя' name='firstName' value='$value' required>
					</div>";
		}

		private function createLastNameInput($value){
			if($value == null) $value = "";
			return "<div class='form-group'>
						<input class='form-control' type='text' placeholder='Фамилия' name='lastName' value='$value' required>
					</div>";
		}

		private function createEmailInput($value){
			if($value == null) $value = "";
			return "<div class='form-group'>
						<input class='form-control' type='email' placeholder='Email' name='email' value='$value' required>
					</div>";
		}

		private function createSaveUserDetailsButton(){
			return "<button type='submit' class='btn btn-primary' name='saveDetailButton'>Сохранить</button>";
		}

		private function createSavePasswordButton(){
			return "<button type='submit' class='btn btn-primary' name='savePasswordButton'>Сохранить</button>";
		}

		private function createPasswordInput($name, $placeholder){
			return "<div class='form-group'>
						<input class='form-control' type='password' placeholder='$placeholder' name='$name' required>
					</div>";
		}

	}
?>