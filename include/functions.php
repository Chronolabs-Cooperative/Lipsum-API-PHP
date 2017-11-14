<?php
/**
 * Chronolabs REST Whois API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         whois
 * @since           1.0.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Whois API Service REST
 */



if (!function_exists("getURIData")) {
    
    /* function getURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function getURIData($uri = '', $timeout = 25, $connectout = 25)
    {
        if (!function_exists("curl_init"))
        {
            return file_get_contents($uri);
        }
        if (!$btt = curl_init($uri)) {
            return false;
        }
        curl_setopt($btt, CURLOPT_HEADER, 0);
        curl_setopt($btt, CURLOPT_POST, 0);
        curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
        curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($btt, CURLOPT_VERBOSE, false);
        curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($btt);
        curl_close($btt);
        return $data;
    }
}


if (!function_exists("writeRawFile")) {
    /**
     *
     * @param string $file
     * @param string $data
     */
    function writeRawFile($file = '', $data = '')
    {
        $lineBreak = "\n";
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $lineBreak = "\r\n";
        }
        if (!is_dir(dirname($file)))
            mkdir(dirname($file), 0777, true);
            if (is_file($file))
                unlink($file);
                $data = str_replace("\n", $lineBreak, $data);
                $ff = fopen($file, 'w');
                fwrite($ff, $data, strlen($data));
                fclose($ff);
    }
}


if (!function_exists("checkEmail")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function checkEmail($email, $antispam = false)
    {
        if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
            return false;
        }
        $email_array      = explode('@', $email);
        $local_array      = explode('.', $email_array[0]);
        $local_arrayCount = count($local_array);
        for ($i = 0; $i < $local_arrayCount; ++$i) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode('.', $email_array[1]);
            if (count($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < count($domain_array); ++$i) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        if ($antispam) {
            $email = str_replace('@', ' at ', $email);
            $email = str_replace('.', ' dot ', $email);
        }
        
        return $email;
    }
}

if (!function_exists("writeRawFile")) {
    /**
     *
     * @param string $file
     * @param string $data
     */
    function writeRawFile($file = '', $data = '')
    {
        $lineBreak = "\n";
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $lineBreak = "\r\n";
        }
        if (!is_dir(dirname($file)))
            mkdir(dirname($file), 0777, true);
            if (is_file($file))
                unlink($file);
                $data = str_replace("\n", $lineBreak, $data);
                $ff = fopen($file, 'w');
                fwrite($ff, $data, strlen($data));
                fclose($ff);
    }
}

if (!function_exists("getCompleteFilesListAsArray")) {
	function getCompleteFilesListAsArray($dirname, $result = array())
	{
		foreach(getCompleteDirListAsArray($dirname) as $path)
			foreach(getFileListAsArray($path) as $file)
				$result[$path.DIRECTORY_SEPARATOR.$file] = $path.DIRECTORY_SEPARATOR.$file;
				return $result;
	}

}


if (!function_exists("getCompleteDirListAsArray")) {
	function getCompleteDirListAsArray($dirname, $result = array())
	{
		$result[$dirname] = $dirname;
		foreach(getDirListAsArray($dirname) as $path)
		{
			$result[$dirname . DIRECTORY_SEPARATOR . $path] = $dirname . DIRECTORY_SEPARATOR . $path;
			$result = getCompleteDirListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
		}
		return $result;
	}

}

if (!function_exists("getCompleteHistoryListAsArray")) {
	function getCompleteHistoryListAsArray($dirname, $result = array())
	{
		foreach(getCompleteDirListAsArray($dirname) as $path)
		{
			foreach(getHistoryListAsArray($path) as $file=>$values)
				$result[$path][sha1_file($path . DIRECTORY_SEPARATOR . $values['file'])] = array_merge(array('fullpath'=>$path . DIRECTORY_SEPARATOR . $values['file']), $values);
		}
		return $result;
	}
}

if (!function_exists("getDirListAsArray")) {
	function getDirListAsArray($dirname)
	{
		$ignored = array(
				'cvs' ,
				'_darcs');
		$list = array();
		if (substr($dirname, - 1) != '/') {
			$dirname .= '/';
		}
		if ($handle = opendir($dirname)) {
			while ($file = readdir($handle)) {
				if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored))
					continue;
					if (is_dir($dirname . $file)) {
						$list[$file] = $file;
					}
			}
			closedir($handle);
			asort($list);
			reset($list);
		}

		return $list;
	}
}

if (!function_exists("getFileListAsArray")) {
	function getFileListAsArray($dirname, $prefix = '')
	{
		$filelist = array();
		if (substr($dirname, - 1) == '/') {
			$dirname = substr($dirname, 0, - 1);
		}
		if (is_dir($dirname) && $handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (! preg_match('/^[\.]{1,2}$/', $file) && is_file($dirname . '/' . $file)) {
					$file = $prefix . $file;
					$filelist[$file] = $file;
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}

		return $filelist;
	}
}

if (!function_exists("getHistoryListAsArray")) {
	function getHistoryListAsArray($dirname, $prefix = '')
	{
		$formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'history-formats.diz'));
		$filelist = array();

		if ($handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				foreach($formats as $format)
					if (substr(strtolower($file), strlen($file)-strlen(".".$format)) == strtolower(".".$format)) {
						$file = $prefix . $file;
						$filelist[$file] = array('file'=>$file, 'type'=>$format, 'sha1' => sha1_file($dirname . DIRECTORY_SEPARATOR . $file));
					}
			}
			closedir($handle);
		}
		return $filelist;
	}
}


if (!function_exists("cleanWhitespaces")) {
	/**
	 *
	 * @param array $array
	 */
	function cleanWhitespaces($array = array())
	{
		foreach($array as $key => $value)
		{
			if (is_array($value))
				$array[$key] = cleanWhitespaces($value);
				else {
					$array[$key] = trim(str_replace(array("\n", "\r", "\t"), "", $value));
				}
		}
		return $array;
	}
}


if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIPAddy()
	 * 
	 * 	provides an associative array of whitelisted IP Addresses
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @return 		array
	 */
	function whitelistGetIPAddy() {
		return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
	}
}

if (!function_exists("whitelistGetNetBIOSIP")) {

	/* function whitelistGetNetBIOSIP()
	 *
	 * 	provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @return 		array
	 */
	function whitelistGetNetBIOSIP() {
		$ret = array();
		foreach(file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
			$ip = gethostbyname($domain);
			$ret[$ip] = $ip;
		} 
		return $ret;
	}
}

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIP()
	 *
	 * 	get the True IPv4/IPv6 address of the client using the API
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 * 
	 * @param		boolean		$asString	Whether to return an address or network long integer
	 * 
	 * @return 		mixed
	 */
	function whitelistGetIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
		} else
		if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED'];
		} else
		if (!empty($_SERVER['HTTP_VIA'])) {
			$proxy_ip = $_SERVER['HTTP_VIA'];
		} else
		if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
		} else
		if (!empty($_SERVER['HTTP_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
		}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
			
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
		return $the_IP;
	}
}


