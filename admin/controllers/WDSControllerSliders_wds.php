<?php

class WDSControllerSliders_wds {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $task = WDW_S_Library::get('task');
    $id = WDW_S_Library::get('current_id', 0);
    $message = WDW_S_Library::get('message');
    echo WDW_S_Library::message_id($message);
    if (method_exists($this, $task)) {
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();

    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    $view->display();
  }

  public function add() {
    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();

    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    $view->edit(0);
  }

  public function edit() {
    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();

    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    $id = WDW_S_Library::get('current_id', 0);
    $view->edit($id);
  }

  public function save() {
    $message = $this->save_slider_db();
    $this->save_slide_db();
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array('page' => $page, 'task' => 'display', 'message' => $message), admin_url('admin.php')));
  }

  public function apply() {
    $message = $this->save_slider_db();
    $this->save_slide_db();
    global $wpdb;
    $id = (int) $wpdb->get_var('SELECT MAX(`id`) FROM ' . $wpdb->prefix . 'wdsslider');
    $current_id = WDW_S_Library::get('current_id', $id);
    $nav_tab = WDW_S_Library::get('nav_tab', 'global');
    $tab = WDW_S_Library::get('tab', 'settings');
    $sub_tab = WDW_S_Library::get('sub_tab', '');
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array('page' => $page, 'task' => 'edit', 'current_id' => $current_id, 'nav_tab' => $nav_tab, 'tab' => $tab, 'sub_tab' => $sub_tab, 'message' => $message), admin_url('admin.php')));
  }
  
  public function save_slider_db() {
    global $wpdb;
    $slider_id = (isset($_POST['current_id']) ? (int) $_POST['current_id'] : 0);
    $name = ((isset($_POST['name'])) ? esc_html(stripslashes($_POST['name'])) : '');
    $published = ((isset($_POST['published'])) ? (int) esc_html(stripslashes($_POST['published'])) : 1);
    $full_width = ((isset($_POST['full_width'])) ? (int) esc_html(stripslashes($_POST['full_width'])) : 0);
    $width = ((isset($_POST['width'])) ? (int) esc_html(stripslashes($_POST['width'])) : 800);
    $height = ((isset($_POST['height'])) ? (int) esc_html((stripslashes($_POST['height']))) : 300);
    $bg_fit = ((isset($_POST['bg_fit'])) ? esc_html(stripslashes($_POST['bg_fit'])) : 'cover');
    $align = ((isset($_POST['align'])) ? esc_html(stripslashes($_POST['align'])) : 'center');
    $effect = ((isset($_POST['effect'])) ? esc_html(stripslashes($_POST['effect'])) : 'fade');
    $time_intervval = ((isset($_POST['time_intervval'])) ? (int) esc_html(stripslashes($_POST['time_intervval'])) : 5);
    $autoplay = ((isset($_POST['autoplay'])) ? (int) esc_html(stripslashes($_POST['autoplay'])) : 0);
    $shuffle = ((isset($_POST['shuffle'])) ? (int) esc_html(stripslashes($_POST['shuffle'])) : 0);
    $music = ((isset($_POST['music'])) ? (int) esc_html(stripslashes($_POST['music'])) : 0);	
    $music_url = ((isset($_POST['music_url'])) ? esc_html(stripslashes($_POST['music_url'])) : '');
    $preload_images = ((isset($_POST['preload_images'])) ? (int) esc_html(stripslashes($_POST['preload_images'])) : 1);
    $background_color = ((isset($_POST['background_color'])) ? esc_html(stripslashes($_POST['background_color'])) : '000000');
    $background_transparent = ((isset($_POST['background_transparent'])) ? esc_html(stripslashes($_POST['background_transparent'])) : 100);
    $glb_border_width = ((isset($_POST['glb_border_width'])) ? (int) esc_html(stripslashes($_POST['glb_border_width'])) : 0);
    $glb_border_style = ((isset($_POST['glb_border_style'])) ? esc_html(stripslashes($_POST['glb_border_style'])) : 'none');	
    $glb_border_color = ((isset($_POST['glb_border_color'])) ? esc_html(stripslashes($_POST['glb_border_color'])) : 'FFFFFF');
    $glb_border_radius = ((isset($_POST['glb_border_radius'])) ? esc_html(stripslashes($_POST['glb_border_radius'])) : '');
    $glb_margin = ((isset($_POST['glb_margin'])) ? (int) esc_html(stripslashes($_POST['glb_margin'])) : 0);
    $glb_box_shadow = ((isset($_POST['glb_box_shadow'])) ? esc_html(stripslashes($_POST['glb_box_shadow'])) : '');
    $image_right_click = ((isset($_POST['image_right_click'])) ? (int) esc_html(stripslashes($_POST['image_right_click'])) : 0);
    $layer_out_next = ((isset($_POST['layer_out_next'])) ? (int) esc_html(stripslashes($_POST['layer_out_next'])) : 0);
    $prev_next_butt = ((isset($_POST['prev_next_butt'])) ? (int) esc_html(stripslashes($_POST['prev_next_butt'])) : 1);	
    $play_paus_butt = ((isset($_POST['play_paus_butt'])) ? (int) esc_html(stripslashes($_POST['play_paus_butt'])) : 0);
    $navigation = ((isset($_POST['navigation'])) ? esc_html(stripslashes($_POST['navigation'])) : 'hover');
    $rl_butt_style = ((isset($_POST['rl_butt_style'])) ? esc_html(stripslashes($_POST['rl_butt_style'])) : 'fa-angle-double');
    $rl_butt_size = ((isset($_POST['rl_butt_size'])) ? (int) esc_html(stripslashes($_POST['rl_butt_size'])) : 40);
    $pp_butt_size = ((isset($_POST['pp_butt_size'])) ? (int) esc_html(stripslashes($_POST['pp_butt_size'])) : 40);	
    $butts_color = ((isset($_POST['butts_color'])) ? esc_html(stripslashes($_POST['butts_color'])) : '000000');
    $butts_transparent = ((isset($_POST['butts_transparent'])) ? (int) esc_html(stripslashes($_POST['butts_transparent'])) : 50);
    $hover_color = ((isset($_POST['hover_color'])) ? esc_html(stripslashes($_POST['hover_color'])) : '000000');
    $nav_border_width = ((isset($_POST['nav_border_width'])) ? (int) esc_html(stripslashes($_POST['nav_border_width'])) : 0);
    $nav_border_style = ((isset($_POST['nav_border_style'])) ? esc_html(stripslashes($_POST['nav_border_style'])) : 'none');
    $nav_border_color = ((isset($_POST['nav_border_color'])) ? esc_html(stripslashes($_POST['nav_border_color'])) : 'FFFFFF');	
    $nav_border_radius = ((isset($_POST['nav_border_radius'])) ? esc_html(stripslashes($_POST['nav_border_radius'])) : '20px');
    $nav_bg_color = ((isset($_POST['nav_bg_color'])) ? esc_html(stripslashes($_POST['nav_bg_color'])) : 'FFFFFF');
    $bull_position = ((isset($_POST['bull_position'])) ? esc_html(stripslashes($_POST['bull_position'])) : 'bottom');
    if (isset($_POST['enable_bullets']) && (esc_html(stripslashes($_POST['enable_bullets'])) == 0)) {
      $bull_position = 'none';
    }
    $bull_style = ((isset($_POST['bull_style'])) ? esc_html(stripslashes($_POST['bull_style'])) : 'fa-circle-o');
    $bull_size = ((isset($_POST['bull_size'])) ? (int) esc_html(stripslashes($_POST['bull_size'])) : 25);
    $bull_color = ((isset($_POST['bull_color'])) ? esc_html(stripslashes($_POST['bull_color'])) : 'FFFFFF');	
    $bull_act_color = ((isset($_POST['bull_act_color'])) ? esc_html(stripslashes($_POST['bull_act_color'])) : 'FFFFFF');
    $bull_margin = ((isset($_POST['bull_margin'])) ? (int) esc_html(stripslashes($_POST['bull_margin'])) : 3);
    $film_pos = ((isset($_POST['film_pos'])) ? esc_html(stripslashes($_POST['film_pos'])) : 'none');
    if (isset($_POST['enable_filmstrip']) && (esc_html(stripslashes($_POST['enable_filmstrip'])) == 0)) {
      $film_pos = 'none';
    }
    $film_thumb_width = ((isset($_POST['film_thumb_width'])) ? (int) esc_html(stripslashes($_POST['film_thumb_width'])) : 100);
    $film_thumb_height = ((isset($_POST['film_thumb_height'])) ? (int) esc_html(stripslashes($_POST['film_thumb_height'])) : 50);
    $film_bg_color = ((isset($_POST['film_bg_color'])) ? esc_html(stripslashes($_POST['film_bg_color'])) : '000000');	
    $film_tmb_margin = ((isset($_POST['film_tmb_margin'])) ? (int) esc_html(stripslashes($_POST['film_tmb_margin'])) : 0);
    $film_act_border_width = ((isset($_POST['film_act_border_width'])) ? (int) esc_html(stripslashes($_POST['film_act_border_width'])) : 0);
    $film_act_border_style = ((isset($_POST['film_act_border_style'])) ? esc_html(stripslashes($_POST['film_act_border_style'])) : 'none');
    $film_act_border_color = ((isset($_POST['film_act_border_color'])) ? esc_html(stripslashes($_POST['film_act_border_color'])) : 'FFFFFF');
    $film_dac_transparent = ((isset($_POST['film_dac_transparent'])) ? (int) esc_html(stripslashes($_POST['film_dac_transparent'])) : 50);	
    $built_in_watermark_type = (isset($_POST['built_in_watermark_type']) ? esc_html(stripslashes($_POST['built_in_watermark_type'])) : 'none');
    $built_in_watermark_position = (isset($_POST['built_in_watermark_position']) ? esc_html(stripslashes($_POST['built_in_watermark_position'])) : 'middle-center');
    $built_in_watermark_size = (isset($_POST['built_in_watermark_size']) ? esc_html(stripslashes($_POST['built_in_watermark_size'])) : 15);
    $built_in_watermark_url = (isset($_POST['built_in_watermark_url']) ? esc_html(stripslashes($_POST['built_in_watermark_url'])) : '');
    $built_in_watermark_text = (isset($_POST['built_in_watermark_text']) ? esc_html(stripslashes($_POST['built_in_watermark_text'])) : 'web-dorado.com');
    $built_in_watermark_opacity = (isset($_POST['built_in_watermark_opacity']) ? esc_html(stripslashes($_POST['built_in_watermark_opacity'])) : 70);
    $built_in_watermark_font_size = (isset($_POST['built_in_watermark_font_size']) ? esc_html(stripslashes($_POST['built_in_watermark_font_size'])) : 20);
    $built_in_watermark_font = (isset($_POST['built_in_watermark_font']) ? esc_html(stripslashes($_POST['built_in_watermark_font'])) : '');
    $built_in_watermark_color = (isset($_POST['built_in_watermark_color']) ? esc_html(stripslashes($_POST['built_in_watermark_color'])) : 'FFFFFF');
    $css = (isset($_POST['css']) ? esc_html(stripslashes($_POST['css'])) : '');
    $timer_bar_type = (isset($_POST['timer_bar_type']) ? esc_html(stripslashes($_POST['timer_bar_type'])) : 'top');
    if (isset($_POST['enable_time_bar']) && (esc_html(stripslashes($_POST['enable_time_bar'])) == 0)) {
      $timer_bar_type = 'none';
    }
    $timer_bar_size = (isset($_POST['timer_bar_size']) ? esc_html(stripslashes($_POST['timer_bar_size'])) : 5);
    $timer_bar_color = (isset($_POST['timer_bar_color']) ? esc_html(stripslashes($_POST['timer_bar_color'])) : 'FFFFFF');
    $timer_bar_transparent = (isset($_POST['timer_bar_transparent']) ? esc_html(stripslashes($_POST['timer_bar_transparent'])) : 50);
    if (!$slider_id) {
      $save = $wpdb->insert($wpdb->prefix . 'wdsslider', array(			
        'name' => $name,
        'published' => $published,
        'full_width' => $full_width,
        'width' => $width,
        'height' => $height,
        'bg_fit' => $bg_fit,
        'align' => $align,
        'effect' => $effect,
        'time_intervval' => $time_intervval,
        'autoplay' => $autoplay,
        'shuffle' => $shuffle,
        'music' => $music,
        'music_url' => $music_url,
        'preload_images' => $preload_images,
        'background_color' => $background_color,
        'background_transparent' => $background_transparent,
        'glb_border_width' => $glb_border_width,
        'glb_border_style' => $glb_border_style,
        'glb_border_color' => $glb_border_color,
        'glb_border_radius' => $glb_border_radius,
        'glb_margin' => $glb_margin,
        'glb_box_shadow' => $glb_box_shadow,
        'image_right_click' => $image_right_click,
        'prev_next_butt' => $prev_next_butt,	
        'play_paus_butt' => $play_paus_butt,
        'navigation' => $navigation,
        'rl_butt_style' => $rl_butt_style,
        'rl_butt_size' => $rl_butt_size,
        'pp_butt_size' => $pp_butt_size,	
        'butts_color' => $butts_color,
        'butts_transparent' => $butts_transparent,
        'hover_color' => $hover_color,
        'nav_border_width' => $nav_border_width,
        'nav_border_style' => $nav_border_style,
        'nav_border_color' => $nav_border_color,
        'nav_border_radius' => $nav_border_radius,
        'nav_bg_color' => $nav_bg_color,
        'bull_position' => $bull_position,
        'bull_style' => $bull_style,
        'bull_size' => $bull_size,
        'bull_color' => $bull_color,
        'bull_act_color' => $bull_act_color,
        'bull_margin' => $bull_margin,
        'film_pos' => $film_pos,
        'film_thumb_width' => $film_thumb_width,
        'film_thumb_height' => $film_thumb_height,
        'film_bg_color' => $film_bg_color,
        'film_tmb_margin' => $film_tmb_margin,
        'film_act_border_width' => $film_act_border_width,
        'film_act_border_style' => $film_act_border_style,
        'film_act_border_color' => $film_act_border_color,
        'film_dac_transparent' => $film_dac_transparent,	
        'built_in_watermark_type' => $built_in_watermark_type,
        'built_in_watermark_position' => $built_in_watermark_position,
        'built_in_watermark_size' => $built_in_watermark_size,
        'built_in_watermark_url' => $built_in_watermark_url,
        'built_in_watermark_text' => $built_in_watermark_text,
        'built_in_watermark_opacity' => $built_in_watermark_opacity,		
        'built_in_watermark_font_size' => $built_in_watermark_font_size,
        'built_in_watermark_font' => $built_in_watermark_font,
        'built_in_watermark_color' => $built_in_watermark_color,
        'css' => $css,
        'timer_bar_type' => $timer_bar_type,
        'timer_bar_size' => $timer_bar_size,
        'timer_bar_color' => $timer_bar_color,
        'timer_bar_transparent' => $timer_bar_transparent,
        'layer_out_next' => $layer_out_next,
      ), array(
        '%s',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s',
        '%s',
        '%s',
        '%d',
        '%d',
        '%d',
        '%d',
        '%s',
        '%d',
        '%s',
        '%d',
        '%d',
        '%s',
        '%s',
        '%s',
        '%d',
        '%s',
        '%d',
        '%d',
        '%d',
        '%s',
        '%s',
        '%d',
        '%d',
        '%s',
        '%d',
        '%s',
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
        '%s',
        '%s',
        '%d',
        '%s',
        '%d',
        '%d',
        '%s',
        '%d',
        '%d',
        '%s',
        '%s',
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
        '%s',
        '%d',
        '%d',
      ));
    }
    else {
      $save = $wpdb->update($wpdb->prefix . 'wdsslider', array(
        'name' => $name,
        'published' => $published,
        'full_width' => $full_width,
        'width' => $width,
        'height' => $height,
        'bg_fit' => $bg_fit,
        'align' => $align,
        'effect' => $effect,
        'time_intervval' => $time_intervval,
        'autoplay' => $autoplay,
        'shuffle' => $shuffle,
        'music' => $music,
        'music_url' => $music_url,
        'preload_images' => $preload_images, 
        'background_color' => $background_color,
        'background_transparent' => $background_transparent,
        'glb_border_width' => $glb_border_width,
        'glb_border_style' => $glb_border_style,
        'glb_border_color' => $glb_border_color,
        'glb_border_radius' => $glb_border_radius,
        'glb_margin' => $glb_margin,
        'glb_box_shadow' => $glb_box_shadow,
        'image_right_click' => $image_right_click,
        'prev_next_butt' => $prev_next_butt,	
        'play_paus_butt' => $play_paus_butt,
        'navigation' => $navigation,
        'rl_butt_style' => $rl_butt_style,
        'rl_butt_size' => $rl_butt_size,
        'pp_butt_size' => $pp_butt_size,	
        'butts_color' => $butts_color,
        'butts_transparent' => $butts_transparent,
        'hover_color' => $hover_color,
        'nav_border_width' => $nav_border_width,
        'nav_border_style' => $nav_border_style,
        'nav_border_color' => $nav_border_color,
        'nav_border_radius' => $nav_border_radius,
        'nav_bg_color' => $nav_bg_color,
        'bull_position' => $bull_position,
        'bull_style' => $bull_style,
        'bull_size' => $bull_size,
        'bull_color' => $bull_color,
        'bull_act_color' => $bull_act_color,
        'bull_margin' => $bull_margin,
        'film_pos' => $film_pos,
        'film_thumb_width' => $film_thumb_width,
        'film_thumb_height' => $film_thumb_height,
        'film_bg_color' => $film_bg_color,
        'film_tmb_margin' => $film_tmb_margin,
        'film_act_border_width' => $film_act_border_width,
        'film_act_border_style' => $film_act_border_style,
        'film_act_border_color' => $film_act_border_color,
        'film_dac_transparent' => $film_dac_transparent,	
        'built_in_watermark_type' => $built_in_watermark_type,
        'built_in_watermark_position' => $built_in_watermark_position,
        'built_in_watermark_size' => $built_in_watermark_size,
        'built_in_watermark_url' => $built_in_watermark_url,
        'built_in_watermark_text' => $built_in_watermark_text,
        'built_in_watermark_opacity' => $built_in_watermark_opacity,		
        'built_in_watermark_font_size' => $built_in_watermark_font_size,
        'built_in_watermark_font' => $built_in_watermark_font,
        'built_in_watermark_color' => $built_in_watermark_color,
        'css' => $css,
        'timer_bar_type' => $timer_bar_type,
        'timer_bar_size' => $timer_bar_size,
        'timer_bar_color' => $timer_bar_color,
        'timer_bar_transparent' => $timer_bar_transparent,
        'layer_out_next' => $layer_out_next,
        ), array('id' => $slider_id));
    }
    if ($save !== FALSE) {
      return 1;
    }
    else {
      return 2;
    }
  }

  public function save_slide_db() {
    global $wpdb;
    $slider_id = (isset($_POST['current_id']) ? (int) $_POST['current_id'] : 0);
    if (!$slider_id) {
      $slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');
    }
    $del_slide_ids_string = (isset($_POST['del_slide_ids_string']) ? esc_html(stripslashes($_POST['del_slide_ids_string'])) : '');
    $del_slide_id_array = explode(',', $del_slide_ids_string);
    foreach ($del_slide_id_array as $del_slide_id) {
      if ($del_slide_id) {
        $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wdsslide WHERE id="%d"', $del_slide_id));
      }
    }
    $slide_ids_string = (isset($_POST['slide_ids_string']) ? esc_html(stripslashes($_POST['slide_ids_string'])) : '');
    $slide_id_array = explode(',', $slide_ids_string);
    foreach ($slide_id_array as $slide_id) {
      if ($slide_id) {
        $title = ((isset($_POST['title' . $slide_id])) ? esc_html(stripslashes($_POST['title' . $slide_id])) : '');
        $type = ((isset($_POST['type' . $slide_id])) ? esc_html(stripslashes($_POST['type' . $slide_id])) : '');
        $order = ((isset($_POST['order' . $slide_id])) ? esc_html(stripslashes($_POST['order' . $slide_id])) : '');
        $published = ((isset($_POST['published' . $slide_id])) ? esc_html(stripslashes($_POST['published' . $slide_id])) : '');
        $link = ((isset($_POST['link' . $slide_id])) ? esc_html(stripslashes($_POST['link' . $slide_id])) : '');
        $image_url = ((isset($_POST['image_url' . $slide_id])) ? esc_html(stripslashes($_POST['image_url' . $slide_id])) : '');
        $thumb_url = ((isset($_POST['thumb_url' . $slide_id])) ? esc_html(stripslashes($_POST['thumb_url' . $slide_id])) : '');
        if (strpos($slide_id, 'pr') !== FALSE) {
          $save = $wpdb->insert($wpdb->prefix . 'wdsslide', array(
            'slider_id' => $slider_id,
            'title' => $title,
            'type' => $type,
            'order' => $order,
            'published' => $published,
            'link' => $link,
            'image_url' => $image_url,
            'thumb_url' => $thumb_url,
          ), array(
            '%d',
            '%s',
            '%s',
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
          ));
          $slide_id_pr = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslide');
          $this->save_layer_db($slide_id, $slide_id_pr);
        }
        else {
          $save = $wpdb->update($wpdb->prefix . 'wdsslide', array(
            'slider_id' => $slider_id,
            'title' => $title,
            'type' => $type,
            'order' => $order,
            'published' => $published,
            'link' => $link,
            'image_url' => $image_url,
            'thumb_url' => $thumb_url,
          ), array('id' => $slide_id));
          $this->save_layer_db($slide_id, $slide_id);
        }
      }
    }
  }

  public function save_layer_db($slide_id, $slide_id_pr) {
    global $wpdb;
    $del_layer_ids_string = (isset($_POST['slide' . $slide_id . '_del_layer_ids_string']) ? esc_html(stripslashes($_POST['slide' . $slide_id . '_del_layer_ids_string'])) : '');
    $del_layer_id_array = explode(',', $del_layer_ids_string);
    foreach ($del_layer_id_array as $del_layer_id) {
      if ($del_layer_id) {
        $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wdslayer WHERE id="%d"', $del_layer_id));
      }
    }
    $layer_ids_string = (isset($_POST['slide' . $slide_id . '_layer_ids_string']) ? esc_html(stripslashes($_POST['slide' . $slide_id . '_layer_ids_string'])) : '');
    $layer_id_array = explode(',', $layer_ids_string);
    foreach ($layer_id_array as $layer_id) {
      if ($layer_id) {
        $prefix = 'slide' . $slide_id . '_layer' . $layer_id;
        $title = ((isset($_POST[$prefix . '_title'])) ? esc_html(stripslashes($_POST[$prefix . '_title'])) : '');
        $type = ((isset($_POST[$prefix . '_type'])) ? esc_html(stripslashes($_POST[$prefix . '_type'])) : '');
        $depth = ((isset($_POST[$prefix . '_depth'])) ? esc_html(stripslashes($_POST[$prefix . '_depth'])) : '');
        $text = ((isset($_POST[$prefix . '_text'])) ? stripslashes($_POST[$prefix . '_text']) : '');
        $link = ((isset($_POST[$prefix . '_link'])) ? esc_html(stripslashes($_POST[$prefix . '_link'])) : '');
        $width = ((isset($_POST['width'])) ? (int) esc_html(stripslashes($_POST['width'])) : 0);
        $height = ((isset($_POST['height'])) ? (int) esc_html((stripslashes($_POST['height']))) : 0);
        $left = ((isset($_POST[$prefix . '_left'])) ? esc_html(stripslashes($_POST[$prefix . '_left'])) : '');
        $top = ((isset($_POST[$prefix . '_top'])) ? esc_html(stripslashes($_POST[$prefix . '_top'])) : '');
        $start = ((isset($_POST[$prefix . '_start'])) ? esc_html(stripslashes($_POST[$prefix . '_start'])) : '');
        $end = ((isset($_POST[$prefix . '_end'])) ? esc_html(stripslashes($_POST[$prefix . '_end'])) : '');
        $published = ((isset($_POST[$prefix . '_published'])) ? esc_html(stripslashes($_POST[$prefix . '_published'])) : '');
        $color = ((isset($_POST[$prefix . '_color'])) ? esc_html(stripslashes($_POST[$prefix . '_color'])) : '');
        $size = ((isset($_POST[$prefix . '_size'])) ? esc_html(stripslashes($_POST[$prefix . '_size'])) : '');
        $ffamily = ((isset($_POST[$prefix . '_ffamily'])) ? esc_html(stripslashes($_POST[$prefix . '_ffamily'])) : '');
        $fweight = ((isset($_POST[$prefix . '_fweight'])) ? esc_html(stripslashes($_POST[$prefix . '_fweight'])) : '');
        $padding = ((isset($_POST[$prefix . '_padding'])) ? esc_html(stripslashes($_POST[$prefix . '_padding'])) : '');
        $fbgcolor = ((isset($_POST[$prefix . '_fbgcolor'])) ? esc_html(stripslashes($_POST[$prefix . '_fbgcolor'])) : '');
        $transparent = ((isset($_POST[$prefix . '_transparent'])) ? esc_html(stripslashes($_POST[$prefix . '_transparent'])) : '');
        $border_width = ((isset($_POST[$prefix . '_border_width'])) ? esc_html(stripslashes($_POST[$prefix . '_border_width'])) : '');
        $border_style = ((isset($_POST[$prefix . '_border_style'])) ? esc_html(stripslashes($_POST[$prefix . '_border_style'])) : '');
        $border_color = ((isset($_POST[$prefix . '_border_color'])) ? esc_html(stripslashes($_POST[$prefix . '_border_color'])) : '');
        $border_radius = ((isset($_POST[$prefix . '_border_radius'])) ? esc_html(stripslashes($_POST[$prefix . '_border_radius'])) : '');
        $shadow = ((isset($_POST[$prefix . '_shadow'])) ? esc_html(stripslashes($_POST[$prefix . '_shadow'])) : '');
        $image_url = ((isset($_POST[$prefix . '_image_url'])) ? esc_html(stripslashes($_POST[$prefix . '_image_url'])) : '');
        $image_width = ((isset($_POST[$prefix . '_image_width'])) ? esc_html(stripslashes($_POST[$prefix . '_image_width'])) : '');
        $image_height = ((isset($_POST[$prefix . '_image_height'])) ? esc_html(stripslashes($_POST[$prefix . '_image_height'])) : '');
        $image_scale = ((isset($_POST[$prefix . '_image_scale'])) ? esc_html(stripslashes($_POST[$prefix . '_image_scale'])) : '');
        $alt = ((isset($_POST[$prefix . '_alt'])) ? esc_html(stripslashes($_POST[$prefix . '_alt'])) : '');
        $imgtransparent = ((isset($_POST[$prefix . '_imgtransparent'])) ? esc_html(stripslashes($_POST[$prefix . '_imgtransparent'])) : '');
        $social_button = ((isset($_POST[$prefix . '_social_button'])) ? esc_html(stripslashes($_POST[$prefix . '_social_button'])) : '');
        $hover_color = ((isset($_POST[$prefix . '_hover_color'])) ? esc_html(stripslashes($_POST[$prefix . '_hover_color'])) : '');
        $layer_effect_in = ((isset($_POST[$prefix . '_layer_effect_in'])) ? esc_html(stripslashes($_POST[$prefix . '_layer_effect_in'])) : ''); 
        $layer_effect_out = ((isset($_POST[$prefix . '_layer_effect_out'])) ? esc_html(stripslashes($_POST[$prefix . '_layer_effect_out'])) : '');
        $duration_eff_in = ((isset($_POST[$prefix . '_duration_eff_in'])) ? esc_html(stripslashes($_POST[$prefix . '_duration_eff_in'])) : 3);
        $duration_eff_out = ((isset($_POST[$prefix . '_duration_eff_out'])) ? esc_html(stripslashes($_POST[$prefix . '_duration_eff_out'])) : 3);
        if ($title) {
          if (strpos($layer_id, 'pr_') !== FALSE) {
            $save = $wpdb->insert($wpdb->prefix . 'wdslayer', array(
              'slide_id' => $slide_id_pr,
              'title' => $title,
              'type' => $type,
              'depth' => $depth,
              'text' => $text,
              'link' => $link,
              'left' => $left,
              'top' => $top,
              'start' => $start,
              'end' => $end,
              'published' => $published,
              'color' => $color,
              'size' => $size,
              'ffamily' => $ffamily,
              'fweight' => $fweight,
              'padding' => $padding,
              'fbgcolor' => $fbgcolor,
              'transparent' => $transparent,
              'border_width' => $border_width,
              'border_style' => $border_style,
              'border_color' => $border_color,
              'border_radius' => $border_radius,
              'shadow' => $shadow,
              'image_url' => $image_url,
              'image_width' => $image_width,
              'image_height' => $image_height,
              'image_scale' => $image_scale,
              'alt' => $alt,
              'imgtransparent' => $imgtransparent,
              'social_button' => $social_button,
              'hover_color' => $hover_color,
              'layer_effect_in' => $layer_effect_in,
              'layer_effect_out' => $layer_effect_out,
              'duration_eff_in' => $duration_eff_in,
              'duration_eff_out' => $duration_eff_out,
            ), array(
              '%d',
              '%s',
              '%s',
              '%d',
              '%s',
              '%s',
              '%d',
              '%d',
              '%d',
              '%d',
              '%d',
              '%s',
              '%d',
              '%s',
              '%s',
              '%s',
              '%s',
              '%d',
              '%d',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%d',
              '%d',
              '%s',
              '%s',
              '%d',
              '%s',
              '%s',
              '%s',
              '%s',
              '%s',
              '%d',
              '%d',
            ));
          }
          else {
            $save = $wpdb->update($wpdb->prefix . 'wdslayer', array(
              'title' => $title,
              'type' => $type,
              'depth' => $depth,
              'text' => $text,
              'link' => $link,
              'left' => $left,
              'top' => $top,
              'start' => $start,
              'end' => $end,
              'published' => $published,
              'color' => $color,
              'size' => $size,
              'ffamily' => $ffamily,
              'fweight' => $fweight,
              'padding' => $padding,
              'fbgcolor' => $fbgcolor,
              'transparent' => $transparent,
              'border_width' => $border_width,
              'border_style' => $border_style,
              'border_color' => $border_color,
              'border_radius' => $border_radius,
              'shadow' => $shadow,
              'image_url' => $image_url,
              'image_width' => $image_width,
              'image_height' => $image_height,
              'image_scale' => $image_scale,
              'alt' => $alt,
              'imgtransparent' => $imgtransparent,
              'social_button' => $social_button,
              'hover_color' => $hover_color,
              'layer_effect_in' => $layer_effect_in,
              'layer_effect_out' => $layer_effect_out,
              'duration_eff_in' => $duration_eff_in,
              'duration_eff_out' => $duration_eff_out,
            ), array('id' => $layer_id));
          }
        }
      }
    }
  }

  public function set_watermark() {
    $message = $this->save_slider_db();
    $this->save_slide_db();
    global $wpdb;
    $slider_id = WDW_S_Library::get('current_id', 0);
    if (!$slider_id) {
      $slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');
    }

    $slider_images = $wpdb->get_col($wpdb->prepare('SELECT image_url FROM ' . $wpdb->prefix . 'wdsslide WHERE `slider_id`="%d"', $slider_id));
    $slider = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider WHERE `id`="%d"', $slider_id));

    switch ($slider->built_in_watermark_type) {
      case 'text':
        foreach ($slider_images as $slider_image) {
          if ($slider_image) {
            $slider_image_dir = str_replace(site_url() . '/', ABSPATH, $slider_image);
            $last_dot_pos = strrpos($slider_image_dir, '.');
            $base_name = substr($slider_image_dir, 0, $last_dot_pos);
            $ext = substr($slider_image_dir, strlen($base_name));
            $new_image = $base_name . '-original' . $ext;
            if (!file_exists($new_image)) {
              copy($slider_image_dir, $new_image);
            }
            $this->set_text_watermark($slider_image_dir, $slider_image_dir, $slider->built_in_watermark_text, $slider->built_in_watermark_font, $slider->built_in_watermark_font_size, '#' . $slider->built_in_watermark_color, $slider->built_in_watermark_opacity, $slider->built_in_watermark_position);
          }
        }
        break;
      case 'image':
        foreach ($slider_images as $slider_image) {
          if ($slider_image) {
            $slider_image_dir = str_replace(site_url() . '/', ABSPATH, $slider_image);
            $last_dot_pos = strrpos($slider_image_dir, '.');
            $base_name = substr($slider_image_dir, 0, $last_dot_pos);
            $ext = substr($slider_image_dir, strlen($base_name));
            $new_image = $base_name . '-original' . $ext;
            if (!file_exists($new_image)) {
              copy($slider_image_dir, $new_image);
            }
            $watermark_image_dir = str_replace(site_url() . '/', ABSPATH, $slider->built_in_watermark_url);
            $this->set_image_watermark($slider_image_dir, $slider_image_dir, $watermark_image_dir, $slider->built_in_watermark_size, $slider->built_in_watermark_size, $slider->built_in_watermark_position);
          }
        }
        break;
    }

    $nav_tab = WDW_S_Library::get('nav_tab', 'global');
    $tab = WDW_S_Library::get('tab', 'settings');
    $sub_tab = WDW_S_Library::get('sub_tab', '');
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array('page' => $page, 'task' => 'edit', 'current_id' => $slider_id, 'nav_tab' => $nav_tab, 'tab' => $tab, 'sub_tab' => $sub_tab, 'message' => $message), admin_url('admin.php')));
  }

  public function reset_watermark() {
    $this->save_slider_db();
    $this->save_slide_db();
    global $wpdb;
    $slider_id = WDW_S_Library::get('current_id', 0);
    if (!$slider_id) {
      $slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');
    }

    $slider_images = $wpdb->get_col($wpdb->prepare('SELECT image_url FROM ' . $wpdb->prefix . 'wdsslide WHERE `slider_id`="%d"', $slider_id));
    foreach ($slider_images as $slider_image) {
      if ($slider_image) {
        $slider_image_dir = str_replace(site_url() . '/', ABSPATH, $slider_image);
        $last_dot_pos = strrpos($slider_image_dir, '.');
        $base_name = substr($slider_image_dir, 0, $last_dot_pos);
        $ext = substr($slider_image_dir, strlen($base_name));
        $new_image = $base_name . '-original' . $ext;
        if (file_exists($new_image)) {
          copy($new_image, $slider_image_dir);
        }
      }
    }

    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();

    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    echo WDW_S_Library::message('Watermark Succesfully Removed.', 'updated');
    $view->edit($slider_id);
  }

  public function reset() {
    $this->save_slider_db();
    $this->save_slide_db();
    global $wpdb;
    $slider_id = WDW_S_Library::get('current_id', 0);
    if (!$slider_id) {
      $slider_id = $wpdb->get_var('SELECT MAX(id) FROM ' . $wpdb->prefix . 'wdsslider');
    }

    require_once WD_S_DIR . "/admin/models/WDSModelSliders_wds.php";
    $model = new WDSModelSliders_wds();

    require_once WD_S_DIR . "/admin/views/WDSViewSliders_wds.php";
    $view = new WDSViewSliders_wds($model);
    echo WDW_S_Library::message('Changes must be saved.', 'error');
    $view->edit($slider_id, TRUE);
  }

  function bwg_hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);
    if (strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    }
    else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
    }
    $rgb = array($r, $g, $b);
    return $rgb;
  }

  function bwg_imagettfbboxdimensions($font_size, $font_angle, $font, $text) {
    $box = @ImageTTFBBox($font_size, $font_angle, $font, $text) or die;
    $max_x = max(array($box[0], $box[2], $box[4], $box[6]));
    $max_y = max(array($box[1], $box[3], $box[5], $box[7]));
    $min_x = min(array($box[0], $box[2], $box[4], $box[6]));
    $min_y = min(array($box[1], $box[3], $box[5], $box[7]));
    return array(
      "width"  => ($max_x - $min_x),
      "height" => ($max_y - $min_y)
    );
  }

  function set_text_watermark($original_filename, $dest_filename, $watermark_text, $watermark_font, $watermark_font_size, $watermark_color, $watermark_transparency, $watermark_position) {
    $original_filename = htmlspecialchars_decode($original_filename, ENT_COMPAT | ENT_QUOTES);
    $dest_filename = htmlspecialchars_decode($dest_filename, ENT_COMPAT | ENT_QUOTES);

    $watermark_transparency = 127 - ((100 - $watermark_transparency) * 1.27);
    list($width, $height, $type) = getimagesize($original_filename);
    $watermark_image = imagecreatetruecolor($width, $height);

    $watermark_color = $this->bwg_hex2rgb($watermark_color);
    $watermark_color = imagecolorallocatealpha($watermark_image, $watermark_color[0], $watermark_color[1], $watermark_color[2], $watermark_transparency);
    $watermark_font = WD_S_DIR . '/fonts/' . $watermark_font;
    $watermark_font_size = ($height * $watermark_font_size / 500);
    $watermark_position = explode('-', $watermark_position);
    $watermark_sizes = $this->bwg_imagettfbboxdimensions($watermark_font_size, 0, $watermark_font, $watermark_text);

    $top = $height - 5;
    $left = $width - $watermark_sizes['width'] - 5;
    switch ($watermark_position[0]) {
      case 'top':
        $top = $watermark_sizes['height'] + 5;
        break;
      case 'middle':
        $top = ($height + $watermark_sizes['height']) / 2;
        break;
    }
    switch ($watermark_position[1]) {
      case 'left':
        $left = 5;
        break;
      case 'center':
        $left = ($width - $watermark_sizes['width']) / 2;
        break;
    }
    ini_set('memory_limit', '-1');
    if ($type == 2) {
      $image = imagecreatefromjpeg($original_filename);
      imagettftext($image, $watermark_font_size, 0, $left, $top, $watermark_color, $watermark_font, $watermark_text);
      imagejpeg ($image, $dest_filename, 100);
      imagedestroy($image);  
    }
    elseif ($type == 3) {
      $image = imagecreatefrompng($original_filename);
      imagettftext($image, $watermark_font_size, 0, $left, $top, $watermark_color, $watermark_font, $watermark_text);
      imageColorAllocateAlpha($image, 0, 0, 0, 127);
      imagealphablending($image, FALSE);
      imagesavealpha($image, TRUE);
      imagepng($image, $dest_filename, 9);
      imagedestroy($image);
    }
    elseif ($type == 1) {
      $image = imagecreatefromgif($original_filename);
      imageColorAllocateAlpha($watermark_image, 0, 0, 0, 127);
      imagecopy($watermark_image, $image, 0, 0, 0, 0, $width, $height);
      imagettftext($watermark_image, $watermark_font_size, 0, $left, $top, $watermark_color, $watermark_font, $watermark_text);
      imagealphablending($watermark_image, FALSE);
      imagesavealpha($watermark_image, TRUE);
      imagegif($watermark_image, $dest_filename);
      imagedestroy($image);
    }
    imagedestroy($watermark_image);
    ini_restore('memory_limit');
  }

  function set_image_watermark($original_filename, $dest_filename, $watermark_url, $watermark_height, $watermark_width, $watermark_position) {
    $original_filename = htmlspecialchars_decode($original_filename, ENT_COMPAT | ENT_QUOTES);
    $dest_filename = htmlspecialchars_decode($dest_filename, ENT_COMPAT | ENT_QUOTES);
    $watermark_url = htmlspecialchars_decode($watermark_url, ENT_COMPAT | ENT_QUOTES);

    list($width, $height, $type) = getimagesize($original_filename);
    list($width_watermark, $height_watermark, $type_watermark) = getimagesize($watermark_url);

    $watermark_width = $width * $watermark_width / 100;
    $watermark_height = $height_watermark * $watermark_width / $width_watermark;
        
    $watermark_position = explode('-', $watermark_position);
    $top = $height - $watermark_height - 5;
    $left = $width - $watermark_width - 5;
    switch ($watermark_position[0]) {
      case 'top':
        $top = 5;
        break;
      case 'middle':
        $top = ($height - $watermark_height) / 2;
        break;
    }
    switch ($watermark_position[1]) {
      case 'left':
        $left = 5;
        break;
      case 'center':
        $left = ($width - $watermark_width) / 2;
        break;
    }
    ini_set('memory_limit', '-1');
    if ($type_watermark == 2) {
      $watermark_image = imagecreatefromjpeg($watermark_url);        
    }
    elseif ($type_watermark == 3) {
      $watermark_image = imagecreatefrompng($watermark_url);
    }
    elseif ($type_watermark == 1) {
      $watermark_image = imagecreatefromgif($watermark_url);      
    }
    else {
      return false;
    }

    $watermark_image_resized = imagecreatetruecolor($watermark_width, $watermark_height);
    imagecolorallocatealpha($watermark_image_resized, 255, 255, 255, 127);
    imagealphablending($watermark_image_resized, FALSE);
    imagesavealpha($watermark_image_resized, TRUE);
    imagecopyresampled ($watermark_image_resized, $watermark_image, 0, 0, 0, 0, $watermark_width, $watermark_height, $width_watermark, $height_watermark);
        
    if ($type == 2) {
      $image = imagecreatefromjpeg($original_filename);
      imagecopy($image, $watermark_image_resized, $left, $top, 0, 0, $watermark_width, $watermark_height);
      if ($dest_filename <> '') {
        imagejpeg ($image, $dest_filename, 100); 
      } else {
        header('Content-Type: image/jpeg');
        imagejpeg($image, null, 100);
      };
      imagedestroy($image);  
    }
    elseif ($type == 3) {
      $image = imagecreatefrompng($original_filename);
      imagecopy($image, $watermark_image_resized, $left, $top, 0, 0, $watermark_width, $watermark_height);
      imagealphablending($image, FALSE);
      imagesavealpha($image, TRUE);
      imagepng($image, $dest_filename, 9);
      imagedestroy($image);
    }
    elseif ($type == 1) {
      $image = imagecreatefromgif($original_filename);
      $tempimage = imagecreatetruecolor($width, $height);
      imagecopy($tempimage, $image, 0, 0, 0, 0, $width, $height);
      imagecopy($tempimage, $watermark_image_resized, $left, $top, 0, 0, $watermark_width, $watermark_height);
      imagegif($tempimage, $dest_filename);
      imagedestroy($image);
      imagedestroy($tempimage);
    }
    imagedestroy($watermark_image);
    ini_restore('memory_limit');
  }

  public function delete($id) {
    global $wpdb;
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $id);
    if ($wpdb->query($query)) {
      $query_image = $wpdb->prepare('DELETE t1.*, t2.* FROM ' . $wpdb->prefix . 'wdsslide as t1 INNER JOIN ' . $wpdb->prefix . 'wdslayer as t2 ON t1.id=t2.slide_id WHERE t1.slider_id="%d"', $id);
      $wpdb->query($query_image);
      echo WDW_S_Library::message('Item Succesfully Deleted.', 'updated');
    }
    else {
      echo WDW_S_Library::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function delete_all() {
    global $wpdb;
    $flag = FALSE;
    $sliders_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wdsslider');
    foreach ($sliders_ids_col as $slider_id) {
      if (isset($_POST['check_' . $slider_id]) || isset($_POST['check_all_items'])) {
        $flag = TRUE;
        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $slider_id);
        $wpdb->query($query);
        $query_image = $wpdb->prepare('DELETE t1.*, t2.* FROM ' . $wpdb->prefix . 'wdsslide as t1 INNER JOIN ' . $wpdb->prefix . 'wdslayer as t2 ON t1.id=t2.slide_id WHERE t1.slider_id="%d"', $slider_id);
        $wpdb->query($query_image);
      }
    }
    if ($flag) {
      echo WDW_S_Library::message('Items Succesfully Deleted.', 'updated');
    }
    else {
      echo WDW_S_Library::message('You must select at least one item.', 'error');
    }
    $this->display();
  }

  public function publish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'wdsslider', array('published' => 1), array('id' => $id));
    if ($save !== FALSE) {
      echo WDW_S_Library::message('Item Succesfully Published.', 'updated');
    }
    else {
      echo WDW_S_Library::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function publish_all() {
    global $wpdb;
    $flag = FALSE;
    if (isset($_POST['check_all_items'])) {
      $wpdb->query('UPDATE ' .  $wpdb->prefix . 'wdsslider SET published=1');
      $flag = TRUE;
    }
    else {
      $sliders_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wdsslider');
      foreach ($sliders_ids_col as $slider_id) {
        if (isset($_POST['check_' . $slider_id])) {
          $flag = TRUE;
          $wpdb->update($wpdb->prefix . 'wdsslider', array('published' => 1), array('id' => $slider_id));
        }
      }
    }
    if ($flag) {
      echo WDW_S_Library::message('Items Succesfully Published.', 'updated');
    }
    else {
      echo WDW_S_Library::message('You must select at least one item.', 'error');
    }
    $this->display();
  }

  public function unpublish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'wdsslider', array('published' => 0), array('id' => $id));
    if ($save !== FALSE) {
      echo WDW_S_Library::message('Item Succesfully Unpublished.', 'updated');
    }
    else {
      echo WDW_S_Library::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function unpublish_all() {
    global $wpdb;
    $flag = FALSE;
    if (isset($_POST['check_all_items'])) {
      $wpdb->query('UPDATE ' .  $wpdb->prefix . 'wdsslider SET published=0');
      $flag = TRUE;
    }
    else {
      $sliders_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wdsslider');
      foreach ($sliders_ids_col as $slider_id) {
        if (isset($_POST['check_' . $slider_id])) {
          $flag = TRUE;
          $wpdb->update($wpdb->prefix . 'wdsslider', array('published' => 0), array('id' => $slider_id));
        }
      }
    }
    if ($flag) {
      echo WDW_S_Library::message('Items Succesfully Unpublished.', 'updated');
    }
    else {
      echo WDW_S_Library::message('You must select at least one item.', 'error');
    }
    $this->display();
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}