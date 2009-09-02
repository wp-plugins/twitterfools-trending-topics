=== Plugin Name ===
Twitterfools Trending Topics
Contributors: Twitterfools.com
Donate link: http://www.twitterfools.com/
Tags: Twitter, Trending Topics, Twitter Trending Topics, Twitterfools
Requires at least: 2.8.0
Tested up to: 2.8.4
Stable tag: 1.0.1

This is a simple plugin that adds a widget with a list of the Twitter Trending Topics

== Description ==

This plugin allows you to add a sidebar widget to your site that displays Twitter's trending topics.  
Unlike some other Twitter Plugins, this one caches Twitter data, and provides you with a cache timeout option that 
lets you comply with Twitter API rate limiting. 

Homepage and Support: http://www.twitterfools.com/plugins/wordpress-plugin-twitterfools-trending-topics/

Follow us on Twitter: http://www.twitter.com/twitfools

Requirements:

PHP 5.x

WordPress 2.5+

MySQL 5.x

== Installation ==

Simple Installation: Install the plug-in from your Wordpress console and activate.

Manual Installation is also very simple

1. Download a copy of the twitterfools-trending-topics zip file
2. Unpack and upload the twitterfools-trending-topics folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
5. Add the trending topics to your sidebar or other area using the Appearance->Widgets dashboard
5. Enter a title and a cache timeout value in the widget instance. The cache time out values are 
in seconds.  If omitted, results will be cached for 300 seconds (5 minutes).

== Screenshots ==

1. A view of the widget form.

== Frequently Asked Questions ==

= Why are you caching Twitter Data? =

Twitter has a rate limit of 150 API calls per hour for each IP address when those calls are anonymous.  We cache
the reults of the Twitter trending topics call for an amount of time that is configurable in the 
plugin dialog.  This lets us get around the rate limit issue, as well as improve performace by retrieving the 
topics from a local cache. 

= What are Trending Topics =

Twitter is a near real-time messaging community, and trending topics are things that many people are currently
tweeting about.  In some cases these topics indicate breaking news or popular ideas of the moment.  In other cases
they're still popular, since many people are tweeting them, but the reasons behind why a specific topic begins
to trend are often a mystery.


== Changelog ==

= 1.0.1 =
* Migrated to the WordPress 2.8 WP_Plugin API

= 1.0.0 =
* Initial production release on Twitterfools.com