if (!function_exists("setCallBackURI")) {
    /*
     * set's a callback to be called in the database reference for the cronjob
     *
     * @param string $uri
     * @param integer $timeout
     * @param integer $connectout
     * @param array $data
     * @param array $queries
     *
     * @return boolean
     */
    function setCallBackURI($uri = '', $timeout = 65, $connectout = 65, $data = array(), $queries = array())
    {
        list($when) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF("SELECT `when` from `" . $GLOBALS['APIDB']->prefix('callbacks') . "` ORDER BY `when` DESC LIMIT 1"));
        if ($when<time())
            $when = $time();
            $when = $when + mt_rand(3, 14);
            return $GLOBALS['APIDB']->queryF("INSERT INTO `" . $GLOBALS['APIDB']->prefix('callbacks') . "` (`when`, `uri`, `timeout`, `connection`, `data`, `queries`) VALUES(\"$when\", \"$uri\", \"$timeout\", \"$connectout\", \"" . $GLOBALS['APIDB']->escape(json_encode($data)) . "\",\"" . $GLOBALS['APIDB']->escape(json_encode($queries)) . "\")");
    }
}

if (!function_exists("setExecutionTimer")) {
    /**
     * get a font nodes by Font Identity Hash
     *
     * @param string $font_id
     *
     * @return array
     */
    function setExecutionTimer($what = 'header')
    {
        static $seconds = 0;
        if ($seconds == 0)
            $seconds = ini_get('max_execution_time');
            
            if (file_exists($cache = getCacheFilename(FONTS_CACHE , 'execution-timers--%s--%s.json',sha1($what), 'php')))
            {
                $timers = json_decode(file_get_contents($cache), true);
                foreach($timers as $id => $time)
                {
                    $ttl = $ttl + $time;
                }
                $seconds = $seconds + ($ttl / count($timers));
            } else {
                $seconds = $seconds + 9;
            }
            set_time_limit($seconds);
            ini_set('max_execution_time', $seconds);
    }
}

if (!function_exists("saveExecutionTimer")) {
    /**
     * get a font nodes by Font Identity Hash
     *
     * @param string $font_id
     *
     * @return array
     */
    function saveExecutionTimer()
    {
        static $seconds = 0;
        if ($seconds == 0)
            $seconds = ini_get('max_execution_time');
            
            $cache = getCacheFilename(FONTS_CACHE , 'execution-timers--%s--%s.json',sha1('header'), 'php');
            $timers = json_decode(file_get_contents($cache), true);
            $timers[$GLOBAL['header']['start']] = $GLOBAL['header']['end'] - $GLOBAL['header']['start'];
            if(($max = mt_rand(32,78))>count($timers))
            {
                $keys = array_keys($timers);
                $index = $max;
                foreach($keys as $key)
                {
                    if ($index>0)
                    {
                        unset($timers[$key]);
                        $index--;
                    }
                }
            }
            @writeRawFile($cache, json_encode($timers));
            if (isset($GLOBAL['apifuncs']) && !empty($GLOBAL['apifuncs']))
            {
                foreach($GLOBAL['apifuncs'] as $what => $values)
                {
                    $cache = getCacheFilename(FONTS_CACHE , 'execution-timers--%s--%s.json',sha1($what), 'php');
                    $timers = json_decode(file_get_contents($cache), true);
                    $timers[$values['start']] = $values['end'] - $values['start'];
                    if(($max = mt_rand(32,78))>count($timers))
                    {
                        $keys = array_keys($timers);
                        $index = $max;
                        foreach($keys as $key)
                        {
                            if ($index>0)
                            {
                                unset($timers[$key]);
                                $index--;
                            }
                        }
                    }
                    @writeRawFile($cache, json_encode($timers));
                }
                
            }
            
    }
}



if (!function_exists("getCacheFilename")) {
    /**
     * get a cache filename from the routine for passing to cache routines
     *
     * @param string $path
     * @param string $ffiletemp
     * @param string $filehash
     * @param string $output
     * @param integer $maxedfor
     *
     * @return string
     */
    function getCacheFilename($path = '', $ffiletemp = '', $filehash = '', $output = '', $maxedfor = -1)
    {
        if (empty($output))
            $output = 'data';
            
            if (!is_dir($path  . DIRECTORY_SEPARATOR . ".$output"))
                mkdir($path  . DIRECTORY_SEPARATOR . ".$output", 0777, true);
                
                // Works out cache file spacing times
                $diff = ceil(time() - strtotime(date("Y-m-d H:00:00")) / 20) * 60;
                $origin = strtotime(date("Y-m-d H:00:00", strtotime(date("Y-m-d H:00:00")) + $diff * 20));
                $last = strtotime(date("Y-m-d H:00:00", strtotime(date("Y-m-d H:00:00")) + $diff * 20) - (3600*24));
                if ($maxedfor==-1)
                    $dropfrom = strtotime(date("Y-m-d H:00:00", strtotime(date("Y-m-d H:00:00")) + $diff * 20) - (3600*mt_rand(11,21)));
                    else
                        $dropfrom = strtotime(date("Y-m-d H:00:00", strtotime(date("Y-m-d H:00:00")) + $diff * 20) - $maxedfor);
                        
                        // Calculates Cache Time
                        $filename = '';
                        for($pioning = $origin + (60*20); $pioning < $last; $pioning = $pioning - (60*20))
                        {
                            if (file_exists($tmpname = $path  . DIRECTORY_SEPARATOR . ".$output" . DIRECTORY_SEPARATOR . sprintf($ffiletemp, date('YmdHis---', $pioning), $filehash)))
                            {
                                if (empty($filename) && $poining > $dropfrom)
                                {
                                    $filename = $tmpname;
                                } else {
                                    unlink($tmpname);
                                    rmdir(dirname($tmpname));
                                }
                            }
                        }
                        if (empty($filename))
                            $filename = $path  . DIRECTORY_SEPARATOR . ".$output" . DIRECTORY_SEPARATOR . sprintf($ffiletemp, date('YmdHis---', $origin), $filehash);
                            if (file_exists($filename) && filesize($filename) == 0)
                                unlink($filename);
                                return $filename;
    }
}


