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


    public function parse_phpinfo() {
        ob_start(); phpinfo(); $s = ob_get_contents(); ob_end_clean();
        $s = strip_tags($s, '<h2><th><td>');
        $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', '<info>\1</info>', $s);
        $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', '<info>\1</info>', $s);
        $t = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
        $r = array(); $count = count($t);
        $p1 = '<info>([^<]+)<\/info>';
        $p2 = '/'.$p1.'\s*'.$p1.'\s*'.$p1.'/';
        $p3 = '/'.$p1.'\s*'.$p1.'/';
        for ($i = 1; $i < $count; $i++) {
            if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $t[$i], $matchs)) {
                $name = trim($matchs[1]);
                $vals = explode("\n", $t[$i + 1]);
                foreach ($vals AS $val) {
                    if (preg_match($p2, $val, $matchs)) { // 3cols
                        $r[$name][trim($matchs[1])] = array(trim($matchs[2]), trim($matchs[3]));
                    } elseif (preg_match($p3, $val, $matchs)) { // 2cols
                        $r[$name][trim($matchs[1])] = trim($matchs[2]);
                    }
                }
            }
        }
        return $r;
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

    public function isJsonRequest($request = null)
    {
    	$request = $request ?: request();
        if ($request->isXmlHttpRequest()) {
            return true;
        }
        $acceptable = $request->getAcceptableContentTypes();
        return (isset($acceptable[0]) && $acceptable[0] == 'application/json');
    }

    public function getDump($v)
    {
        $styles = [
            'default' => 'background-color:white; color:#222; line-height:1.2em; font-weight:normal; font:13px Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:100000; border:0',
            'num' => 'color:#a71d5d',
            'const' => 'color:#795da3',
            'str' => 'color:#df5000',
            'cchr' => 'color:#222',
            'note' => 'color:#a71d5d',
            'ref' => 'color:#a0a0a0',
            'public' => 'color:#795da3',
            'protected' => 'color:#795da3',
            'private' => 'color:#795da3',
            'meta' => 'color:#b729d9',
            'key' => 'color:#df5000',
            'index' => 'color:#a71d5d',
        ];
        ob_start();

        $dumper = new \Symfony\Component\VarDumper\Dumper\HtmlDumper;
        $dumper->setStyles($styles);
  
        $dumper->dump((new \Symfony\Component\VarDumper\Cloner\VarCloner)->cloneVar($v));
        
        //$dumper->dump($v);
        $result = ob_get_clean();
    
        return $result;    
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
     * Add slash at the end if doesnt exists 
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
	public function checkSlash($str)
	{
	    return substr($str,-1) !== '/' ? $str .'/' : $str;
	}

	public function varToHtml($var='', $key='') {
	     $type = gettype($var);
	      $result = '';

	      if (in_array($type, array('object','array'))) {
	        $result .= '
	          <table class="debug-table">
	            <tr>
	              <td class="debug-key-cell"><b>'.$key.'</b><br/>Type: '.$type.'<br/>Length: '.count($var).'</td>
	              <td class="debug-value-cell">';

	        foreach ($var as $akey => $val) {
	          $result .= $this->varToHtml($val, $akey);
	        }
	        $result .= '</td></tr></table>';
	      } else {
	        $result .= '<div class="debug-item"><span class="debug-label">'.$key.' ('.$type.'): </span><span class="debug-value">'.$var.'</span></div>';
	      }

	      return $result;
	   }

	    public function test()
	    {
	    	return "hello";
	    }
	}