<?php
// в этом классе описываются методы для поиска и обработки строк
class SuperFilter {

    // Читать файл через генератор
    public function readTheFile($file){
        $handle = fopen($file, "r");
        
        while (!feof($handle)) {
            yield fgetcsv($handle);
        }
        fclose($handle);
        
    }
    
    
    //__________________________________________________________________________
    // 			 Берем заголовки
    // - Создаем не асоциативный массив 
    // - Элементы которого названия столбцов
    // это нужно чтобы создать асоциативный массив при разборе строки с товаром
    public function parceCSV($pathBest){        
        if (($handle = fopen($pathBest, 'r')) !== FALSE){
            $arr = array();
            if (($data = fgetcsv($handle, 1000, ',')) !== FALSE){
                foreach($data as $item){
                    $arr[] = $item;
                }
            }
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE){
            	//echo '<tr><td>'.implode('</td><td>', $data).'</td></tr>';
            	echo '<pre>';
            	foreach($data as $item){
            		var_dump($item);
            	}
            	echo '</pre>';
            }
            fclose($handle);
            return $arr;
        }else{
            return FALSE;
        }
        
    }
    
    // чистка строки из массива
    public function ArrStrSlimm($str){
        try {
            $str		= 	mb_strtolower(implode($str),'UTF-8')	    ; // в нижний регистр
            $search		= 	array(' ', '*', '/', '\\', '.', '-')	    ; // что искать
            $replace	=	''										    ; // на что заменить
            $str        =   str_replace($search, $replace, $str)		; // убрали лишние символы	
        } catch ( Exception $e ) {
            echo 'Не получилось разьебать строку';
        }
        return $str;
    }

    // чистка строки
    public function strSlimm($str){
        try {
            $str		= 	mb_strtolower($str,'UTF-8')	                ; // в нижний регистр
            $search		= 	array(' ', '*', '/', '\\', '-', '.')	    ; // что искать
            $replace	=	''										    ; // на что заменить
            $str        =   str_replace($search, $replace, $str)		; // убрали лишние символы	
        } catch ( Exception $e ) {
            echo 'Не получилось разьебать строку';
        }
        return $str;
    }

    //невьебенный поиск нужной строки
    public function search($str, $brand , $model, $widthAndHeigh, $radius){
        $result = '';
        if(stristr($str, $brand)) {
            if(stristr($str, $model)){
                if(stristr($str, $radius)){
                    if(stristr($str, $widthAndHeigh)){
                        $result =   true;
                    }else{
                        $result =   false;
                    }
                }
            }
        }
        return $result;
    }

}


class CsvIterator {
 
    protected $file;
 
    public function __construct($file) {
        $this->file = fopen($file, 'r');
    }
 
    public function parse() {
        $headers = array_map('trim', fgetcsv($this->file, 4096));
        while (!feof($this->file)) {
            $row = array_map('trim', (array)fgetcsv($this->file, 4096));
            if (count($headers) !== count($row)) {
                continue;
            }
            $row = array_combine($headers, $row);
            yield $row;
        }
        return;
    }
}