if (!function_exists("getLoremIpsum")) {
    
    /** function getLoremIpsum()
     *
     * Function that get the Lipsum off the Lipsum Generator
     *
     * @author Simon Roberts (Chronolabs) wishcraft@users.sourceforge.net
     *
     * @param integer $amount The number of the type to return
     * @param string $type What is to be returned
     * @param string $start Open with 'Lorem ipsum dolor sit amet...'
     *
     * @return mixed
     */
    function getLoremIpsum($amount = 2, $type = 'paragraphs', $start = 'open', $mode = 'json')
    {
        
        switch ($type)
        {
            default:
            case "paragraphs":
                $type = 'paras';
                break;
            case "words":
            case "bytes":
            case "lists":
                break;
        }
        
        if ($amount<0) $amount = mt_rand(1,6);
        
        switch ($start)
        {
            default:
            case "any":
                $start = 'no';
                break;
            case "lorem":
                $start = 'yes';
                break;
        }
        
        $url = "https://lipsum.com/feed/html?amount=".$amount . "&what=".$type . "&start=" . $start;
        $html = getURIData($url, 25, 15, array());
         
        # Create a DOM parser object
        $dom = new DOMDocument();
        
        # Parse the HTML from Google.
        # The @ before the method call suppresses any warnings that
        # loadHTML might throw because of invalid HTML in the page.
        @$dom->loadHTML($html);
        
        # Iterate over all the <a> tags
        $result = array();
        switch ($type)
        {
            default:
            case "paras":
            case "words":
            case "bytes":
                foreach($dom->getElementsByTagName('p') as $link) {
                    $result[] = $link->nodeValue;
                }
                #outputs buffer
                return implode("<br />", $result);
                break;
            case "lists":
                foreach($dom->getElementsByTagName('li') as $link) {
                    $result[] = $link->nodeValue;
                }
                #outputs buffer
                return $result;
                break;
        }
        
        
        
    }
}

if (!function_exists("SaveToFile")) {
    /**
     * Writes a file to the filebase
     *
     * @param string $file
     * @param string $s
     * @param string $mode
     */
    function SaveToFile($file, $s, $mode='t') {
        $f = fopen($file, 'w'.$mode);
        if(!$f) {
            die('Can\'t write to file '.$file);
        }
        fwrite($f, $s, strlen($s));
        fclose($f);
    }
}

if (!function_exists("putRawFile")) {
    /**
     * Saves a Raw File to the Filebase
     *
     * @param string $file
     * @param string $data
     *
     * @return boolean
     */
    function putRawFile($file = '', $data = '')
    {
        $lineBreak = "\n";
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $lineBreak = "\r\n";
        }
        if (!is_dir(dirname($file)))
            if (strpos(' '.$file, FONTS_CACHE))
                mkdirSecure(dirname($file), 0777, true);
                else
                    mkdir(dirname($file), 0777, true);
                    elseif (strpos(' '.$file, FONTS_CACHE) && !file_exists(FONTS_CACHE . DIRECTORY_SEPARATOR . '.htaccess'))
                    SaveToFile(FONTS_CACHE . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
                    if (is_file($file))
                        unlink($file);
                        return SaveToFile($file, $data);
    }
}


if (!function_exists("getHTMLForm")) {
    /**
     * Get the HTML Forms for the API
     *
     * @param unknown_type $mode
     * @param unknown_type $clause
     * @param unknown_type $output
     * @param unknown_type $version
     *
     * @return string
     */
    function getHTMLForm($mode = '', $clause = '', $callback = '', $output = '', $version = 'v2')
    {
        error_reporting(E_ALL);
        $form = array();
        switch ($mode)
        {
            case "emails":
                $form[] = "<form name=\"email-lorem-lipsum-uploader\" method=\"POST\" enctype=\"multipart/form-data\" action=\"" . API_URL . "/v2/email.api\">";
                $form[] = "\t<table class='email-lorem-lipsum-uploader' id='font-uploader' style='vertical-align: top !important; min-width: 98%;'>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='from-email'>From Email:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold; float:right; position: relative;'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='textbox' name='from[email]' id='from-email' maxlen='198' size='41' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='from-name'>From Name:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold; float:right; position: relative;'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='textbox' name='from[name]' id='from-name' maxlen='198' size='41' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='to-email'>To Email(s):&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold; float:right; position: relative;'>*</font><br/><font style='font-size: 0.78em'>seperated by [<strong> ; </strong>] either in format \"Firstname Lastname\" &lt;email@address.com&gt;; <em>email@address.com;</em></font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td colspan='2'>";
                $form[] = "\t\t\t\t<textarea type='textbox' name='to-email' id='to-email' rows='5' cols='41'></textarea>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='cc-email'>CC's Email(s):&nbsp;<br/><font style='font-size: 0.78em'>seperated by [<strong> ; </strong>] either in format \"Firstname Lastname\" &lt;email@address.com&gt;; <em>email@address.com;</em></font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td colspan='2'>";
                $form[] = "\t\t\t\t<textarea type='textbox' name='cc-email' id='cc-email' rows='5' cols='41'></textarea>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='bcc-email'>BCC's Email(s):&nbsp;<br/><font style='font-size: 0.78em'>seperated by [<strong> ; </strong>] either in format \"Firstname Lastname\" &lt;email@address.com&gt;; <em>email@address.com;</em></font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td colspan='2'>";
                $form[] = "\t\t\t\t<textarea type='textbox' name='bcc-email' id='bcc-email' rows='5' cols='41'></textarea>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='signature'>Email Signature:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold; float:right; position: relative;'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td colspan='2'>";
                $form[] = "\t\t\t\t<textarea type='textbox' name='signature' id='signature' rows='5' cols='41'></textarea>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='reply-email'>Reply To Email:&nbsp;</label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='textbox' name='reply-email' id='reply-email' maxlen='198' size='41' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='priority'>Email Priority:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold; float:right; position: relative;'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='priority-low'>Low&nbsp;</label><input type='radio' name='priority' id='priority-low' value='low' />&nbsp;&nbsp;";
                $form[] = "\t\t\t\t<label for='priority-normal'>Normal&nbsp;</label><input type='radio' name='priority' id='priority-normal' value='normal' checked='checked'/>&nbsp;&nbsp;";
                $form[] = "\t\t\t\t<label for='priority-high'>High&nbsp;</label><input type='radio' name='priority' id='priority-high' value='high' />";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3'>";
                $form[] = "\t\t\t\t<label for='attachment-zero'>File 1 to Attach to Email:&nbsp;</label>";
                $form[] = "\t\t\t\t<input type='file' name='attachment[0]' id='attachment-zero'><br/>";
                $form[] = "\t\t\t\t<label for='attachment-one'>File 2 to Attach to Email:&nbsp;</label>";
                $form[] = "\t\t\t\t<input type='file' name='attachment[1]' id='attachment-one'><br/>";
                $form[] = "\t\t\t\t<label for='attachment-two'>File 3 to Attach to Email:&nbsp;</label>";
                $form[] = "\t\t\t\t<input type='file' name='attachment[2]' id='attachment-two'><br/>";
                $form[] = "\t\t\t\t<label for='attachment-three'>File 4 to Attach to Email:&nbsp;</label>";
                $form[] = "\t\t\t\t<input type='file' name='attachment[3]' id='attachment-three'><br/>";
                $form[] = "\t\t\t\t<label for='attachment-four'>File 5 to Attach to Email:&nbsp;</label>";
                $form[] = "\t\t\t\t<input type='file' name='attachment[4]' id='attachment-four'><br/>";
                $form[] = "\t\t\t\t<div style='margin-left:42px; font-size: 71.99%; margin-top: 7px; padding: 11px;'>";
                $form[] = "\t\t\t\t\t ~~ <strong>Maximum Upload Size Is: <em style='color:rgb(255,100,123); font-weight: bold; font-size: 132.6502%;'>" . ini_get('upload_max_filesize') . "!!!</em></strong><br/>";
                $form[] = "\t\t\t\t</div>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='paragraphs'>Paragramatical Settings:&nbsp;</label><font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold; float:right; position: relative;'>*</font>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='paragraphs-minumum'>Minimum Paragraphs:&nbsp;</label><input type='textbox' name='paragraphs-minimum' id='paragraphs-minimum' value='".($minp=mt_rand(1,5))."' maxlen='2' size='4' />&nbsp;&nbsp;";
                $form[] = "\t\t\t\t<label for='paragraphs-maximum'>Maximum Paragraphs:&nbsp;</label><input type='textbox' name='paragraphs-maximum' id='paragraphs-maximum' value='".($minp=mt_rand($minp+$minp,$minp+$minp+$minp))."' maxlen='2' size='4' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='list'>List Settings:&nbsp;</label><font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold; float:right; position: relative;'>*</font>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='number-list'>Number of Lists:&nbsp;</label><input type='textbox' name='number-lists' id='number-lists' value='".($listn=mt_rand(1,4))."' maxlen='2' size='4' />&nbsp;&nbsp;";
                $form[] = "\t\t\t\t<label for='list-items-minimum'>Items In List Minimum:&nbsp;</label><input type='textbox' name='list-items-minimum' id='list-items-mininum' value='".($items=mt_rand(7,13))."' maxlen='2' size='4' />&nbsp;&nbsp;";
                $form[] = "\t\t\t\t<label for='list-items-maximum'>Items in List Maximum:&nbsp;</label><input type='textbox' name='list-items-maximum' id='list-items-maximum' value='".($items=mt_rand($items+$items,$items+$items+$items))."' maxlen='2' size='4' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<label for='list-items-maximum'>Support Embedded Lists:&nbsp;</label><input type='checkbox' name='lists-scope' id='scope[lists]' value='1' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='return-url'>Return URL:&nbsp;</label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='textbox' name='return-url' id='return-url' value='".API_URL."' disabled='disabled' maxlen='198' size='41' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='callback-url'>Callback URL:&nbsp;</label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='textbox' name='callback-url' id='callback-url' value='".API_URL."/v2/callback.api' disabled='disabled' maxlen='198' size='41' />&nbsp;&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t&nbsp;";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
                $form[] = "\t\t\t\t<input type='hidden' name='return' value='" . API_URL ."'>";
                $form[] = "\t\t\t\t<input type='hidden' name='callback' value='" . API_URL."/v2/callback.api'>";
                $form[] = "\t\t\t\t<input type='submit' value='Upload File' name='submit' style='padding:11px; font-size:122%;'>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
                $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t</table>";
                $form[] = "</form>";
                break;
        }
        return implode("\n", $form);
    }
}


