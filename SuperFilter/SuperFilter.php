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
    public function parse($file) {
        $file = fopen($file, 'r');
        $headers = array_map('trim', fgetcsv($file, 4096));
        while (!feof($file)) {
            $row = array_map('trim', (array)fgetcsv($file, 4096));
            if (count($headers) !== count($row)) {
                continue;
            }
            $row = array_combine($headers, $row);
            yield $row;
        }
        return;
        fclose($file); 
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
        unset($str);
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
        unset($str);
    }

    //невьебенный поиск нужной строки
    public function search($str, $brand , $model, $size, $radius, $index){
        $result = false;
        // $str = строка в которой искать
        if(stristr($str, $brand)) {
            if(stristr($str, $model)){
                if(stristr($str, $radius)){
                    if(stristr($str, $size)){
                        if(stristr($str, $index)){
                            $result = true;
                        }
                    }
                }
            }
        }else{
            $result = false;
        }
        unset($str);
        return $result;
    }

   

}

