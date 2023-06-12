<?php
	class VideoDetailsFormProvider{

		private $con;

		public function __construct($con){
			$this->con = $con;
		}

		public function createUploadForm(){
			$fileInput = $this->createFileInput();
			$titleInput = $this->createTitleInput(null);
			$descriptionInput = $this->createDescriptionInput(null);
			$privacyInput = $this->createPrivacyInput(null);
			$categoriesInput = $this->createCategoriesInput(null);
			$uploadButton = $this->createUploadButton();
			return "<form action='processing.php' method='POST' enctype='multipart/form-data'>
						$fileInput
						$titleInput
						$descriptionInput
						$privacyInput
						$categoriesInput
						$uploadButton
					</form>";
		}

		public function createEditDetailsForm($video){
			$titleInput = $this->createTitleInput($video->getTitle());
			$descriptionInput = $this->createDescriptionInput($video->getDescription());
			$privacyInput = $this->createPrivacyInput($video->getPrivacy());
			$categoriesInput = $this->createCategoriesInput($video->getCategory());
			$saveButton = $this->createSaveButton();
			$deleteButton = $this->createDeleteButton();
			return "<form method='POST'>
						$titleInput
						$descriptionInput
						$privacyInput
						$categoriesInput
						
						$saveButton
						$deleteButton
					</form>";
		} 

		private function createFileInput(){

			return "<div class='form-group'>
					    <label for='exampleFormControlFile1'>Ваш файл</label>
					    <input type='file' class='form-control-file' id='exampleFormControlFile1' name='fileInput' required>
					</div>";
		}

		private function createTitleInput($value){
			if($value == null) $value="";
			return "<div class='form-group'>
						<input class='form-control' type='text' placeholder='Название' name='titleInput' value='$value'>
					</div>";
		}

		private function createDescriptionInput($value){
			if($value == null) $value="";
			return "<div class='form-group'>
						<textarea class='form-control' placeholder='Описание' name='descriptionInput' rows='3'>$value</textarea>
					</div>";
		}

		private function createPrivacyInput($value){
			if($value == null) $value="";

			$privateSelected = ($value == 0) ? "selected='selected'" : "";
			$publicSelected = ($value == 1) ? "selected='selected'" : "";

			return "<div class='form-group'>
						<select class='form-control' name='privacyInput'>
						    <option value='0' $privateSelected>Частное видео</option>
						    <option value='1' $publicSelected>Публичное видео</option>
					    </select>
					</div>";
		}

		private function createCategoriesInput($value){
			if($value == null) $value="";
			$query = $this->con->prepare("SELECT * FROM categories");
			$query->execute();

			$html = "<div class='form-group'>
						<select class='form-control' name='categoryInput'>";

			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$id = $row["id"];
				$name = $row["name"];
				$selected = ($id == $value) ? "selected='selected'" : "";
				$html .= "<option value='$id' $selected>$name</option>";
			}

			$html .= "</select>
					</div>";

			return $html;
		}

		private function createUploadButton(){
			return "<button type='submit' class='btn btn-primary' name='uploadButton'>Загрузить видео</button>";
		}

		private function createSaveButton(){
			return "<button type='submit' class='btn btn-primary' name='saveButton'>Сохранить изменения</button>";
		}

		private function createDeleteButton(){
			return "<button type='submit' class='btn btn-danger' name='deleteButton' onclick=\"return confirm('Вы уверены что хотите сделать это?');\">Удалить видео</button>";
		}

	}
?>