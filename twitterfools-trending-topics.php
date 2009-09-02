<?php
/**
Plugin Name: TwitterFools Trending Topics
Plugin URI: http://www.twitterfools.com/plugins/wordpress-plugin-twitterfools-trending-topics/
Description: This plugin allows you to add a sidebar widget to your site that displays Twitter's trending topics.  Unlike some other Twitter Plugins, this one caches Twitter data, and provides you with a cache timeout option that lets you comply with Twitter API rate limiting. 
Author: Twitterfools.com - A Member of The Fools Network
Version: 1.0.1
Author URI: http://www.twitterfools.com/

Copyright (C) 2009  The Fools Network

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

For a copy of the GNU General Public License, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

@package TwitterFools_TrendingTopics
@author The Fools Network
@version 1.0.1

*/

class TwitterFools_Trending_Topics extends WP_Widget {

	/** 
	  * Constructor 
	  *
	  */
	function TwitterFools_Trending_Topics() {
		$widget_ops = array('classname' => 'widget_twitterfools_trending_topics', 'description' =>  __('A list of the current Twitter Trending Topics') );
		 parent::WP_Widget(false,  __('Twitterfools Trending Topics'), $widget_ops);
	}
	
 
 	/** 
	  * Render the Widget
	  *
	  */
	function widget($args, $instance) {
		extract($args);
 
		echo $before_widget;
		
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		$cache_timeout = empty($instance['cache_timeout']) ? 300 : apply_filters('widget_cache_timeout', $instance['cache_timeout']);
		$ttt_err = empty($instance['ttt_err']) ? "" : apply_filters('widget_ttt_err', $instance['ttt_err']);
 

		// do a lookup on the topics 
 		$theTopics = $this->getTrendingTopics($cache_timeout);
 
		if ( $title ) {	echo $before_title . $title . $after_title; }
		
		if ( $theTopics ) {
	
			$this->displayTopics( $theTopics );
		} else {
		 	$this->displayError($ttt_err);
		 
		 }

		// for compatibility
		echo $after_widget;
		 
		 
			
	}
 
 	/** 
	  * Saves the widget Options 
	  *
	  */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['cache_timeout'] = strip_tags(stripslashes($new_instance['cache_timeout']));
		$instance['ttt_err'] = strip_tags(stripslashes($new_instance['ttt_err']));

		return $instance;
	}
 
	/**
	  * Creates the edit form for the widget.
	  *
	  */
	  function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'', 'cache_timeout'=>'','ttt_err'=> '' ) );
	
		$title = htmlspecialchars($instance['title']);
		$cache_timeout = htmlspecialchars($instance['cache_timeout']);
		$ttt_err = htmlspecialchars($instance['ttt_err']);
		
		# Output the options
		echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width: 200px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
		# Cache Timeout in seconds
		echo '<p><label for="' . $this->get_field_name('cache_timeout') . '">' . __('Cache Results For (seconds):') . ' <input style="width: 200px;" id="' . $this->get_field_id('cache_timeout') . '" name="' . $this->get_field_name('cache_timeout') . '" type="text" value="' . $cache_timeout . '" /></label></p>';
		
		# Custom Error Message
		echo '<p><label for="' . $this->get_field_name('ttt_err') . '">' . __('Error Message:') . ' <textarea style="width: 200px;height: 150px;" id="' . $this->get_field_id('ttt_err') . '" name="' . $this->get_field_name('ttt_err') . '">' . $ttt_err . '</textarea></label></p>';
			
	
	}


	
	/** 
	  * Main function to get twitter topics - will cache for the timeout period
	  * You want to cache results for at least 30 seconds to stay below Twitter's 
	  * API rate limit of 150 requeste per hour.  
	  * On the other hand, using a low cache time out will mean more frequent calls
	  * out to Twitter.com, which WILL PROBABLY SLOW YOUR SITE DOWN - at least for 
	  * the users unlucky enough to make the requests that require a new external call 
	  * to Twitter.
	  * We use a default timeout of 300 seconds (5 minutes) to strike a balance
	  * between data freshness and performance. 
	  *
	  */
	function getTrendingTopics($cache_timeout = 300) {
		global $wp_query;
		
		// variables
		$last_update = get_option('tfools_lastupdate');
		$update_time = $last_update + $cache_timeout;
		
		// Is it later than last update + cache_timeout?	
		if ($update_time < time())
		{
			/* 
				Yes, so grab and cache new trending topics data
			 	Note we're grabbing json data and are 
				converting it to an array 
			*/
			$uri = 'http://search.twitter.com/trends/current.json';
			$feed_text = file_get_contents($uri);
			update_option('tfools_lastupdate', time());
			$feed_object = json_decode($feed_text);
			$feed_arr = array();

			// if we have a feed, populate feed_array with the data
			if (! is_null($feed_object) && ! is_null($feed_object->trends) ){
			
				foreach ($feed_object->trends as $var){
					foreach ($var as $trend) $feed_arr[] = $trend->query;
				}

				// now - cache the data using the options feature
				update_option('tfools_data', implode('|',$feed_arr));
			
			}
			else {
				return false;
			}
		}
	
		// At this point, we have an options variable containing 
		// the new or cached feed data 
		
		return get_option('tfools_data');
		

	}	
	
	
	/**
		Display the topics
	*/	
	function displayTopics($raw_data){
		
		if ($raw_data)
		{
		
		$topics = explode('|', $raw_data );
		
		echo '<ul>';
			foreach ($topics as $topic){
				echo '<li><a href="http://twitter.com/search?q='.urlencode($topic).'" target="_blank">'.$topic.'</a></li>';
			}
	
		echo '</ul>';
		}
		
	}


	/**
		Display an error message
	*/	
	function displayError($ttt_err=""){
		
		echo '<div>' . $ttt_err . '</div>';
	
	}
	
	
	
}
/**
  * Register TwitterFools Trending Topics widget.
  *
  * Calls 'widgets_init' action after this widget has been registered.
  */
  function TTT_Init() {
  	register_widget('TwitterFools_Trending_Topics');
  }
  
  add_action('widgets_init', 'TTT_Init');

?>