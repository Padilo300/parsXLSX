<?php

ini_set('error_reporting', E_ALL)							;
ini_set('display_errors', 1)								; 
ini_set('display_startup_errors', 1)						;
ini_set('memory_limit', '2048M')							;
ini_set('max_execution_time', 3000)							; //300 seconds = 5 minutes

require_once __DIR__ . '/simpleXLSX/simplexlsx.class.php'	; // подключаем класс для парсинга xlsx
require_once __DIR__ . '/SuperFilter/SuperFilter.php'		; // подключаем класс для поиска в прайсах

$Filter 		= new SuperFilter()							; // класс для поиска в прайсах  
$price 			= SimpleXLSX::parse('./price/2.xlsx')		; // прайс поставщика
$best			= __DIR__ . '/price/medium.csv'				; // путь к файлу идеального прайса
$resultFile		= __DIR__ . '/result/result.html'			; // путь к результирующему файлу



if ($best && $price ) {
	$i = 0;
	$a = 0;
	$b = 0;

	echo '<table>'													;
	echo '<tr>'														;
	echo '<td>#</td><td> Идеальный прайс </td><td> Совпадение </td>';
	echo '</tr>'													;
	
	foreach( $Filter->parse($best) as $row){
		// echo '<pre>';
		// echo $a . '<br>';
		// echo '</pre>';
		$i 					= ($i+1)						; //	счетчик
		$FullName			= $row['name']					; //	=> полное название с размерами
		$quantity 			= $row['quantity']				; //	=> кол-во
		$brand 				= $row['shinumanufacturer']   	; //    => Бренд
		$model				= $row['model']					; //	=> model
		$width 			    = $row['widths']				; //	=> ширина (255)
		$height 			= $row['heights']				; //	=> высота (55)
		$radius 			= $row['radius']				; //	=>  радиус (R16) 
		$speed_code			= $row['speed_code']  			; //	=> индекс скорости (буква)
		$load_index			= $row['load_index']  			; //	=> индекс нагрузки (буквы) 
    	$country			= $row['country']  				; // 	=> country
		$year				= $row['year']  				; // 	=> year

		$brand 				= $Filter->strSlimm($brand)		; // чистим строку
		$model 				= $Filter->strSlimm($model)		; // чистим строку
		$radius				= $Filter->strSlimm($radius)	; // чистим строку
		$speed_code			= $Filter->strSlimm($speed_code); // чистим строку
		$load_index			= $Filter->strSlimm($load_index); // чистим строку

		$size				= $width . $height				; // обьединяем строку в формат 25555	
		$index 				= $load_index . $speed_code		; // обьединяем индекс нагрузки и скорости

		foreach($price->rows(1) as $str){
			$bestSTR	= $str																; // делаем копию строку
			$str 		= $Filter->ArrStrSlimm($str)							    		; // убираем пробелы и лишние символы
			$result 	= $Filter->search($str, $brand, $model, $size, $radius, $index)		; // ищем совпадение
			if($result){
				$b = ($b+1);
				// echo '<tr>'	;
				// echo '<td>' . $b . '</td><td>' . $FullName . '</td><td>' . $str . '</td>';
				// echo '</tr>';
				$string = '<tr><td>' . $b . '</td><td>' . $FullName . '</td><td>' . $bestSTR . '</td></tr>' . PHP_EOL;
				$handle = fopen($resultFile,"a");
				file_put_contents($resultFile, $string, FILE_APPEND | LOCK_EX);
				fclose($handle);
				break	;
			}
		}
	} 
	foreach($price->rows(1) as $str){
		$a = ($a+1);
	}
	echo "готово!";
	memory_get_peak_usage();

	function formatBytes($bytes, $precision = 2) {
		$units = array("b", "kb", "mb", "gb", "tb");

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		$bytes /= (1 << (10 * $pow));

		return round($bytes, $precision) . " " . $units[$pow];
	}
	

	echo '<tr>'	;
	echo '<td> Строк в супер прайсе '.$i.'; <br> Cтрок в прайсе поставщика: '.$a.'; <br> Строк совпало: <b>'.$b.'</b><br> </td><td></td>';
	echo '</tr>';
	echo '</table>';
} else {
	echo SimpleXLSX::parse_error();
}

unset($best)	;
unset($price)	;
unset($Filter)	;

$memory = formatBytes(memory_get_peak_usage());
echo 'Памяти затрачено :'. $memory;