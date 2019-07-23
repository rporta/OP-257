<?php
$xbugConfig = new \stdClass;

//default config
$xbugConfig->fileLog = "/var/script/pixelNotification/dev1/utils/xbug/xbug.log";
$xbugConfig->mode = "a+";

/*---------------------------------------------------*/ 
function xbug($arg,$arg2 = false){
    populeLog($arg);//implementa log en 'xbug.log'
    $arg3 = str_repeat("-", 60);
    //echo "<pre style='color:#00ff00; background-color:black;' >\n";
    echo "\n";
    echo "<".$arg3."\tIn\t".$arg3.">\n";    
    if(is_array($arg) || is_object($arg)){
        if($arg2 == true){
            print_r(var_dump($arg));
        }
        else{
            print_r($arg);
        }
    }
    else{
        if($arg2 == true){
            var_dump($arg);
        }
        else{
            echo $arg."\n";
        }
    }
    echo "<".$arg3."\tOut\t".$arg3.">\n";
    echo "\n";
    //echo "</pre>";
}
/*---------------------------------------------------*/ 

function populeLog($data){
    global $xbugConfig;
    $arg3 = str_repeat("-", 60);
    $temp = "\n";
    $temp .= "<".$arg3."\tIn\t".$arg3.">\n";
    $temp .= var_export($data, true);
    $temp .= "\n";
    $temp .= "<".$arg3."\tOut\t".$arg3.">\n";
    $temp .= "\n";
    $fp = fopen($xbugConfig->fileLog, $xbugConfig->mode);
    fputs($fp, $temp);
    fclose($fp);
}