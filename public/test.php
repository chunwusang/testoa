<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/5
 * Time: 17:22
 */
$str ='您好评';
var_dump(base64encode($str));

 function base64encode($str)
{
    if(isempt($str))return '';
    $str	= base64_encode($str);
    $str	= str_replace(array('+', '/', '='), array('!', '.', ':'), $str);
    return $str;
}

 function base64decode($str)
{
    if(isempt($str))return '';
    $str	= str_replace(array('!', '.', ':'), array('+', '/', '='), $str);
    $str	= base64_decode($str);
    return $str;
}

function isempt($str)
{
    $bool=false;
    if( ($str==''||$str==NULL||empty($str)) && (!is_numeric($str)) )$bool=true;
    return $bool;
}