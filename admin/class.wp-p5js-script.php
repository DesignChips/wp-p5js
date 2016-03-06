<?php
/**
 *
 */
class WP_p5js_Script
{

  function __construct()
  {
    # code...
  }

  public static function init()
  {
    add_action('wp_enqueue_scripts', array( 'WP_p5js_Script', 'p5js_enqueue_scripts') );
    add_action('admin_enqueue_scripts', array( 'WP_p5js_Script', 'p5js_admin_enqueue_scripts') );
  }

  public static function p5js_enqueue_scripts() {
    wp_enqueue_script('p5js', P5JS__PLUGIN_URL . '/admin/js/p5.min.js');
  }

  public static function p5js_admin_enqueue_scripts() {
    wp_enqueue_style('p5js-admin', P5JS__PLUGIN_URL . 'admin/css/admin.css');

    $current_screen = get_current_screen();
    if ( 'p5js' == $current_screen->post_type && 'post' == $current_screen->base ) {
        wp_enqueue_script('p5js', P5JS__PLUGIN_URL . '/admin/js/p5.min.js');

        wp_enqueue_script( 'jquery-ui-tabs' );
        wp_enqueue_script('p5js-ace-editor', P5JS__PLUGIN_URL . '/assets/ace-builds/src-noconflict/ace.js');
        wp_enqueue_script('p5js-ace-editor-tool', P5JS__PLUGIN_URL . '/assets/ace-builds/src-noconflict/ext-language_tools.js');
        wp_enqueue_script('p5js-ace-editor-emmet', P5JS__PLUGIN_URL . '/assets/ace-builds/src-noconflict/ext-emmet.js');
        wp_enqueue_script('p5js-app', P5JS__PLUGIN_URL . '/admin/js/app.js', array('p5js-ace-editor','jquery','jquery-ui-tabs'));

        global $wp_scripts;
        $ui = $wp_scripts->query('jquery-ui-core');

        wp_enqueue_style(
            'jquery-ui-smoothness',
            "//ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css",
            false,
            null
        );
    } else {
      return;
    }
  }
}
