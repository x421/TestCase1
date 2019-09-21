<?php
include 'db.php';
include 'validateData.php';


$strings[0] = $_POST["name"];
$strings[1] = $_POST["soname"];
$strings[2] = $_POST["ots"];
$strings[3] = $_POST["email"];
$strings[4] = $_POST["text"];

if(validate($strings) == 0)
{
	echo "-2";
	exit(-2); // данные введены неккоректно
}
try
{
	$pdo = new PDO("mysql:dbname=users;host=127.0.0.1", $user, $password);
	$sql = "SELECT count(email) FROM Users WHERE email=:mail";
	$sth = $pdo->prepare($sql);
	$sth->execute(array(":mail" => $strings[3]));
	$output = $sth->fetchColumn();
}catch(PDOException e)
{
	echo "-3";
	exit(-3);
}

if($output > 0)
{
	echo "-1";
	exit(-1);
}
try
{
	$pdo->exec('SET CHARACTER SET utf8');
	$sql = "INSERT INTO Users(name, soname, ots, email, text) VALUES (?, ?, ?, ?, ?)";
	$sth = $pdo->prepare($sql);
	$sth->execute(array($strings[0], $strings[1], $strings[2], $strings[3], $strings[4]));
}catch(PDOException e)
{
	echo "-3";
	exit(-3);
}

// Без SMTP работать не будет
$boolMail = mail($siteAdminMail, "Форма обратной связи",
"ФИО: "+$strings[1]+" "+$strings[0]+" "+$strings[2]+"\r\n e-mail: "+$strings[3]+" \r\n Текст вопроса: "+$strings[4]+"");

if($boolMail == false)
{
	echo "-4";
	exit(-4);
}

echo "0";
exit(0);
?>