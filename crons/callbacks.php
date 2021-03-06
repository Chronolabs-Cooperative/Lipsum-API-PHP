<?php
/**
 * Chronolabs Fontages API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers FROM this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         fonts
 * @since           1.0.2
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		cronjobs
 * @description		Screening API Service REST
 */


 //   Scheduled Cron Job Details.,
 //   Execute:- 
 //   
 //   $ sudo crontab -e
 //   
 //   CronTab Entry:
 //   
 //   */1 * * * * /usr/bin/php -q /path/to/cronjobs/callbacks.php


$seconds = floor(mt_rand(1, floor(60 * 0.95)));
set_time_limit($seconds ^ 4);
sleep($seconds);

ini_set('display_errors', true);
ini_set('log_errors', true);
error_reporting(E_ERROR);
define('MAXIMUM_QUERIES', 25);
ini_set('memory_limit', '315M');
include_once dirname(__DIR__).'/constants.php';
$GLOBALS['APIDB']->queryF($sql = "START TRANSACTION");
$result = $GLOBALS['APIDB']->queryF($sql = "SELECT * FROM `" . $GLOBALS['APIDB']->prefix('callbacks') . "` WHERE `when` <= unix_timestamp() AND `fails` < 5 ORDER BY `when` ASC");
while ($row = $GLOBALS['APIDB']->fetchArray($result))
{
	$success = false;
	$data = json_decode($row['data'], true);
	$queries = json_decode($row['queries'], true);
	
	if (isset($queries['before']) && !empty($queries['before']))
		if (is_array($queries['before']))
			foreach($queries['before'] as $question)
				$GLOBALS['APIDB']->queryF($question);
		elseif (is_string($queries['before']))
			$GLOBALS['APIDB']->queryF($queries['before']);
	
	setTimeLimit($row['timeout']+$row['connection']+25);
			
	if (!function_exists("curl_init"))
	{
		if (strlen(file_get_contents($uri)) > 0)
			$success = true;
	} elseif (!$btt = curl_init($row['uri'])) {
		$success = false;
	} 
	if ($btt)
	{
		curl_setopt($btt, CURLOPT_HEADER, 0);
		curl_setopt($btt, CURLOPT_POST, (count($data)==0?false:true));
		if (count($data)!=0)
			curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $row['connection']);
		curl_setopt($btt, CURLOPT_TIMEOUT, $row['timeout']);
		curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($btt, CURLOPT_VERBOSE, false);
		curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
		@curl_exec($btt);
		if (curl_getinfo($btt, CURLINFO_HTTP_CODE) == 200)
			$success = true;
		curl_close($btt);
	}
	if ($success != false)
	{

		if (isset($queries['success']) && !empty($queries['success']))
			if (is_array($queries['success']))
				foreach($queries['success'] as $question)
					$GLOBALS['APIDB']->queryF($question);
			elseif (is_string($queries['success']))
				$GLOBALS['APIDB']->queryF($queries['success']);
		$GLOBALS['APIDB']->queryF($sql = "DELETE FROM `" . $GLOBALS['APIDB']->prefix('callbacks') . "` WHERE `when` = '".$row['when']."' AND `uri` LIKE '".$row['uri']."'");
	} else {

		if (isset($queries['failed']) && !empty($queries['failed']))
			if (is_array($queries['failed']))
				foreach($queries['failed'] as $question)
					$GLOBALS['APIDB']->queryF($question);
			elseif (is_string($queries['failed']))
				$GLOBALS['APIDB']->queryF($queries['failed']);
			
		$GLOBALS['APIDB']->queryF($sql = "UPDATE `" . $GLOBALS['APIDB']->prefix('callbacks') . "` SET `fails` = `fails` + 1 WHERE `when` = '".$row['when']."' AND `uri` LIKE '".$row['uri']."'");
	}
}
$GLOBALS['APIDB']->queryF($sql = "DELETE FROM `" . $GLOBALS['APIDB']->prefix('callbacks') . "` WHERE `fails` >= '5'");
$GLOBALS['APIDB']->queryF($sql = "COMMIT");
exit(0);
?>