if (!function_exists("whitelistGetIP")) {
    /**
     * Provides an associative array of whitelisted IP Addresses
     *
     * @return array
     */
    function whitelistGetIPAddy() {
        return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
    }
}

if (!function_exists("whitelistGetNetBIOSIP")) {
    /**
     * provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
     *
     * @return array
     */
    function whitelistGetNetBIOSIP() {
        $ret = array();
        foreach(file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
            $ip = gethostbyname($domain);
            $ret[$ip] = $ip;
        }
        return $ret;
    }
}

if (!function_exists("whitelistGetIP")) {
    /**
     * get the True IPv4/IPv6 address of the client using the API
     *
     * @param boolean $asString
     *
     * @return mixed
     */
    function whitelistGetIP($asString = true){
        
        // Gets the proxy ip sent by the user
        $proxy_ip = '';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else
            if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
                $proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
            } else
                if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                    $proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
                } else
                    if (!empty($_SERVER['HTTP_FORWARDED'])) {
                        $proxy_ip = $_SERVER['HTTP_FORWARDED'];
                    } else
                        if (!empty($_SERVER['HTTP_VIA'])) {
                            $proxy_ip = $_SERVER['HTTP_VIA'];
                        } else
                            if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
                                $proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
                            } else
                                if (!empty($_SERVER['HTTP_COMING_FROM'])) {
                                    $proxy_ip = $_SERVER['HTTP_COMING_FROM'];
                                }
                            
                            if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
                                $the_IP = $regs[0];
                            } else {
                                $the_IP = $_SERVER['REMOTE_ADDR'];
                            }
                            
                            if (isset($_REQUEST['ip']) && !empty($_REQUEST['ip']) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $_REQUEST['ip'], $regs) && count($regs) > 0)  {
                                $ip = $regs[0];
                            }
                            
                            return isset($ip) && !empty($ip)?(($asString) ? $ip : ip2long($ip)):(($asString) ? $the_IP : ip2long($the_IP));
    }
}


