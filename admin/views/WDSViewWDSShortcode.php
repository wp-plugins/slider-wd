<?php

class WDSViewWDSShortcode {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    $rows = $this->model->get_row_data();
    ?>
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <title>Slider WD</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
        <?php
        wp_print_scripts('jquery');
        ?>
        <base target="_self">
      </head>
      <body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" dir="ltr" class="forceColors">
        <div class="tabs" role="tablist" tabindex="-1">
          <ul>
            <li id="display_tab" class="current" role="tab" tabindex="0">
              <span>
                <a href="javascript:mcTabs.displayTab('display_tab','display_panel');" onMouseDown="return false;" tabindex="-1">Slider WD</a>
              </span>
            </li>
          </ul>
        </div>
        <div class="panel_wrapper" style="height: 170px !important;">
          <div id="display_panel" class="panel current">
            <table>
              <tr>
                <td style="vertical-align: middle; text-align: left;">Select a Slider</td>
                <td style="vertical-align: middle; text-align: left;">
                  <select name="wds_id" id="wds_id" style="width: 230px; text-align: left;">
                    <option value="0" selected="selected">- Select a Slider -</option>
                    <?php
                    foreach ($rows as $row) {
                      ?>
                    <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="mceActionPanel">
          <div style="float: left;">
            <input type="button" id="cancel" name="cancel" value="Cancel" onClick="tinyMCEPopup.close();"/>
          </div>
          <div style="float: right;">
            <input type="submit" id="insert" name="insert" value="Insert" onClick="wds_insert_shortcode();"/>
          </div>
        </div>
        <script type="text/javascript">
          function wds_insert_shortcode() {
            if (document.getElementById("wds_id").value) {
              var tagtext = '[wds id="' + document.getElementById('wds_id').value + '"]';
              window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
            }
            tinyMCEPopup.close();
          }
        </script>
      </body>
    </html>
    <?php
    die();
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