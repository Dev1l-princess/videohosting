<?php
	class FormSanitizer{
		public static function sanitizeFormString($inputText){
			$inputText = strip_tags($inputText);
			$inputText = str_replace(" ", "", $inputText);
            $inputText = mb_substr(mb_strtoupper($inputText, 'utf-8'), 0, 1, 'utf-8') . mb_substr(mb_strtolower($inputText, 'utf-8'), 1, mb_strlen($inputText)-1, 'utf-8');
			return $inputText;
		}

		public static function sanitizeFormUsername($inputText){
			$inputText = strip_tags($inputText);
			$inputText = str_replace(" ", "", $inputText);
			return $inputText;
		}

		public static function sanitizeFormPassword($inputText){
			$inputText = strip_tags($inputText);
			return $inputText;
		}

		public static function sanitizeFormEmail($inputText){
			$inputText = strip_tags($inputText);
			$inputText = str_replace(" ", "", $inputText);
			return $inputText;
		}
	}
?>