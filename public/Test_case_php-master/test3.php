<?php
function createFile($fileName,$count){
    $file=fopen($fileName,"w");
    for ($i=0;$i<$count;$i++){
        fwrite($file,"ключ".$i."\t"."значение".$i."\x0A");
    }
}// создание файла с именем fileName, содержащего count значений ассоциативного лексикографически упорядоченного массива с уникальными ключами от 0 до count и значениями от 0123456789 до count123456789
function getTime($time = false)
{
    return $time === false? microtime(true) : round(microtime(true) - $time, 3);
}//функция установки и получения времени
function binarySearch($fileName, $desiredValue)
{
    $file=new SplFileObject($fileName); //объект файла
    $handle = fopen($fileName, "r");

    //Получаем первую и последнюю строку
    //От последней вычитаем первую(вдруг ключ будет иметь вид -> 1234ключ1
    //последний(1234ключ999) тогда и первый будет 1234ключ0 (1234999-12340 и получится
    //число с количеством строк нашего файла

    $startA = (int)getFirstLineNumberOfKey($handle);
    $start = 0;
    $end = (int)getLastLineNumberOfKey($handle) - $startA;

   //echo $left."\n";
    //echo $right."\n";
    //echo $desiredValue."\n";

    //Ну а после стандартный бинарный поиск(можно было бы и рекурсивный, он быстрее, но раз начал с циклического)
    while ($start <= $end) { //условие выхода за границы
        $position = floor(($start + $end) / 2);  //вычисление середины массива
        $file->seek($position);//взятие строки с вычесленным номером
        //echo $position."\n";
        $elem = explode("\t", $file->current());// разбиение строки на пару ключ:значение
        $strnatcmp = strnatcmp($elem[0],$desiredValue);
        //echo $elem[0];
        //echo $elem[1];
        if ($strnatcmp>0){
            $end = $position-1;
        }elseif($strnatcmp<0){
            $start = $position+1;
        }else{
            return $elem[1];
        }
    }
    return 'undef'; // не найденно значение
}
define("FILE_NAME","test.txt"); // имя файла
//define ("VAL","ключ500000000"); // значение для поиска методом цикл (будет undef)
define ("VAL","ключ4"); //а тут найдет
//createFile(FILE_NAME,500000);
$time=getTime();
$result=binarySearch(FILE_NAME,VAL);
$time=getTime($time);
echo "Значение ключа - ".$result;
    //."\n"."Времени затрачено - ".$time." секунд  ";

//Получаем последнюю строку (ключN), а точнее номер ключа
define("LINES_COUNT", 10);
function getLastLineNumberOfKey($handle){
    $pos = -2;
    $t = " ";
    while ($t != "\n") {
        if(fseek($handle, $pos, SEEK_END) == -1) {
            break;
        }
        $t = fgets($handle);
        $pos --;
    }
    $t = fgets($handle);
    $elem = explode("\t", $t);
    $str2 = "";
    for($n = 0; $n < strlen($elem[0]); $n++){
        if(is_numeric($elem[0][$n])) $str2 = $str2.$elem[0][$n];
    }
    return $str2;
}

//Получаем первую строку (ключ0), а точнее номер ключа
function getFirstLineNumberOfKey($handle){
    $theData = fgets($handle);
    $elem = explode("\t", $theData);
    $str2 = "";
    for($n = 0; $n < strlen($elem[0]); $n++){
        if(is_numeric($elem[0][$n])) $str2 = $str2.$elem[0][$n];
    }
    return $str2;
}
