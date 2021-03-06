<?php
/**
 * Plugin Name: Seminar Sessions
 * Plugin Description: Manage Sessions, Speakers, Sponsors and display Schedules for a seminar-type event. Based almost entirely on code
 * from code built for WordCamp.org at https://meta.svn.wordpress.org/sites/trunk/wordcamp.org/
 * Author: H1/Daniel Koskinen
 * Version: 1.0.0
 *
 * Copyright: WordCamp.org, H1 Web Oy
 * Licence: GPLv3
 */

include( "jquery-ui-css/jquery-ui-css.php" );
include( "post-types/post-types.php" );

add_action( 'plugins_loaded', 'seminar_sessions_init' );
/**
 * Load plugin textdomain
 *
 * @since 1.0.0
 */
function seminar_sessions_init() {
	load_plugin_textdomain( 'seminar-sessions', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}