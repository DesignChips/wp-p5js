<?php
/**
 *
 */
class WP_p5js_Screen_Settings
{

  function __construct()
  {
    # code...
  }

  public static function init() {
    add_filter(
      'get_user_option_screen_layout_p5js',
      array('WP_p5js_Screen_Settings', 'screen_layout_p5js', 10, 3) );
  }

  /**
   * Preset edit single column
   * @param  [type] $result [description]
   * @param  [type] $option [description]
   * @param  [type] $user   [description]
   * @return [type]         [description]
   */
  public static function screen_layout_p5js($result, $option, $user){
    return 1;
  }
}
