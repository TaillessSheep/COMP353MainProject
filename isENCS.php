<?php
$hostname = gethostname();
//$hostname = 'poise.encs.concordia.ca';

$pos = strpos($hostname, '.');
$hostname = substr($hostname,$pos);
$isENCS = false;
if($hostname=='.encs.concordia.ca'){
    $isENCS = true;
}else{
    $isENCS = false;
}

