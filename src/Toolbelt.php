<?php
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

use \Symfony\Component\VarDumper\Dumper\CliDumper;
use \Symfony\Component\VarDumper\Dumper\HtmlDumper;
use \Symfony\Component\VarDumper\Cloner\VarCloner;

namespace Lsrur\Toolbelt;

class Toolbelt
{
    /**
     * determine if the array is associative
     * @param  array   $array [description]
     * @return boolean        [description]
     */
    private function is_assoc(array $array)
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }

    /**
     * Returns current microtime 
     * @return float
     */
    public function getMicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * [isJsonRequest description]
     * @param  [type]  $request [description]
     * @return boolean          [description]
     */
    public function isJsonRequest($request = null)
    {
    	$request = $request ?: request();
        if ($request->isXmlHttpRequest()) {
            return true;
        }
        $acceptable = $request->getAcceptableContentTypes();
        return (isset($acceptable[0]) && $acceptable[0] == 'application/json');
    }
    
    /**
     * Format a memory size
     * @param  [type]  $size      [description]
     * @param  integer $precision [description]
     * @return [type]             [description]
     */
   	public function formatMemSize($size, $precision = 2) {
        $units = array('Bytes','kB','MB','GB','TB','PB','EB','ZB','YB');
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }
        return round($size, $precision).$units[$i];
    }

    /**
     * Add slash at the end if doesnt exist
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
	public function checkSlash($str)
	{
	    return substr($str,-1) !== '/' ? $str .'/' : $str;
	}

	
}