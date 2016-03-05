<?php
/**
 *
 */
class WP_p5js_Shortcode
{

  function __construct()
  {
    # code...
  }

  public static function init() {
    add_action( 'edit_form_after_title', array('WP_p5js_Shortcode', 'p5js_display_shortcode') );
    add_shortcode( 'p5js', array('WP_p5js_Shortcode', 'p5js_do_shortcode') );
  }

  /**
   * Display shortcode
   */
  public static function p5js_display_shortcode() {
    $current_screen = get_current_screen();
    if ( 'p5js' == $current_screen->post_type && 'post' == $current_screen->base ) {

        global $post;
        printf(
          '<div class="inside">
          	<p class="description">
          	<label for="p5js-shortcode">%s</label>
          	<span class="shortcode onfocus="this.select();">
              <input type="text" id="p5js-shortcode" readonly="readonly" onfocus="this.select();" class="wp-ui-highlight large-text code p5js-post" value="%s">
            </span>
          	</p>
          </div>',
          _e('このショートコードをコピーして、投稿、固定ページ、またはテキストウィジェットの内容にペーストしてください:', 'wp-p5js'),
          esc_attr( get_post_meta( $post->ID, '_p5js_shortcode', true ) )
        );

        printf(
          '<div id="tabmenu">
              <ul>
                  <li><a href="#editor-html">HTML</a></li>
                  <li><a href="#editor-css">CSS</a></li>
                  <li><a href="#editor-setup">Setup.js</a></li>
                  <li><a href="#editor-draw">Draw.js</a></li>
                  <li><a href="#p5js-admin-preview">Preview</a></li>
              </ul>
              <div id="editor-html">
                  %1$s
              </div>
              <div id="editor-css">
                  %2$s
              </div>
              <div id="editor-setup">
                  %3$s
              </div>
              <div id="editor-draw">
                  %4$s
              </div>
              <div id="p5js-admin-preview">
                  <p>メニュー 5 の内容。</p>
              </div>
            </div>',
          get_post_meta( $post->ID, 'p5js-html', true ),
          get_post_meta( $post->ID, 'p5js-css', true ),
          get_post_meta( $post->ID, 'p5js-setup', true ),
          get_post_meta( $post->ID, 'p5js-draw', true )
        );
    } else {
      return;
    }
  }


  public static function p5js_do_shortcode( $atts, $content = null ){

    $shortcode_atts = shortcode_atts( array(
        'id' => '',
        'title' => '',
    ), $atts );

    $pre = '<div><script type="text/javascript">';
    $suf = '</script></div>';

    $script = '';
    if( ! empty($shortcode_atts['id']) ) {
      $script = get_post_meta( $shortcode_atts['id'], 'p5js-script', true );
    }

    $p5js_script = $pre . $script . $suf;
    return $p5js_script;
  }
}