if (!function_exists("getIPIdentity")) {
    /**
     * Gets the networking IP Identity Hash and Sets User Identity Session Variables
     *
     * @param string $ip
     * @param boolean $sarray
     *
     * @return mixed
     */
    function getIPIdentity($ip = '', $sarray = false)
    {
        $sql = array();
        if (empty(session_id()))
            session_start();
            if (empty($ip))
                $ip = whitelistGetIP(true);
                
                if (!isset($_SESSION['ipdata'][$ip]) || empty($_SESSION['ipdata'][$ip]) || !isset($_SESSION['locality']))
                {
                    
                    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false)
                        $sql['selecta'] = "SELECT * from `" . $GLOBALS['APIDB']->prefix('networking') . "` WHERE `ipaddy` LIKE '" . $ip . "' AND `type` = 'ipv6'";
                        else
                            $sql['selecta'] = "SELECT * from `" . $GLOBALS['APIDB']->prefix('networking') . "` WHERE `ipaddy` LIKE '" . $ip . "' AND `type` = 'ipv4'";
                            if (!$row = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF($sql['selecta'])))
                            {
                                if (($ipaddypart[0] ===  $serverpart[0] && $ipaddypart[1] ===  $serverpart[1]) )
                                {
                                    $_SESSION['locality'] = array();
                                    if (API_NETWORK_LOGISTICS==true)
                                    {
                                        $uris = cleanWhitespaces(file($file = __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "lookups.diz"));
                                        shuffle($uris); shuffle($uris); shuffle($uris); shuffle($uris);
                                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE || FILTER_FLAG_NO_RES_RANGE) === false || substr($ip,3,0)=="10." || substr($ip,4,0)=="127.")
                                        {
                                            $data = array();
                                            foreach($uris as $uri)
                                            {
                                                if ($_SESSION['locality']['ip']==$ip || $_SESSION['locality']['country']['iso'] == "-" || empty($_SESSION['locality']))
                                                    $_SESSION['locality'] = json_decode(getURIData(sprintf($uri, 'myself', 'json'), 5, 10), true);
                                                    if (count($_SESSION['locality']) > 1 &&  $_SESSION['locality']['country']['iso'] != "-")
                                                        continue;
                                            }
                                        } else{
                                            foreach($uris as $uri)
                                            {
                                                if ($_SESSION['locality']['ip']!=$ip || $_SESSION['locality']['country']['iso'] == "-" || empty($_SESSION['locality']))
                                                    $_SESSION['locality'] = json_decode(getURIData(sprintf($uri, $ip, 'json'), 5, 10), true);
                                                    if (count($_SESSION['locality']) > 1 &&  $_SESSION['locality']['country']['iso'] != "-")
                                                        continue;
                                            }
                                        }
                                    }
                                    if (!isset($_SESSION['locality']['ip']))
                                        $_SESSION['locality']['ip'] = $ip;
                                        
                                        $trace = $output = array();
                                        if (API_NETWORK_LOGISTICS==true)
                                        {
                                            
                                            $start = microtime(true);
                                            exec("/usr/bin/traceroute --max-hops=100 $ip", $return, $output);
                                            $took = microtime(true) - $start;
                                            
                                            if (!empty($output))
                                            {
                                                
                                                foreach($output as $result)
                                                {
                                                    foreach(array('ms', '(', ')') as $replace)
                                                        while (strpos($result, $replace))
                                                            $result = trim(str_replace($result, $replace, ''));
                                                            while (strpos($result, '  '))
                                                                $result = trim(str_replace($result, '  ', ' '));
                                                                $parts = explode(' ', $result);
                                                                if ($parts[0]=='traceroute')
                                                                {
                                                                    $hosthop = $parts[2];
                                                                    $iphop = $parts[3];
                                                                } elseif (is_numeric($parts[0]) && count($parts[3]) == 6) {
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[1]][$parts[2]][1]=$parts[3];
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[1]][$parts[2]][2]=$parts[4];
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[1]][$parts[2]][3]=$parts[5];
                                                                } elseif (is_numeric($parts[0]) && count($parts[3]) == 10 && is_string($parts[1]) && is_string($parts[4]) && !is_numeric($parts[4]) && is_string($parts[7]) && !is_numeric($parts[7])) {
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[1]][$parts[2]][1]=$parts[3];
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[4]][$parts[5]][2]=$parts[6];
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[7]][$parts[8]][3]=$parts[5];
                                                                } elseif (is_numeric($parts[0]) && count($parts[3]) == 8 && is_string($parts[1]) && is_string($parts[4]) && !is_numeric($parts[4])) {
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[1]][$parts[2]][1]=$parts[3];
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[4]][$parts[5]][2]=$parts[6];
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[1]][$parts[2]][3]=$parts[5];
                                                                } elseif (is_numeric($parts[0]) && count($parts[3]) == 8 && is_string($parts[1]) && is_string($parts[5]) && !is_numeric($parts[5])) {
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[1]][$parts[2]][1]=$parts[3];
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[1]][$parts[2]][2]=$parts[4];
                                                                    $trace[$when][$took][$iphop][$hosthop][$parts[0]][$parts[5]][$parts[6]][3]=$parts[7];
                                                                }
                                                }
                                            }
                                        }
                                        
                                        $_SESSION['ipdata'][$ip] = array();
                                        $_SESSION['ipdata'][$ip]['ipaddy'] = $ip;
                                        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false)
                                            $_SESSION['ipdata'][$ip]['type'] = 'ipv6';
                                            else
                                                $_SESSION['ipdata'][$ip]['type'] = 'ipv4';
                                                $_SESSION['ipdata'][$ip]['netbios'] = gethostbyaddr($ip);
                                                $_SESSION['ipdata'][$ip]['data'] = array('ipstack' => gethostbynamel($_SESSION['ipdata'][$ip]['netbios']), 'trace' => $trace);
                                                $_SESSION['ipdata'][$ip]['domain'] = getBaseDomain("http://".$_SESSION['ipdata'][$ip]['netbios']);
                                                if (API_NETWORK_LOGISTICS==true)
                                                {
                                                    $_SESSION['ipdata'][$ip]['country'] = $_SESSION['locality']['country']['iso'];
                                                    $_SESSION['ipdata'][$ip]['region'] = $_SESSION['locality']['location']['region'];
                                                    $_SESSION['ipdata'][$ip]['city'] = $_SESSION['locality']['location']['city'];
                                                    $_SESSION['ipdata'][$ip]['postcode'] = $_SESSION['locality']['location']['postcode'];
                                                    $_SESSION['ipdata'][$ip]['timezone'] = "GMT " . $_SESSION['locality']['location']['gmt'];
                                                    $_SESSION['ipdata'][$ip]['longitude'] = $_SESSION['locality']['location']['coordinates']['longitude'];
                                                    $_SESSION['ipdata'][$ip]['latitude'] = $_SESSION['locality']['location']['coordinates']['latitude'];
                                                }
                                                $_SESSION['ipdata'][$ip]['last'] = $_SESSION['ipdata'][$ip]['created'] = time();
                                                $_SESSION['ipdata'][$ip]['downloads'] = 0;
                                                $_SESSION['ipdata'][$ip]['uploads'] = 0;
                                                $_SESSION['ipdata'][$ip]['fonts'] = 0;
                                                $_SESSION['ipdata'][$ip]['surveys'] = 0;
                                                
                                                if (API_NETWORK_LOGISTICS==true)
                                                {
                                                    $whois = array();
                                                    $whoisuris = cleanWhitespaces(file(__DIR__  . DIRECTORY_SEPARATOR .  "data" . DIRECTORY_SEPARATOR . "whois.diz"));
                                                    shuffle($whoisuris); shuffle($whoisuris); shuffle($whoisuris); shuffle($whoisuris);
                                                    foreach($whoisuris as $uri)
                                                    {
                                                        if (empty($whois[$_SESSION['ipdata'][$ip]['type']]) || !isset($whois[$_SESSION['ipdata'][$ip]['type']]))
                                                        {
                                                            $whois[$_SESSION['ipdata'][$ip]['type']] = json_decode(getURIData(sprintf($uri, $_SESSION['ipdata'][$ip]['ipaddy'], 'json'), 5, 10), true);
                                                        } elseif (empty($whois['domain']) || !isset($whois['domain']))
                                                        {
                                                            $whois['domain'] = json_decode(getURIData(sprintf($uri, $_SESSION['ipdata'][$ip]['domain'], 'json'), 5, 10), true);
                                                        } else
                                                            continue;
                                                    }
                                                    $sql = "SELECT count(*) FROM `whois` WHERE `id` = '".$wsid = md5(json_encode($whois))."'";
                                                    list($countb) = $GLOBALS['APIDB']->fetchRow($GLOBALS['APIDB']->queryF($sql));
                                                    if ($countb == 0)
                                                    {
                                                        $wsdata = array();
                                                        $wsdata['id'] = $wsid;
                                                        $wsdata['whois'] = $GLOBALS['APIDB']->escape(json_encode($whois));
                                                        $wsdata['created'] = time();
                                                        $wsdata['last'] = time();
                                                        $wsdata['instances'] = 1;
                                                        if (!$GLOBALS['APIDB']->queryF($sql = "INSERT INTO `whois` (`" . implode('`, `', array_keys($wsdata)) . "`) VALUES ('" . implode("', '", $wsdata) . "')"))
                                                            @$GLOBALS['APIDB']->queryF($sql = "UPDATE `whois` SET `instances` = `instances` + 1, `last` = unix_timestamp() WHERE `id` =  '$wsid'");
                                                    } else {
                                                        
                                                    }
                                                    $_SESSION['ipdata'][$ip]['whois'] = $wsid;
                                                }
                                                
                                                $_SESSION['ipdata'][$ip]['ip_id'] = md5(json_encode($_SESSION['ipdata'][$ip]));
                                                
                                                $data = array();
                                                foreach($_SESSION['ipdata'][$ip] as $key => $value)
                                                    if (is_array($value))
                                                        $data[$key] = $GLOBALS['APIDB']->escape(json_encode($value));
                                                        else
                                                            $data[$key] = $GLOBALS['APIDB']->escape($value);
                                                            
                                                            $sql['selectb'] = "SELECT * from `" . $GLOBALS['APIDB']->prefix('networking') . "` WHERE `ip_id` LIKE '" . $_SESSION['ipdata'][$ip]['ip_id'] . "'";
                                                            if (!$GLOBALS['APIDB']->getRowsNum($GLOBALS['APIDB']->queryF($sql['selectb'])))
                                                            {
                                                                $sql = "INSERT INTO `" . $GLOBALS['APIDB']->prefix('networking') . "` (`" . implode("`, `", array_keys($data)) . "`) VALUES ('" . implode("', '", $data) . "')";
                                                                if (!$GLOBALS['APIDB']->queryF($sql))
                                                                    trigger_error("SQL Failed: ".$GLOBALS['APIDB']->error() . " :: $sql");
                                                            } else {
                                                                $sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('networking') . "` SET `last` = '". time() . '\' WHERE `ip_id` = "' . $_SESSION['ipdata'][$ip]['ip_id'] .'"';
                                                                if (!$GLOBALS['APIDB']->queryF($sql))
                                                                    trigger_error("SQL Failed: ".$GLOBALS['APIDB']->error() . " :: $sql");
                                                            }
                                                            
                                }
                            }
                }
                if ($sarray == false)
                    return $_SESSION['ipdata'][$ip]['ip_id'];
                    else
                        return $_SESSION['ipdata'][$ip];
    }
}


