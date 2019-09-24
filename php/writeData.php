<?php
include 'settings.php';
include 'validateData.php';
include './PHPMailer/SMTP.php';
include './PHPMailer/Exception.php';
include './PHPMailer/PHPMailer.php';


$strings[0] = $_POST["name"];
$strings[1] = $_POST["soname"];
$strings[2] = $_POST["ots"];
$strings[3] = $_POST["email"];
$strings[4] = $_POST["text"];

$valResult = validate($strings);

if($valResult != 0)
{
	echo $valResult;
	exit($valResult); // неверно что то
}

$mysqli = new mysqli('localhost', $user, $password, 'users');

if (mysqli_connect_errno()) {
    echo "-3";
    exit(-3);
}

$mysqli->set_charset("utf8");
$mysqli->query("SET NAMES `utf8`");

// Проверка email
$sql = "SELECT count(email) FROM Users WHERE email=?";
$stm = $mysqli->prepare($sql);
$stm->bind_param("s", $strings[3]);
$stm->execute();
$stm->bind_result($count);
$stm->fetch();
$stm->close();

if($count > 0)
{
	echo "-1"; // такой mail уже есть
	exit(-1);
}

// Запись в базу
$sql = "INSERT INTO Users(name, soname, ots, email, text) VALUES (?, ?, ?, ?, ?)";
$stm = $mysqli->prepare($sql);
$stm->bind_param("sssss", $strings[0], $strings[1], $strings[2], $strings[3], $strings[4]);
$stm->execute();
$stm->close();

// Отправка почты
$mail = new PHPMailer\PHPMailer\PHPMailer;

$mail->isSMTP();
$mail->CharSet = 'UTF-8';
$mail->Host = $server;
$mail->SMTPAuth = $auth;
$mail->Username = $smtpUsername;
$mail->Password = $smtpPass;
$mail->Port = $smtpPort;
$mail->SMTPSecure = $secure;

$mail->From = $mailFrom;
$mail->addAddress($siteAdminMail);

$mail->isHTML(true);

$mail->Subject = 'Форма обратной связи';
$mail->Body    = "ФИО: ".strval($strings[1])." ".strval($strings[0])." ".strval($strings[2])."<br> e-mail: ".strval($strings[3])." <br> Текст вопроса: ".strval($strings[4])."";
$mail->AltBody = "ФИО: ".strval($strings[1])." ".strval($strings[0])." ".strval($strings[2])." e-mail: ".strval($strings[3])."  Текст вопроса: ".strval($strings[4])."";

if($mail->send() == false)
{
	echo "-4";
	exit(-4);
}

echo "0";
exit(0);
?>