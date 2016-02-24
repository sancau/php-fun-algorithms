<?php

$array1 = array_reverse(str_split($some_array_as_string));
$array2 = array_reverse(str_split($some_array_as_string));

//Делаем 16 разрядов, чтобы в последствии оперировать с числами одинаковой разрядности.

function make16bit($array) {
	for ($i = count($array); $i < 16; $i++) {
		$array[$i] = 0; 
	}
	return $array;
}
	
$array1 = make16bit($array1);
$array2 = make16bit($array2);

//Реализуем операцию сложения в, очевидно, прямом коде.

function summing($array1, $array2) {
	$adding = array();
	for($i = 0; $i < 16; $i++) {
		switch ($array1[$i]) {
			case 0: 
				switch ($array2[$i]) {
					case 0: $result[$i] = 0 + array_pop($adding); 
							break; 
					case 1:	$result[$i] = 1 - array_pop($adding); 
							if ($result[$i] == 0) array_push($adding, 1);
							break;
				} 
				break;
			case 1: 
				switch ($array2[$i]) {
					case 0: $result[$i] = 1 - array_pop($adding); 
							if ($result[$i] == 0) array_push($adding, 1);
								break; 
					case 1:	$result[$i] = 0 + array_pop($adding); 
							array_push($adding, 1); 
							break;
				}
				break;	
			}
		}
	return $result;
}

//Реализуем операцию вычитания.

function substract ($array1, $array2) {

	//Переводим в обратный код.

	for ($i = 0; $i < 16; $i++) {
		switch ($array2[$i]) {
			case 0: $array2[$i] = 1; break;
			case 1: $array2[$i] = 0; break;
		}
	}
			
	//Переводим в дополнительный код 
	
	$bin_one[] = 1;
	
	for ($i = 1; $i < 16; $i++) {
		$bin_one[$i] = 0;
	} 

	$array2 = summing($array2, $bin_one); 	
	
	//Производим вычитание
	
	$result = summing($array1, $array2);
	
	return $result;
	
}

?>