<?php
if (!function_exists('aurl')){
    function aurl($url =null )
    {
   return url('admin/'.$url);
    }
}