if (!function_exists("getBaseDomain")) {
    /**
     * Gets the base domain of a tld with subdomains, that is the root domain header for the network rout
     *
     * @param string $url
     *
     * @return string
     */
    function getBaseDomain($uri = '')
    {
        
        static $fallout, $stratauris, $classes;
        
        if (API_NETWORK_LOGISTICS==true)
        {
            if (empty($classes))
            {
                if (empty($stratauris)) {
                    $stratauris = cleanWhitespaces(file(__DIR__  . DIRECTORY_SEPARATOR .  "data" . DIRECTORY_SEPARATOR . "stratas.diz"));
                    shuffle($stratauris); shuffle($stratauris); shuffle($stratauris); shuffle($stratauris);
                }
                shuffle($stratauris);
                $attempts = 0;
                while(empty($classes) || $attempts <= (count($stratauris) * 1.65))
                {
                    $attempts++;
                    $classes = array_keys(json_decode(getURIData($stratauris[mt_rand(0, count($stratauris)-1)] ."/v1/strata/serial.api", 15, 10), true));
                }
            }
            if (empty($fallout))
            {
                if (empty($stratauris)) {
                    $stratauris = cleanWhitespaces(file(__DIR__  . DIRECTORY_SEPARATOR .  "data" . DIRECTORY_SEPARATOR . "stratas.diz"));
                    shuffle($stratauris); shuffle($stratauris); shuffle($stratauris); shuffle($stratauris);
                }
                shuffle($stratauris);
                $attempts = 0;
                while(empty($fallout) || $attempts <= (count($stratauris) * 1.65))
                {
                    $attempts++;
                    $fallout = array_keys(json_decode(getURIData($stratauris[mt_rand(0, count($stratauris)-1)] ."/v1/fallout/serial.api", 15, 10), true));
                }
            }
            
            // Get Full Hostname
            $uri = strtolower($uri);
            $hostname = parse_url($uri, PHP_URL_HOST);
            if (!filter_var($hostname, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 || FILTER_FLAG_IPV4) === false)
                return $hostname;
                
                // break up domain, reverse
                $elements = explode('.', $hostname);
                $elements = array_reverse($elements);
                
                // Returns Base Domain
                if (in_array($elements[0], $classes))
                    return $elements[1] . '.' . $elements[0];
                    elseif (in_array($elements[0], $fallout) && in_array($elements[1], $classes))
                    return $elements[2] . '.' . $elements[1] . '.' . $elements[0];
                    elseif (in_array($elements[0], $fallout))
                    return  $elements[1] . '.' . $elements[0];
                    else
                        return  $elements[1] . '.' . $elements[0];
        }
        
        return parse_url($uri, PHP_URL_HOST);
    }
}

if (!function_exists("mkdirSecure")) {
    /**
     * Make a folder and secure's it with .htaccess mod-rewrite with apache2
     *
     * @param string $path
     * @param integer $perm
     * @param boolean $secure
     *
     * @return boolean
     */
    function mkdirSecure($path = '', $perm = 0777, $secure = true)
    {
        if (!is_dir($path))
        {
            mkdir($path, $perm, true);
            if ($secure == true)
            {
                SaveToFile($path . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
            }
            return true;
        }
        return false;
    }
}

if (!function_exists("cleanWhitespaces")) {
    /**
     * Clean's an array of \n, \r, \t when importing for example with file() and includes carriage returns in array
     *
     * @param array $array
     *
     * @return array
     */
    function cleanWhitespaces($array = array())
    {
        foreach($array as $key => $value)
        {
            if (is_array($value))
                $array[$key] = cleanWhitespaces($value);
                else {
                    $array[$key] = trim(str_replace(array("\n", "\r", "\t"), "", $value));
                }
        }
        return $array;
    }
}

if (!function_exists('sef'))
{
    /**
     * Safe encoded paths elements
     *
     * @param unknown $datab
     * @param string $char
     *
     * @return string
     */
    function sef($value = '', $stripe ='-')
    {
        return(strtolower(getOnlyAlpha($result, $stripe)));
    }
}


if (!function_exists('getOnlyAlpha'))
{
    /**
     * Safe encoded paths elements
     *
     * @param unknown $datab
     * @param string $char
     *
     * @return string
     */
    function getOnlyAlpha($value = '', $stripe ='-')
    {
        $value = str_replace('&', 'and', $value);
        $value = str_replace(array("'", '"', "`"), 'tick', $value);
        $replacement_chars = array();
        $accepted = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","m","o","p","q",
            "r","s","t","u","v","w","x","y","z","0","9","8","7","6","5","4","3","2","1");
        for($i=0;$i<256;$i++){
            if (!in_array(strtolower(chr($i)),$accepted))
                $replacement_chars[] = chr($i);
        }
        $result = trim(str_replace($replacement_chars, $stripe, ($value)));
        while(strpos($result, $stripe.$stripe, 0))
            $result = (str_replace($stripe.$stripe, $stripe, $result));
            while(substr($result, 0, strlen($stripe)) == $stripe)
                $result = substr($result, strlen($stripe), strlen($result) - strlen($stripe));
                while(substr($result, strlen($result) - strlen($stripe), strlen($stripe)) == $stripe)
                    $result = substr($result, 0, strlen($result) - strlen($stripe));
                    return($result);
    }
}

