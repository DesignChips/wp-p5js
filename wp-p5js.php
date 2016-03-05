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

require_once P5JS__PLUGIN_DIR . 'admin/class.wp-p5js-script.php';
add_action( 'init', array( 'WP_p5js_Script', 'init') );

require_once P5JS__PLUGIN_DIR . 'admin/class.wp-p5js-posttype.php';
add_action( 'init', array('WP_p5js_PostType', 'p5js_create_post_type') );
add_action( 'init', array( 'WP_p5js_PostType', 'init') );

require_once P5JS__PLUGIN_DIR . 'admin/class.wp-p5js-shortcode.php';
add_action( 'init', array( 'WP_p5js_Shortcode', 'init') );

/**
 * Preset edit single column
 * @param  [type] $result [description]
 * @param  [type] $option [description]
 * @param  [type] $user   [description]
 * @return [type]         [description]
 */
function screen_layout_p5js($result, $option, $user){
  return 1;
}
add_filter('get_user_option_screen_layout_p5js', 'screen_layout_p5js', 10, 3);

function hk_functions_screen_options_show_screen( $show_screen ) {
  $current_screen = get_current_screen();
  if ( 'p5js' == $current_screen->post_type && 'post' == $current_screen->base ) {
    return false;
	} else {
    return $show_screen;
  }
}
add_filter( 'screen_options_show_screen', 'hk_functions_screen_options_show_screen' );

/**
 *
 */
 function add_p5js_columns($columns) {
     unset($columns['author']);
     return array_merge(
               $columns,
               array('shortcode' => __('Shortcode'),)
            );
 }
 add_filter('manage_p5js_posts_columns' , 'add_p5js_columns');

 function set_p5js_columns($columns) {
     return array(
         'cb' => '<input type="checkbox" />',
         'title' => __('Title'),
         'shortcode' => __('Shortcode'),
         'date' => __('Date'),
     );
 }
 add_filter('manage_p5js_posts_columns' , 'set_p5js_columns');

 /* Display custom column */
 function display_p5js_column( $column, $post_id ) {
     if ($column == 'shortcode'){
       printf(
         '<span class="shortcode onfocus="this.select();">
             <input type="text" id="p5js-shortcode" readonly="readonly" onfocus="this.select();" class="large-text code p5js-manage" value="%s">
           </span>',
         esc_attr( get_post_meta( $post_id, '_p5js_shortcode', true ) )
       );
     }
 }
 add_action( 'manage_posts_custom_column' , 'display_p5js_column', 10, 2 );

/**
 * No display permalink
 */
add_filter( 'get_sample_permalink_html', '__return_false' );

function featured_image_before_content( $content ) {
   if ( is_singular('p5js') ) {
       $pre = '<div><script type="text/javascript">';
       $suf = '</script></div>';

       $p5js_script = $pre . get_post_meta( get_the_ID(), 'p5js-script', true ) . $suf;

       $content = $p5js_script . $content;
   }
   return $content;
}
add_filter( 'the_content', 'featured_image_before_content' );
