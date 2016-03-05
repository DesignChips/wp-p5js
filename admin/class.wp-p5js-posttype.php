<?php
/**
 *
 */
class WP_p5js_PostType
{

  function __construct() {
    # code...
  }

  public static function init() {
    add_action( 'add_meta_boxes', array('WP_p5js_PostType', 'p5js_add_meta_box') );
    add_action( 'save_post', array('WP_p5js_PostType', 'p5js_save_meta_box_data') );

  }

  public static function p5js_create_post_type() {
    register_post_type( 'p5js',
      array(
        'labels' => array(
          'name' => __( 'p5js' ),
          'singular_name' => __( 'p5js' )
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 5,
      )
    );
    remove_post_type_support( 'p5js', 'editor' );
  }

  /**
   * Adds a box to the main column on the Post and Page edit screens.
   */
  public static function p5js_add_meta_box() {
    $screens = array( 'p5js' );
    foreach ( $screens as $screen ) {
      add_meta_box(
        'wp-p5js-metabox', __( 'p5.js script', 'myplugin_textdomain' ),
        array( 'WP_p5js_PostType', 'p5js_meta_box_callback'), $screen
      );
    }
  }

  /**
   * Prints the box content.
   *
   * @param WP_Post $post The object for the current post/page.
   */
  public static function p5js_meta_box_callback( $post ) {

  	// Add a nonce field so we can check for it later.
  	wp_nonce_field( 'p5js_save_meta_box_data', 'p5js_meta_box_nonce' );

  	/*
  	 * Use get_post_meta() to retrieve an existing value
  	 * from the database and use the value for the form.
  	 */
    $p5js_html = get_post_meta( $post->ID, 'p5js-html', true );
    $p5js_css = get_post_meta( $post->ID, 'p5js-css', true );
    $p5js_setup = get_post_meta( $post->ID, 'p5js-setup', true );
    $p5js_draw = get_post_meta( $post->ID, 'p5js-draw', true );

    echo '<input type="hidden" id="p5js-html" name="p5js-html" value="">';
    echo '<input type="hidden" id="p5js-css" name="p5js-css" value="">';
    echo '<input type="hidden" id="p5js-setup" name="p5js-setup" value="">';
    echo '<input type="hidden" id="p5js-draw" name="p5js-draw" value="">';
  }

  /**
   * When the post is saved, saves our custom data.
   *
   * @param int $post_id The ID of the post being saved.
   */
  public static function p5js_save_meta_box_data( $post_id ) {

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
  	// if ( ! isset( $_POST['p5js-html'] ) ) {
  	// 	return;
  	// }

    $p5js_html = isset( $_POST['p5js-html'] ) ? $_POST['p5js-html'] : '';
    $p5js_css = isset( $_POST['p5js-css'] ) ? $_POST['p5js-css'] : '';
    $p5js_setup = isset( $_POST['p5js-setup'] ) ? $_POST['p5js-setup'] : '';
    $p5js_draw = isset( $_POST['p5js-draw'] ) ? $_POST['p5js-draw'] : '';

  	// Sanitize user input.
    $my_p5js_html = $_POST['p5js-html'];
    $my_p5js_css = $_POST['p5js-css'];
    $my_p5js_setup = $_POST['p5js-setup'];
    $my_p5js_draw = $_POST['p5js-draw'];

    $p5js_html = get_post_meta($post_id, 'p5js-html', true );
    if ( empty($p5js_html) ) {
      add_post_meta( $post_id, 'p5js-html', $my_p5js_html);
    } else {
      update_post_meta( $post_id, 'p5js-html', $my_p5js_html );
    }

    $p5js_css = get_post_meta($post_id, 'p5js-css', true );
    if ( empty($p5js_css) ) {
      add_post_meta( $post_id, 'p5js-css', $my_p5js_css);
    } else {
      update_post_meta( $post_id, 'p5js-css', $my_p5js_css );
    }

    $p5js_setup = get_post_meta($post_id, 'p5js-setup', true );
    if ( empty($p5js_setup) ) {
      add_post_meta( $post_id, 'p5js-setup', $my_p5js_setup);
    } else {
      update_post_meta( $post_id, 'p5js-setup', $my_p5js_setup );
    }

    $p5js_draw = get_post_meta($post_id, 'p5js-draw', true );
    if ( empty($p5js_draw) ) {
      add_post_meta( $post_id, 'p5js-draw', $my_p5js_draw);
    } else {
      update_post_meta( $post_id, 'p5js-draw', $my_p5js_draw );
    }

    $p5js_shortcode_title = get_the_title( $post_id );
    $shortcode = get_post_meta($post_id, '_p5js_shortcode', true );
    if ( empty($shortcode) ) {
      add_post_meta( $post_id, '_p5js_shortcode', "[p5js id='{$post_id}' title='{$p5js_shortcode_title}']", true);
    } else {
      update_post_meta( $post_id, '_p5js_shortcode', "[p5js id='{$post_id}' title='{$p5js_shortcode_title}']");
    }
  }


}