if (!function_exists("spacerName")) {
    /**
     * Formats font name to correct definition textualisation without typed precisioning
     *
     * @param string $name
     *
     * @return string
     */
    function spacerName($name = '')
    {
        $name = getOnlyAlpha(str_replace(array('-', ':', ',', '<', '>', ';', '+', '_', '(', ')', '[', ']', '{', '}', '='), ' ', $name), ' ');
        $nname = '';
        $previous = $last = '';
        for($i=0; $i<strlen($name); $i++)
        {
            if (substr($name, $i, 1)==strtoupper(substr($name, $i, 1)) && $last==strtolower($last))
            {
                $nname .= ' ' . substr($name, $i, 1);
            } else
                $nname .= substr($name, $i, 1);
                $last=substr($name, $i, 1);
        }
        while(strpos($nname, '  ')>0)
            $nname = str_replace('  ', ' ', $nname);
            return trim(implode(' ', array_unique(explode(' ', $nname))));
    }
}

if (!function_exists("checkEmail")) {
    /**
     * checks if a data element is an email address
     *
     * @param mixed $email
     *
     * @return bool|mixed
     */
    function checkEmail($email)
    {
        if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
            return false;
        }
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        return $email;
    }
}

if (!function_exists("writeRawFile")) {
    /**
     * Writes RAW File Data
     *
     * @param string $file
     * @param string $data
     *
     * @return boolean
     */
    function writeRawFile($file = '', $data = '')
    {
        if (!is_dir(dirname($file)))
            mkdir(dirname($file), 0777, true);
            if (is_file($file))
                unlink($file);
                SaveToFile($file, $data);
                if (!strpos($file, 'caches-files-sessioning.json') && strpos($file, '.json'))
                {
                    
                    if (file_exists(FONTS_CACHE . DIRECTORY_SEPARATOR . 'caches-files-sessioning.json'))
                        $sessions = json_decode(file_get_contents(FONTS_CACHE . DIRECTORY_SEPARATOR . 'caches-files-sessioning.json'), true);
                        else
                            $sessions = array();
                            if (!isset($sessions[basename($file)]))
                                $sessions[basename($file)] = array('file' => $file, 'till' =>microtime(true) + mt_rand(3600*24*7.35,3600*24*14*8.75));
                                foreach($sessions as $file => $values)
                                    if ($values['till']<time() && isset($values['till']))
                                    {
                                        if (file_exists($values['file']))
                                            unlink($values['file'])	;
                                            unset($sessions[$file]);
                                    }
                                SaveToFile(FONTS_CACHE . DIRECTORY_SEPARATOR . 'caches-files-sessioning.json', json_encode($sessions));
                }
    }
}


if (!function_exists("deleteFilesNotListedByArray")) {
    /**
     * deletes all files and folders contained within the path passed which do not match the array for file skipping
     *
     * @param string $dirname
     * @param array $skipped
     *
     * @return array
     */
    function deleteFilesNotListedByArray($dirname, $skipped = array())
    {
        $deleted = array();
        foreach(array_reverse(getCompleteFilesListAsArray($dirname)) as $file)
        {
            $found = false;
            foreach($skipped as $skip)
                if (strtolower(substr($file, strlen($file)-strlen($skip)))==strtolower($skip))
                    $found = true;
                    if ($found == false)
                    {
                        if (unlink($file))
                        {
                            $deleted[str_replace($dirname, "", dirname($file))][] = basename($file);
                            rmdir(dirname($file));
                        }
                    }
        }
        return $deleted;
    }
    
}

if (!function_exists("getCompleteFilesListAsArray")) {
    /**
     * Get a complete file listing for a folder and sub-folder
     *
     * @param string $dirname
     * @param string $remove
     *
     * @return array
     */
    function getCompleteFilesListAsArray($dirname, $remove = '')
    {
        foreach(getCompleteDirListAsArray($dirname) as $path)
            foreach(getFileListAsArray($path) as $file)
                $result[str_replace($remove, '', $path.DIRECTORY_SEPARATOR.$file)] = str_replace($remove, '', $path.DIRECTORY_SEPARATOR.$file);
                return $result;
    }
    
}


if (!function_exists("getCompleteDirListAsArray")) {
    /**
     * Get a complete folder/directory listing for a folder and sub-folder
     *
     * @param string $dirname
     * @param array $result
     *
     * @return array
     */
    function getCompleteDirListAsArray($dirname, $result = array())
    {
        $result[$dirname] = $dirname;
        foreach(getDirListAsArray($dirname) as $path)
        {
            $result[$dirname . DIRECTORY_SEPARATOR . $path] = $dirname . DIRECTORY_SEPARATOR . $path;
            $result = getCompleteDirListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
        }
        return $result;
    }
    
}

if (!function_exists("getCompleteZipListAsArray")) {
    /**
     * Get a complete zip archive for a folder and sub-folder
     *
     * @param string $dirname
     * @param array $result
     *
     * @return array
     */
    function getCompleteZipListAsArray($dirname, $result = array())
    {
        foreach(getCompleteDirListAsArray($dirname) as $path)
        {
            foreach(getZipListAsArray($path) as $file)
                $result[md5_file($path . DIRECTORY_SEPARATOR . $file)] =  $path . DIRECTORY_SEPARATOR . $file;
        }
        return $result;
    }
}


if (!function_exists("getCompletePacksListAsArray")) {
    /**
     * Get a complete all packed archive supported for a folder and sub-folder
     *
     * @param string $dirname
     * @param array $result
     *
     * @return array
     */
    function getCompletePacksListAsArray($dirname, $result = array())
    {
        foreach(getCompleteDirListAsArray($dirname) as $path)
        {
            foreach(getPacksListAsArray($path) as $file=>$values)
                $result[$values['type']][md5_file( $path . DIRECTORY_SEPARATOR . $values['file'])] =  $path . DIRECTORY_SEPARATOR . $values['file'];
        }
        return $result;
    }
}

