<?php
/*
Plugin Name: TalkAhead Sponsored Comments
Plugin URI: http://www.talkahead.com
Description: Add Sponsored comments to your blog
Version: 1.0.4
Author: TalkAhead
Author URI: http://www.talkahead.com
*/

$th_pi_directory = '';
if( basename( dirname( __FILE__) ) == 'mu-plugins' )
	$th_pi_directory = 'TalkAhead_plugin/';



$talkahead_plugin_version = "1.0.4";



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
	if(!is_feed() && !is_page("about") && is_single())
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
			  publisher: "'. get_option('publisher_account') .'",
			  category: "'. get_option('channel') .'",
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


function talkahead_plugin_menu() {
  add_options_page('TalkAhead Options', 'TalkAhead Plugin', 'administrator',  'talkahead-identifier', 'talkahead_plugin_options');
}

function talkahead_plugin_options() {
?>
<div class="wrap">
<h2>TalkAhead Sponsored Comments</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'talkahead-option-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Publisher Account</th>
        <td><input type="text" name="publisher_account" value="<?php echo get_option('publisher_account'); ?>" /></td><td> copy your account ID from the TalkAhead admin site</td>
        </tr>
        <tr valign="top">
        <th scope="row">Channel</th>
        <td><input type="text" name="channel" value="<?php echo get_option('channel'); ?>" /></td><td> Enter a channel from the list you have on the TalkAhead admin site</td>
        </tr>

    </table>

    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php
}
function talkahead_register_settings() { // whitelist options
  register_setting( 'talkahead-option-group', 'publisher_account' );
  register_setting( 'talkahead-option-group', 'channel' );
//  if (get_option('publisher_account') == null){
//    update_option('publisher_account','{PUBLISHER}');
//  }
  if (get_option('channel') == null){
    update_option('channel','default channel');
  }
}
function talkahead_addaction($links, $file) {
  $this_plugin = plugin_basename ( __FILE__ );
  if ($file == $this_plugin) {
     $links [] = "<a href='options-general.php?page=talkahead-identifier'>Settings</a>";
  }
  return $links;
}

if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'talkahead_plugin_menu');
  add_action( 'admin_init', 'talkahead_register_settings' );
  add_filter ( 'plugin_action_links', 'talkahead_addaction' , -10, 2 ); // add a settings page link to the plugin
} else {
  // non-admin
}

?>
