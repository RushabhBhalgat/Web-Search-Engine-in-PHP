<?php
$file_handle = fopen("top-1m.csv", "r");
while(!feof($file_handle))
{
    $line = fgets($file_handle);
    $tmp = explode(',', $line);
    $rank = trim($tmp[0]);
    $url = trim($tmp[1]);
    echo 'http://'.$url."/\n";
}

fclose($file_handle);