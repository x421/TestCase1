<?php
function validate($strings)
{
	$a = 0;
	
	if(preg_match("/^[А-Яа-яё]+$/msiu", $strings[0]) == 0)
		$a+=1;
	if(preg_match("/^[А-Яа-яё]+$/msiu", $strings[1]) == 0)
		$a+=2;
	if(preg_match("/^[А-Яа-яё]+$/msiu", $strings[2]) == 0)
		$a+=4;
	if(preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $strings[3]) == 0)
		$a+=8;
	
	// Вернет 0 если все верно
	return $a;
}

?>