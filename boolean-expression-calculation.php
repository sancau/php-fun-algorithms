<?php

//Разбиваем и преобразуем входную строку 

$input = str_split($expression_as_string);

function get_bool($x) {
	switch ($_POST[$x]) {
		case 'true': $x = 1; break;
		case 'false': $x = 0; break;
	}
	return $x;
}

for ($i = 0; $i < count($input); $i++) {
	if ($input[$i] == ('y' || 'x')) $input[$i] = get_bool($input[$i]);
}

//ОБРАТНАЯ ПОЛЬСКАЯ НОТАЦИЯ 

$stack = array();
$output = array();

//Функция для определения приоритета оператора 

function getp($x) {
	switch ($x) {
		//case 'i': $p = 1; break;
		case '|': $p = 2; break;
		case '&': $p = 3; break;
		case '!': $p = 4; break;
	}
	return $p;
}

//Функция для определения того является ли элемент оператором 

function isOperator ($x) {
	if ($x == ('!' || '&' || '|')) $bool = true;
		else $bool = false;
	return $bool;
}

//Функция для отпределения вершины стека 

function top($stack) {
	$top = $stack[(count($stack)-1)];
	return $top;	
} 

//Преобразуем выражение в "Обратную польскую запись" (постфиксная нотация) 

for ($i = 0; $i < count($input); $i++) {
		
	if (is_numeric($input[$i])) {
		array_push($output, $input[$i]);
	}
	elseif ($input[$i] == '(') {
		array_push($stack, $input[$i]);
	}
	elseif ($input[$i] == ')') {
			while (($stack[(count($stack)-1)] !== '(')) array_push($output, array_pop($stack)); 
			array_pop($stack);
	}
	elseif (isOperator($input[$i])) {
				
		while ((isOperator($stack[count($stack)-1]) && (getp($input[$i]) < getp($stack[count($stack)-1])))) { 
		array_push($output, array_pop($stack));					
		}
		array_push($stack, $input[$i]);
	}
}

while (count($stack) != 0) array_push($output, array_pop($stack)); 

$string = implode(' ', $output);

$polish_string = $string;

//ВЫЧИСЛЕНИЕ РЕЗУЛЬТАТА 

$result = calc_polish($polish_string);

if ($result == false) $result = "false";
else $result = "true";

//Функция вычисления выражения в ОПН

function calc_polish($string)
{
	$stack = array();
    
	//За токен(единицу обрабатываемых данных) примем каждый отдельный символ разделенный пробелом. 
	//Разделение пробелом задано в преобразовании массива ОПН в строку.
	
	$token = strtok($string, ' ');
    
	//Пока есть необработанные токены во входной строке, обрабатываем их.
	
	while ($token !== false)
	{
		if (in_array($token, array('&', '|')))
		{
			if (count($stack) < 2)
				throw new Exception("Недостаточно данных в стеке для операции '$token'");
			$b = array_pop($stack);
			$a = array_pop($stack);
			switch ($token)
			{
				case '&': $res = $a&&$b; break;
				case '|': $res = $a||$b; break;
			}
			
			array_push($stack, $res);
			
		} 
		elseif (in_array($token, array('!'))) {
			if (count($stack) < 1)
				throw new Exception("Недостаточно данных в стеке для операции '$token'");
			$a = array_pop($stack); 
			$res = !$a; 
			array_push($stack, $res);
		}
		
		//Так как мы предварительно преобразовали значения истинности в бинарный код => можем обрабатывать их как числа
		
		elseif (is_numeric($token))
		{
			array_push($stack, $token);
		} else
		{
			throw new Exception("Недопустимый символ в выражении: $token");
		}
 
		$token = strtok(' ');
	}
	if (count($stack) > 1)
		throw new Exception("Количество операторов не соответствует количеству операндов");
		
	//После обработки всех токенов - выталкиваем значение из стека. Оно и будет результатом.
	
	return array_pop($stack);
}

?>
