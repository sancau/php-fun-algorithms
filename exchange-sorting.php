<?php

//РЕАЛИЗАЦИЯ АЛГОРИТМА ОБМЕННОЙ СОРТИРОВКИ

// Приём и обработка входных данных, проверка данных на корректность.

if (isset($_POST[type])) {
	
	// Создание булевского параметра, определяющего тип сортировки (по возрастанию - по убыванию)
	
	switch ($_POST[type]) {
		case "true": $type = true; break;
		case "false": $type = false;
	}
	
	/* Предварительная отчистка полученных данных и проверка на корректность
	с использованием регулярных выражений. */
	
	$data_string = trim($_POST[data]); // отчистка от лишних пробельных символов
	$reg = "/\d\s/";
	$reg2 = "/[a-zA-Zа-яА-Я,.]/";
	if ((preg_match($reg, $data_string) === 1) && (preg_match($reg2, $data_string) === 0))  {
		
		// Создание массива для сортировки, разбиение на числовые элементы строки введенной пользователем
		
		$data_string = $data_string." ";
		$i = 0;
		while ($i < (strlen($data_string)-1)) {	
			$marker = strpos($data_string, " ", $i);
			$data_array[] = substr($data_string, $i, ($marker - $i));
			$i = $marker + 1;
		}
		
		// Реализация функционала алгоритма обменной сортировки с возможностью выбора её типа:
		
		function array_sorting($array, $bool) {
			for ($i = count($array); $i > 0; $i--) {
				for ($k = 1; $k < count($array); $k++) {
					switch ($bool) {
						case true: {
							if ($array[$k] < $array[$k-1]) {
								$temp = $array[$k-1];
								$array[$k-1] = $array[$k];
								$array[$k] = $temp;
							}
							break;
						}
						case false: {
							if ($array[$k] > $array[$k-1]) {
								$temp = $array[$k-1];
								$array[$k-1] = $array[$k];
								$array[$k] = $temp;
							}
							break;
						}			
					}
				}
			}
			return $array;
		}
		
		// Вычисления с пользовательскими данными
		
		$result = array_sorting($data_array, $type);
	}
		
	else $result[] = "Некорректные данные! Введите числовые значения элементов массива, разделяя их пробелами!";
}

else $result[] = "Введите исходные данные и укажите тип сортировки!";

?>