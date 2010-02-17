<?php
/*
Plugin Name: TalkAhead Sponsored Comments
Plugin URI: http://www.talkahead.com
Description: Add Sponsored comments to your blog
Version: 1.0.3
Author: TalkAhead
Author URI: http://www.talkahead.com
*/

$th_pi_directory = '';
if( basename( dirname( __FILE__) ) == 'mu-plugins' )
	$th_pi_directory = 'TalkAhead_plugin/';



$talkahead_plugin_version = "1.0.3";



function talkahead_globals_init(){
	if ( ! defined( 'WP_CONTENT_URL' ) )
		define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) )
		define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( ! defined( 'WP_PLUGIN_URL' ) )
		define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( ! defined( 'WP_PLUGIN_DIR' ) )
		define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR. '/plugins' );
}


function getVersion(){
	 global $talkahead_plugin_version;
	 return $talkahead_plugin_version;
}

// display the plugin
function talkahead_display ($content)
{
	global $post_ID, $talkahead_plugin_version, $html ;

	{
		$content .= '<!--  start TalkAhead code -->
		<script type=\'text/javascript\'>
		if (typeof TH_position == \'undefined\'){
			TH_position=0;
			TH_articles = new Array();
		} else {
			TH_position++;
		}
		document.write("<div id=\'TH_div_"+TH_position+"\'></div>");
		  TH_articles[TH_position] = {
			  article: "' . get_permalink( $post_ID ) . '",
			  publisher: "{Fill In your publisher ID here}",
			  category: "default channel",
			  url: document.location,

			  load: function() {
				headID = document.getElementsByTagName("head")[0];
				newScript = document.createElement(\'script\');
				newScript.type = \'text/javascript\';
				newScript.src = \'http://server.talkahead.com/scripts/thwidget/th.js\';
				headID.appendChild(newScript);
			  }
			};
			if (TH_position==0){
			  TH_articles[TH_position].load();
			}
		</script>
		<!--  end TalkAhead code -->
		';
	}
	return $content;
}



add_filter('the_content'	, 'talkahead_display');

?>