if (!function_exists("getCompleteFontsListAsArray")) {
    /**
     * Get a complete all font files supported for a folder and sub-folder
     *
     * @param string $dirname
     * @param array $result
     *
     * @return array
     */
    function getCompleteFontsListAsArray($dirname, $result = array())
    {
        foreach(getCompleteDirListAsArray($dirname) as $path)
        {
            foreach(getFontsListAsArray($path) as $file=>$values)
                $result[$values['type']][md5_file($path . DIRECTORY_SEPARATOR . $values['file'])] = $path . DIRECTORY_SEPARATOR . $values['file'];
        }
        return $result;
    }
}

if (!function_exists("getDirListAsArray")) {
    /**
     * Get a folder listing for a single path no recursive
     *
     * @param string $dirname
     *
     * @return array
     */
    function getDirListAsArray($dirname)
    {
        $ignored = array(
            'cvs' ,
            '_darcs', '.git', '.svn');
        $list = array();
        if (substr($dirname, - 1) != '/') {
            $dirname .= '/';
        }
        if ($handle = opendir($dirname)) {
            while ($file = readdir($handle)) {
                if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored))
                    continue;
                    if (is_dir($dirname . $file)) {
                        $list[$file] = $file;
                    }
            }
            closedir($handle);
            asort($list);
            reset($list);
        }
        return $list;
    }
}

if (!function_exists("getFileListAsArray")) {
    /**
     * Get a file listing for a single path no recursive
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    function getFileListAsArray($dirname, $prefix = '')
    {
        $filelist = array();
        if (substr($dirname, - 1) == '/') {
            $dirname = substr($dirname, 0, - 1);
        }
        if (is_dir($dirname) && $handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (! preg_match('/^[\.]{1,2}$/', $file) && is_file($dirname . '/' . $file)) {
                    $file = $prefix . $file;
                    $filelist[$file] = $file;
                }
            }
            closedir($handle);
            asort($filelist);
            reset($filelist);
        }
        return $filelist;
    }
}

if (!function_exists("getZipListAsArray")) {
    /**
     * Get a zip file listing for a single path no recursive
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    function getZipListAsArray($dirname, $prefix = '')
    {
        $filelist = array();
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (preg_match('/(\.zip)$/i', $file)) {
                    $file = $prefix . $file;
                    $filelist[$file] = $file;
                }
            }
            closedir($handle);
            asort($filelist);
            reset($filelist);
        }
        return $filelist;
    }
}

if (!function_exists("get7zListAsArray")) {
    /**
     * Get a zip file listing for a single path no recursive
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    function get7zListAsArray($dirname, $prefix = '')
    {
        $filelist = array();
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                if (preg_match('/(\.7z)$/i', $file)) {
                    $file = $prefix . $file;
                    $filelist[$file] = $file;
                }
            }
            closedir($handle);
            asort($filelist);
            reset($filelist);
        }
        return $filelist;
    }
}


if (!function_exists("getPacksListAsArray")) {
    /**
     * Get a compressed archives file listing for a single path no recursive
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    function getPacksListAsArray($dirname, $prefix = '')
    {
        $packs = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-converted.diz'));
        $filelist = array();
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                foreach($packs as $pack)
                    if (substr(strtolower($file), strlen($file)-strlen(".".$pack)) == strtolower(".".$pack)) {
                        $file = $prefix . $file;
                        $filelist[$file] = array('file'=>$file, 'type'=>$pack);
                    }
            }
            closedir($handle);
        }
        return $filelist;
    }
}


if (!function_exists("getFontsListAsArray")) {
    /**
     * Get a font files listing for a single path no recursive
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    function getFontsListAsArray($dirname, $prefix = '')
    {
        $formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'font-converted.diz'));
        $filelist = array();
        
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                foreach($formats as $format)
                    if (substr(strtolower($file), strlen($file)-strlen(".".$format)) == strtolower(".".$format)) {
                        $file = $prefix . $file;
                        $filelist[$file] = array('file'=>$file, 'type'=>$format);
                    }
            }
            closedir($handle);
        }
        return $filelist;
    }
}

if (!function_exists("getStampingShellExec")) {
    /**
     * Get a bash shell execution command for stamping archives
     *
     * @return array
     */
    function getStampingShellExec()
    {
        $ret = array();
        foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-stamping.diz')) as $values)
        {
            $parts = explode("||", $values);
            $ret[$parts[0]] = $parts[1];
        }
        return $ret;
    }
}

if (!function_exists("getArchivingShellExec")) {
    /**
     * Get a bash shell execution command for creating archives
     *
     * @return array
     */
    function getArchivingShellExec()
    {
        $ret = array();
        foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-archiving.diz')) as $values)
        {
            $parts = explode("||", $values);
            $ret[$parts[0]] = $parts[1];
        }
        return $ret;
    }
}

if (!function_exists("getExtractionShellExec")) {
    /**
     * Get a bash shell execution command for extracting archives
     *
     * @return array
     */
    function getExtractionShellExec()
    {
        $ret = array();
        foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-extracting.diz')) as $values)
        {
            $parts = explode("||", $values);
            $ret[$parts[0]] = $parts[1];
        }
        return $ret;
    }
}


if (!class_exists("XmlDomConstruct")) {
    /**
     * class XmlDomConstruct
     *
     * 	Extends the DOMDocument to implement personal (utility) methods.
     *
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     */
    class XmlDomConstruct extends DOMDocument {
        
        /**
         * Constructs elements and texts from an array or string.
         * The array can contain an element's name in the index part
         * and an element's text in the value part.
         *
         * It can also creates an xml with the same element tagName on the same
         * level.
         *
         * ex:
         * <nodes>
         *   <node>text</node>
         *   <node>
         *     <field>hello</field>
         *     <field>world</field>
         *   </node>
         * </nodes>
         *
         * Array should then look like:
         *
         * Array (
         *   "nodes" => Array (
         *     "node" => Array (
         *       0 => "text"
         *       1 => Array (
         *         "field" => Array (
         *           0 => "hello"
         *           1 => "world"
         *         )
         *       )
         *     )
         *   )
         * )
         *
         * @param mixed $mixed An array or string.
         *
         * @param DOMElement[optional] $domElement Then element
         * from where the array will be construct to.
         *
         * @author 		Simon Roberts (Chronolabs) simon@labs.coop
         *
         */
        public function fromMixed($mixed, DOMElement $domElement = null) {
            
            $domElement = is_null($domElement) ? $this : $domElement;
            
            if (is_array($mixed)) {
                foreach( $mixed as $index => $mixedElement ) {
                    
                    if ( is_int($index) ) {
                        if ( $index == 0 ) {
                            $node = $domElement;
                        } else {
                            $node = $this->createElement($domElement->tagName);
                            $domElement->parentNode->appendChild($node);
                        }
                    }
                    
                    else {
                        $node = $this->createElement($index);
                        $domElement->appendChild($node);
                    }
                    
                    $this->fromMixed($mixedElement, $node);
                    
                }
            } else {
                $domElement->appendChild($this->createTextNode($mixed));
            }
            
        }
        
    }
}
