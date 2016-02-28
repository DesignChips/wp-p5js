<?php
/*
Plugin Name: wp-p5js
Plugin URI:
Description: enable p5.js
Version: 1.0.0
Author: DesignChips
Author URI: http://www.designchips.net
License: GPL2
*/

/*  Copyright 2016 DesignChips (email : info@designchips.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'P5JS__VERSION', '1.0.0' );
define( 'P5JS__MINIMUM_WP_VERSION', '4.4' );
define( 'P5JS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'P5JS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'P5JS__DELETE_LIMIT', 100000 );

function p5js_enqueue_scripts() {
  wp_enqueue_script('p5js', P5JS__PLUGIN_URL . 'p5.min.js');
}
add_action('wp_enqueue_scripts', 'p5js_enqueue_scripts');

function p5js_admin_enqueue_scripts() {
  wp_enqueue_script('p5js-ace-editor', P5JS__PLUGIN_URL . '/ace-builds/src-noconflict/ace.js');
  wp_enqueue_script('p5js-app', P5JS__PLUGIN_URL . 'app.js', array('p5js-ace-editor'));
}
add_action('admin_enqueue_scripts', 'p5js_admin_enqueue_scripts');

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function p5js_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'myplugin_sectionid',
			__( 'p5.js script', 'myplugin_textdomain' ),
			'p5js_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'p5js_add_meta_box' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function p5js_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'p5js_save_meta_box_data', 'p5js_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
  $p5js_script = get_post_meta( $post->ID, 'p5js-script', true );

  // echo '<label for="p5js-script">';
  // _e( 'p5js-script for this field', 'myplugin_textdomain' );
  // echo '</label> ';
  echo '<div id="editor" style="width:100%; height:300px;">';
  echo $p5js_script;
  echo '</div>';
  echo '<input type="hidden" id="p5js-script" name="p5js-script" value="">';
  echo '<div id="p5Canvas"><div>';

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function p5js_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['p5js_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['p5js_meta_box_nonce'], 'p5js_save_meta_box_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */

	// Make sure that it is set.
	if ( ! isset( $_POST['p5js-script'] ) ) {
		return;
	}

  $p5js_script = isset( $_POST['p5js-script'] ) ? $_POST['p5js-script'] : '';

	// Sanitize user input.
  $my_p5js_script = $_POST['p5js-script'];

	// Update the meta field in the database.
  update_post_meta( $post_id, 'p5js-script', $my_p5js_script );

}
add_action( 'save_post', 'p5js_save_meta_box_data' );


// function featured_image_before_content( $content ) {
//    if ( is_singular('post') ) {
//        $pre = '<div><script type="text/javascript">';
//        $suf = '</script></div>';
//
//        $p5js_script = $pre . get_post_meta( get_the_ID(), 'p5js-script', true ) . $suf;
//
//        $content = $p5js_script . $content;
//    }
//    return $content;
// }
// add_filter( 'the_content', 'featured_image_before_content' );


function p5js_shortcode( $atts ){
  $pre = '<div><script type="text/javascript">';
  $suf = '</script></div>';
  $p5js_script = $pre . get_post_meta( get_the_ID(), 'p5js-script', true ) . $suf;
	return $p5js_script;
}
add_shortcode( 'p5js', 'p5js_shortcode' );
