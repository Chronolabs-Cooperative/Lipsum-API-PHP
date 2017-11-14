<?php
/**
 * Chronolabs Lorem Ipsum Generator Service API
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
 * @package         lipsum
 * @since           1.0.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Checksums/Hashes API Service REST
 * @link			https://screening.labs.coop Screening API Service Operates from this URL
 * @category		control
 * @category		gui
 * @filesource
 */


	
	global $domain, $protocol, $business, $entity, $contact, $referee, $peerings;
	
	if (strlen($theip = whitelistGetIP(true))==0)
		$theip = "127.0.0.1";
	
	$types = array('paragraphs'=>'Paragraphs', 'words'=>'Words', 'bytes'=>'Bytes', 'lists'=>'Lists');
	$modes = array('raw'=>'Raw', 'html'=>'HTML', 'json'=>'Json', 'serial'=>'Serialisation', 'xml'=>'XML');
	$with = array('lorem' => 'start each item opening with Lorem Ipsum',  'any' => 'open each item starting with anything; not with Lorem Ipsum');
	$wkeys = array_keys($with);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta property="og:title" content="<?php echo API_VERSION; ?>"/>
<meta property="og:type" content="api<?php echo API_TYPE; ?>"/>
<meta property="og:image" content="<?php echo API_URL; ?>/assets/images/logo_500x500.png"/>
<meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:site_name" content="<?php echo API_VERSION; ?> - <?php echo API_LICENSE_COMPANY; ?>"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="rating" content="general" />
<meta http-equiv="author" content="wishcraft@users.sourceforge.net" />
<meta http-equiv="copyright" content="<?php echo API_LICENSE_COMPANY; ?> &copy; <?php echo date("Y"); ?>" />
<meta http-equiv="generator" content="Chronolabs Cooperative (<?php echo $place['iso3']; ?>)" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo API_VERSION; ?> || <?php echo API_LICENSE_COMPANY; ?></title>
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
<script type="text/javascript">
  addthis.layers({
	'theme' : 'transparent',
	'share' : {
	  'position' : 'right',
	  'numPreferredServices' : 6
	}, 
	'follow' : {
	  'services' : [
		{'service': 'facebook', 'id': 'Chronolabs'},
		{'service': 'twitter', 'id': 'JohnRingwould'},
		{'service': 'twitter', 'id': 'ChronolabsCoop'},
		{'service': 'twitter', 'id': 'Cipherhouse'},
		{'service': 'twitter', 'id': 'OpenRend'},
	  ]
	},  
	'whatsnext' : {},  
	'recommended' : {
	  'title': 'Recommended for you:'
	} 
  });
</script>
<!-- AddThis Smart Layers END -->
<link rel="stylesheet" href="<?php echo API_URL; ?>/class/apieditor/tinymce/style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/style.css" type="text/css" />
<!-- Custom Fonts -->
<link href="<?php echo API_URL; ?>/assets/media/Labtop/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Superwide Boldish/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Labtop Unicase/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/LHF Matthews Thin/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Life BT Bold Italic/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Bold/style.css" rel="stylesheet" type="text/css">
<link href="<?php echo API_URL; ?>/assets/media/Prestige Elite Normal/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/gradients.php" type="text/css" />
<link rel="stylesheet" href="<?php echo API_URL; ?>/assets/css/shadowing.php" type="text/css" />

</head>
<body>
<div class="main">
	<img style="float: right; margin: 11px; width: auto; height: auto; clear: none;" src="<?php echo API_URL; ?>/assets/images/logo_350x350.png" />
    <h1><?php echo API_VERSION; ?> -- <?php echo API_LICENSE_COMPANY; ?></h1>
    <p>This is an API Service for creating Lorem Ipsum via a URL with GET methods as per normal REST API Services. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
    <h2>Code API Documentation</h2>
    <p>You can find the phpDocumentor code API documentation at the following path :: <a target="_blank" href="<?php echo API_URL; ?>/docs/" target="_blank"><?php echo API_URL; ?>/docs/</a>. These should outline the source code core functions and classes for the API to function!</p>
    <h2>API Responses Available</h2>
    <p>This is a list of the types of lipsums available you would use in the URL path the part in this information just following this paragraph in <em>italics</em>!</p>
    <blockquote>
<?php foreach ($types as $key => $values) { ?>
        <em><strong><?php echo $key; ?></strong></em> - Return a set of <?php echo $values; ?> to the amount and number of items<br />
<?php } ?>
    </blockquote>
<?php foreach ($modes as $mode => $title) { ?>
    <h2><?php echo $title; ?> Document Output</h2>
    <p>This is done with the <em><?php echo $mode; ?>.api</em> extension at the end of the url.</p>
    <blockquote>
<?php foreach ($types as $key => $values) {
	$openkey = $wkeys[mt_rand(0, count($wkeys)-1)];
	switch ($key)
	{
		case 'paragraphs':
			$amount = mt_rand(1,7);
			break;
		default:
			$amount = mt_rand(19, 87);
			break;
	}
	$items = mt_rand(5, 23); ?>
    	<font color="#009900">This is for returning a set of <?php echo $items; ?> seperate items of  <?php echo $values; ?> containing <em>'<?php echo $amount; ?>'</em> in each items which <?php echo $with[$openkey]; ?>.</font><br/>
        <em><strong><a target="_blank" href="<?php echo API_URL; ?>/v2/<?php echo $key; ?>/<?php echo $openkey; ?>/<?php echo $amount; ?>/<?php echo $items; ?>/<?php echo $mode; ?>.api"><?php echo API_URL; ?>/v2/<?php echo $key; ?>/<?php echo $openkey; ?>/<?php echo $amount; ?>/<?php echo $items; ?>/<?php echo $mode; ?>.api</a></strong></em><br /><br />
<?php } ?>
    </blockquote>
    <?php } ?>
    <!-- 
    <h2>Email Document Output</h2>
    <p>This is done with the <em>email.api</em> extension at the end of the url.</p>
    <blockquote>
    	<?php echo $form = getHTMLForm('emails'); ?>
    </blockquote>
    <h3>HTML Code for form above!</h3>
    <pre style="overflow: scroll; height: 445px;">
    	<?php echo htmlspecialchars($form); ?>
   	</pre>
   	 -->
    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2017 and is part of the Chronolabs System and api's.<br/><br/>This is open source which you can download from <a href="https://sourceforge.net/projects/chronolabsapis/">https://sourceforge.net/projects/chronolabsapis/</a> contact the scribe  <a href="mailto:wishcraft@users.sourceforge.net">wishcraft@users.sourceforge.net</a></p></body>
    <p>You can also get this off our github.com at the following path: <a href="https://github.com/Chronolabs-Cooperative/Lipsum-API-PHP/">https://github.com/Chronolabs-Cooperative/Lipsum-API-PHP</a></p></body>
</div>
</html>
<?php 
