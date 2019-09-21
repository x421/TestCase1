<?php
function validate($strings)
{
	$a = 0;
	$a += preg_match("/^[А-Яа-яё]+$/msiu", $strings[0]);
	$a += preg_match("/^[А-Яа-яё]+$/msiu", $strings[1]);
	$a += preg_match("/^[А-Яа-яё]+$/msiu", $strings[2]);
	$a += preg_match("#^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$#", $strings[3]);
	
	if($a == 4)
		return true;
	else
		return false;
}

?>