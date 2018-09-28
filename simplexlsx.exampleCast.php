<?php
ini_set('error_reporting', E_ALL)							;
ini_set('display_errors', 1)								; 
ini_set('display_startup_errors', 1)						;
ini_set('memory_limit', '2048M')							;
ini_set('max_execution_time', 300)							; //300 seconds = 5 minutes

require_once __DIR__ . '/simpleXLSX/simplexlsx.class.php'	;
require_once __DIR__ . '/SuperFilter/SuperFilter.php'		;
$price 	= SimpleXLSX::parse('./price/4.xlsx')			    ; // прайс поставщика
//$best 	= SimpleXLSX::parse('./price/small.xlsx')			; // прайст образец

$Filter 	= new SuperFilter()					; 
 
$pathBest	= __DIR__ . '/price/4.csv'			; // путь к файлу
//$pathPrice	= __DIR__ . '/price/4.xlsx'			; // путь к файлу

//$best		= $Filter->readTheFile($pathBest)		; // включаем генератор нахуй
//$price	= $Filter->readTheFile($pathPrice)		; // включаем генератор нахуй
echo "<pre>";
//foreach($Filter->readTheFile($pathBest) as $i){
// var_dump($i);
//}
foreach ($Filter->readTheFile($pathBest) as $row) {
	var_dump($row);
}
echo "</pre>";
if (/* $best &&*/ $price ) {
	$i = 0;
	$a = 0;
	$b = 0;

	echo '<table>';
	echo '<tr>'	;
	echo '<td> Идеальный прайс </td><td> Совпадение </td>';
	echo '</tr>';
	
	// foreach( $best as $row){
	// 	$i = ($i+1);
	// 	str_getcsv($row);
	// 	echo '<pre>'  ;
	// 	var_dump($row) ;
	// 	echo '</pre>' ;
	// 	$FullName			= $row[1]	; //	=> полное название с размерами
	// 	$quantity 			= $row[4]	; //	=> кол-во
	// 	$brand 				= $row[6]   ; //    => Бренд
	// 	$model				= $row[5]	; //	=> model
	// 	$width 			    = $row[7]	; //	=> ширина (255)
	// 	$height 			= $row[8]	; //	=> высота (55)
	// 	$radius 			= $row[9]	; //	=>  радиус (R16) 
	// 	$speed_code			= $row[11]  ; //	=> индекс скорости (буква)
	// 	$load_index			= $row[12]  ; //	=> индекс нагрузки (буквы) 
    // 	$country			= $row[56]  ; // 	=> country
	// 	$year				= $row[57]  ; // 	=> year

	// 	$brand 				= $Filter->strSlimm($brand)	; // чистим строку
	// 	$model 				= $Filter->strSlimm($model)	; // чистим строку
	// 	$radius				= $Filter->strSlimm($radius); // чистим строку
	// 	$widthAndHeight		= $width . $height			; // обьединяем строку в формат 25555	

	// 	foreach($price as $str){
	// 		$bestSTR	= $str																; //делаем копию строку
	// 		$str 		= $Filter->ArrStrSlimm($str)							    		; // убираем пробелы и лишние символы
	// 		$result 	= $Filter->search($str, $brand, $model, $widthAndHeight, $radius)	; // ищем совпадение
	// 		if($result){
	// 			echo '<tr>'	;
	// 			echo '<td>' . $brand . ' ' .  $model .' ' . $radius .' ' . $width .' ' . $height  . '</td><td>' . implode($bestSTR) . '</td>';
	// 			echo '</tr>';
	// 			$b = ($b+1);
	// 		}
	// 		$a = ($a+1);
	// 	}
	
	// } 
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