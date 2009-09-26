<?php
// proxy.php - used with Twitterfools Trending topics Plugin
// to pull topic descriptions from WhatTheTrend.com
// see the plugin file for more details and license info

$topic = $_REQUEST['topic'];
$path = $_REQUEST['path'];
$id = $_REQUEST['id'];
$topic = urlencode($topic);

$wttURL = "http://www.whatthetrend.com/api/trend/getByName/" . $topic . "/xml";
$feed_object = simplexml_load_file($wttURL, 'SimpleXMLElement', LIBXML_NOCDATA);

			if ( $feed_object != null ){
				$descriptionText = parseText($feed_object->trend->blurb->text);
				echo "<input type=\"image\" src=\"". $path . "up.png\" alt=\"Hide Description\" height=\"16\" width=\"16\" onClick=\"collapseTTTDescription('". $id ."')\" style='padding-right:3px;vertical-align:middle;'/>";
				echo $descriptionText;
			}

			
	/**
	 * This is a simple method to clean up the text
	 * this should make embedded urls, @names, and #hashtags clickable
	 */
	function parseText($text) {
			
		$arr = explode(" ", $text);

		// replace embedded urls
		$arr2 = preg_replace("/(http:\/\/[^\s]+)/", "<a href=\"$1\" target='_blank' rel='nofollow'>$1</a>", $arr);

		// replace embedded @names
		$pattern = "/[@]+[A-Za-z0-9-_]+/";
		$arr2 = preg_replace($pattern, "<a href=\"http://twitter.com/$0\" target='_blank' rel='nofollow'>$0</a>", $arr2);
		$arr2 = preg_replace("/twitter.com\/@/", "//twitter.com/", $arr2);

		//replace embedded #hashtags
		$pattern="/[#]+[A-Za-z0-9-_]+/";
		$arr2 = preg_replace($pattern, "<a href=\"http://twitter.com/search?q=$0\" target='_blank' rel='nofollow'>$0</a>", $arr2);
		$arr2 = preg_replace("/q=#/", "q=%23", $arr2);

		return implode(" ", $arr2);
	}			
?>
