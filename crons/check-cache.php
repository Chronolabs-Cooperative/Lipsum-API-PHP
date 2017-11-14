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

$seconds = floor(mt_rand(1, floor(60 * 4.75)));
set_time_limit($seconds ^ 4);
sleep($seconds);

ini_set('display_errors', true);
ini_set('log_errors', true);
error_reporting(E_ERROR);
define('MAXIMUM_QUERIES', 25);
ini_set('memory_limit', '128M');
include_once dirname(__DIR__).'/constants.php';
error_reporting(E_ERROR);
set_time_limit(7200);


@writeRawFile(FONT_RESOURCES_RESOURCE_RESOURCE . DIRECTORY_SEPARATOR . 'nodes-all.json', json_encode(getNodesListArray('all', 'json', 'cron', 'v2')));
@writeRawFile(FONT_RESOURCES_RESOURCE_RESOURCE . DIRECTORY_SEPARATOR . 'fonts-all.json', json_encode(getFontsListArray('all', 'json', 'cron', 'v2')));

//die("TMP Break-point!");
$result = $GLOBALS['APIDB']->queryF("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('fonts') . "` WHERE `medium` = 'FONT_RESOURCES_RESOURCE' ORDER BY RAND() LIMIT 36");
while($row = $GLOBALS['APIDB']->fetchArray($result))
{
	sleep(mt_rand(7,14));
	$archive = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('fonts_archiving') . "` WHERE `font_id` = '" . $row['id'] . "'"));
	if (API_REPOSITORY == 'svn')
		if (sha1_file(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']) == sha1(file_get_contents(sprintf(constant("FONT_RESOURCES_RESOURCE_STORE"), $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']))))
		{
			$GLOBALS['APIDB']->queryF("UPDATE `" . $GLOBALS['APIDB']->prefix('fonts') . "` SET `medium` = 'FONT_RESOURCES_CACHE' WHERE `id` = '" . $row['id'] . "'");
			$GLOBALS['APIDB']->queryF("UPDATE `" . $GLOBALS['APIDB']->prefix('fonts_archiving') . "` SET `unlocalised` = UNIX_TIMSTAMP(), `unlocalisation` = `unlocalisation` + 1 WHERE `id` = '" . $archive['id'] . "'");
			unlink(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']);
			$path = constant("FONT_RESOURCES_RESOURCE") . $archive['path'];
			foreach(explode(DIRECTORY_SEPARATOR, $archive['path']) as $folder)
			{
				rmdir($path);
				$path = dirname($path);
			}
		}
	elseif (API_REPOSITORY == 'git')
		if (sha1_file(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']) == sha1(file_get_contents(sprintf(constant("FONT_RESOURCES_RESOURCE_STORE_GIT"), $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']))))
		{
			$GLOBALS['APIDB']->queryF("UPDATE `" . $GLOBALS['APIDB']->prefix('fonts') . "` SET `medium` = 'FONT_RESOURCES_CACHE' WHERE `id` = '" . $row['id'] . "'");
			$GLOBALS['APIDB']->queryF("UPDATE `" . $GLOBALS['APIDB']->prefix('fonts_archiving') . "` SET `unlocalised` = UNIX_TIMSTAMP(), `unlocalisation` = `unlocalisation` + 1 WHERE `id` = '" . $archive['id'] . "'");
			putRawFile(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . '.gitignore', "!/".$archive['filename']);
			unlink(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']);
			$path = constant("FONT_RESOURCES_RESOURCE") . $archive['path'];
			foreach(explode(DIRECTORY_SEPARATOR, $archive['path']) as $folder)
			{
				rmdir($path);
				$path = dirname($path);
			}
		}
	elseif (API_REPOSITORY == 'git,svn')
		if (sha1_file(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']) == sha1(file_get_contents(sprintf(constant("FONT_RESOURCES_RESOURCE_STORE"), $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename'])))  == sha1(file_get_contents(sprintf(constant("FONT_RESOURCES_RESOURCE_STORE_GIT"), $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']))))
		{
			$GLOBALS['APIDB']->queryF("UPDATE `" . $GLOBALS['APIDB']->prefix('fonts') . "` SET `medium` = 'FONT_RESOURCES_CACHE' WHERE `id` = '" . $row['id'] . "'");
			$GLOBALS['APIDB']->queryF("UPDATE `" . $GLOBALS['APIDB']->prefix('fonts_archiving') . "` SET `unlocalised` = UNIX_TIMSTAMP(), `unlocalisation` = `unlocalisation` + 1 WHERE `id` = '" . $archive['id'] . "'");
			putRawFile(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . '.gitignore', "!/".$archive['filename']);
			unlink(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']);
			$path = constant("FONT_RESOURCES_RESOURCE") . $archive['path'];
			foreach(explode(DIRECTORY_SEPARATOR, $archive['path']) as $folder)
			{
				rmdir($path);
				$path = dirname($path);
			}
		}
		
}


$result = $GLOBALS['APIDB']->queryF("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('fonts') . "` WHERE `medium` = 'FONT_RESOURCES_CACHE' ORDER BY RAND() LIMIT 36");
while($row = $GLOBALS['APIDB']->fetchArray($result))
{
	sleep(mt_rand(7,14));
	$archive = $GLOBALS['APIDB']->fetchArray($GLOBALS['APIDB']->queryF("SELECT * FROM `" . $GLOBALS['APIDB']->prefix('fonts_archiving') . "` WHERE `font_id` = '" . $row['id'] . "'"));
	if (file_exists(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']))
	{
		if (API_REPOSITORY == 'svn')
			if (sha1_file(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']) == sha1(file_get_contents(sprintf(constant("FONT_RESOURCES_RESOURCE_STORE"), $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']))))
			{
				unlink(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']);
				$path = constant("FONT_RESOURCES_RESOURCE") . $archive['path'];
				foreach(explode(DIRECTORY_SEPARATOR, $archive['path']) as $folder)
				{
					rmdir($path);
					$path = dirname($path);
				}
			}
		elseif (API_REPOSITORY == 'git')
			if (sha1_file(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']) == sha1(file_get_contents(sprintf(constant("FONT_RESOURCES_RESOURCE_STORE_GIT"), $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']))))
			{
				putRawFile(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . '.gitignore', "!/".$archive['filename']);
				unlink(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']);
				$path = constant("FONT_RESOURCES_RESOURCE") . $archive['path'];
				foreach(explode(DIRECTORY_SEPARATOR, $archive['path']) as $folder)
				{
					rmdir($path);
					$path = dirname($path);
				}
			}
		elseif (API_REPOSITORY == 'git,svn')
			if (sha1_file(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']) == sha1(file_get_contents(sprintf(constant("FONT_RESOURCES_RESOURCE_STORE"), $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']))) == sha1(file_get_contents(sprintf(constant("FONT_RESOURCES_RESOURCE_STORE_GIT"), $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']))))
			{
				putRawFile(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . '.gitignore', "!/".$archive['filename']);
				unlink(constant("FONT_RESOURCES_RESOURCE") . $archive['path'] . DIRECTORY_SEPARATOR . $archive['filename']);
				$path = constant("FONT_RESOURCES_RESOURCE") . $archive['path'];
				foreach(explode(DIRECTORY_SEPARATOR, $archive['path']) as $folder)
				{
					rmdir($path);
					$path = dirname($path);
				}
			}
	}
}
exit(0);


?>
