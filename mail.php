<?php

// Обязательно!!! выдать папке files права доступа от www-data если Linux


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Автоподключение модулей
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {

	// Настройки PHP mailer
	$mail->CharSet = 'UTF-8';
	$mail->setLanguage('ru', 'phpmailer/language/');
	$mail->IsHTML(true);

	// Настройки SMTP для Google
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'babryak@gmail.com'; // Ваш адрес электронной почты Gmail
	$mail->Password = 'tapv hvwa fzjt kgkt'; // Пароль приложения
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;

	// Настройки письма
	$mail->setFrom('babryak@gmail.com', 'Alexandr'); // От кого
	$mail->addAddress('babryak@gmail.com'); // Кому
	$mail->Subject = 'Сообщение с сайта ФОТОТЕКА'; // Тема письма

	// Формируем тело письма
	$message = "<b>Сообщение от:</b> {$_POST['name']} <br>";
	$message .= "<b>Email:</b>  {$_POST['email']} <br><br>";
	$message .= "<b>Текст сообщения:</b> <br> {$_POST['message']}";

	// Тело письма
	$mail->Body = $message;

	// Отправка письма
	$mail->send();

	// В случае успеха, выводим 'SUCCESS'
	echo 'SUCCESS';
} catch (Exception $e) {
	// В случае ошибки выводим сообщение об ошибке и содержимое ошибки
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
