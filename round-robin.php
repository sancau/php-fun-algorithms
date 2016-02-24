<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Моделирование Round Robin</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf" />
	<link rel="stylesheet" type="text/css" href="OS_styles/styles.css" />
</head>
<body>
	<h3>Моделирование Round Robin</h3>

<?php
//квант
$quant = $_POST[quant];

//количество процессов
$count = count($_POST)-2;

//создаем массив с информацией о процессах
for ($i = 1; $i <= $count; $i++) {
	$proc_array[$i] = $_POST[$i];	
}

//необходимое проц время всего: 
$total_cpu = 0;
for($i = 1; $i <= count($proc_array); $i++) {
	$total_cpu = $total_cpu + $proc_array[$i];
}

//вспомогательная переменная togo
$togo = $total_cpu;

//вспомогательная переменная timestamp, момент времени, начинается с одного
$timestamp = 1;

//массивы для учета времени выполнения / простоя процессов
$inprog = array();
$ready = array();

for ($i = 1; $i <= $count; $i++) {
	$inprog[$i] = 0;
	$ready[$i] = 0;
}

//моделируем
$temp_array = $proc_array;
while (($togo !== 0)) {
	for ($i = 1; $i <= count($temp_array); $i++) {
		if ($temp_array[$i] > 0) {
			echo "<br /><hr /> Calculating process #".$i.":<br />";
			switch ($temp_array[$i]) { 
				case ($temp_array[$i] >= $quant): 
					for ($k = $timestamp; $k < ($quant+$timestamp); $k++) {
						echo "<br /> Timestamp: ".$k."<br />";
						$table_array[$i][$k] = "Exec"; 
						$inprog[$i] = $inprog[$i] + 1;
						for ($j = 1; $j <= $count; $j++) {
							if ($j !== $i){
								$ready[$j] = $ready[$j]+1;
								if ($temp_array[$j] !== 0) {
									$table_array[$j][$k] = "Ready";
								}
								else
									$table_array[$j][$k] = "Done";
							}	
						}
					}
					$timestamp = $k;
					$temp_array[$i] = $temp_array[$i] - $quant;
					$togo = $togo - $quant;
					
					echo "<br /> Process # ".$i." CPU time passed: ".$inprog[$i]."<br /><br />";
					echo "<br /> Process # ".$i." On-hold time passed: ".$ready[$i]."<br /><br />";
					echo "<br /> Total CPU time to go: ".$togo."<br /><br />";
					break;
				default: 
					for ($k = $timestamp; $k < ($temp_array[$i]+$timestamp); $k++) {
						echo "<br /> Timestamp: ".$k."<br />";
						$table_array[$i][$k] = "Exec"; 
						$inprog[$i] = $inprog[$i] + 1;
						for ($j = 1; $j <= $count; $j++) {
							if ($j !== $i){
								$ready[$j] = $ready[$j]+1;
								if ($temp_array[$j] !== 0) {
									$table_array[$j][$k] = "Ready";
								}
								else
									$table_array[$j][$k] = "Done";
							}	
						}
					}
					$timestamp = $k;
					$togo = $togo - $temp_array[$i];
					
					echo "<br /> Process # ".$i." CPU time passed: ".$inprog[$i]."<br /><br />";
					echo "<br /> Process # ".$i." On-hold time passed: ".$ready[$i]."<br /><br />";
					echo "<br /> Total CPU time to go: ".$togo."<br /><br />";
					$temp_array[$i] = 0;
					break;
			}
		}
	}
}
echo "<hr /><b>DONE!</b><br /><br />";

$total_ready = array_sum($ready);
$total_inprog = array_sum($inprog);

$av_ready = $total_ready / $count;
$av_exe = $total_inprog / $count;

echo "<b>Среднее время ожидания: ".$av_ready."<br /><br />";
echo "Среднее время исполнения процесса: ".$av_exe."<br /><br /></b>";

//визуализируем
echo "<h3>Визуальное представление модели диспечеризации Round Robin</h3>";
echo "<table id='rest'>";
echo "<tr><td><i>Timeline =></i></td>";
for ($k = 1; $k <= count($table_array[1]); $k++) {
	echo "<td><b>".$k."</b></td>";
}
echo "</tr>";
for ($i = 1; $i <= count($table_array); $i++) {
	echo "<tr><td><b>Process # ".$i."</b></td>";
	for ($k = 1; $k <= count($table_array[$i]); $k++) {
		switch ($table_array[$i][$k]) {
			case "Exec": echo "<td bgcolor='green'>".$table_array[$i][$k]."</td>"; break;
			case "Ready": echo "<td bgcolor='red'>".$table_array[$i][$k]."</td>"; break;
			default: echo "<td bgcolor='grey'>".$table_array[$i][$k]."</td>"; break;
		}
	}
	echo "</tr>";
}
echo "</table>";
?>
</body>
</html>