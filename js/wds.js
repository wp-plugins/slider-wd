function spider_select_value(obj) {
  obj.focus();
  obj.select();
}

function spider_run_checkbox() {
  jQuery("tbody").children().children(".check-column").find(":checkbox").click(function (l) {
    if ("undefined" == l.shiftKey) {
      return true
    }
    if (l.shiftKey) {
      if (!i) {
        return true
      }
      d = jQuery(i).closest("form").find(":checkbox");
      f = d.index(i);
      j = d.index(this);
      h = jQuery(this).prop("checked");
      if (0 < f && 0 < j && f != j) {
        d.slice(f, j).prop("checked", function () {
          if (jQuery(this).closest("tr").is(":visible")) {
            return h
          }
          return false
        })
      }
    }
    i = this;
    var k = jQuery(this).closest("tbody").find(":checkbox").filter(":visible").not(":checked");
    jQuery(this).closest("table").children("thead, tfoot").find(":checkbox").prop("checked", function () {
      return(0 == k.length)
    });
    return true
  });
  jQuery("thead, tfoot").find(".check-column :checkbox").click(function (m) {
    var n = jQuery(this).prop("checked"), l = "undefined" == typeof toggleWithKeyboard ? false : toggleWithKeyboard, k = m.shiftKey || l;
    jQuery(this).closest("table").children("tbody").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
      if (jQuery(this).is(":hidden")) {
        return false
      }
      if (k) {
        return jQuery(this).prop("checked")
      } else {
        if (n) {
          return true
        }
      }
      return false
    });
    jQuery(this).closest("table").children("thead,  tfoot").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
      if (k) {
        return false
      } else {
        if (n) {
          return true
        }
      }
      return false
    })
  });
}

// Set value by id.
function spider_set_input_value(input_id, input_value) {
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = input_value;
  }
}

// Submit form by id.
function spider_form_submit(event, form_id) {
  if (document.getElementById(form_id)) {
    document.getElementById(form_id).submit();
  }
  if (event.preventDefault) {
    event.preventDefault();
  }
  else {
    event.returnValue = false;
  }
}

// Check if required field is empty.
function spider_check_required(id, name) {
  if (jQuery('#' + id).val() == '') {
    alert(name + '* field is required.');
    jQuery('#' + id).attr('style', 'border-color: #FF0000;');
    jQuery('#' + id).focus();
    jQuery('html, body').animate({
      scrollTop:jQuery('#' + id).offset().top - 200
    }, 500);
    return true;
  }
  else {
    return false;
  }
}

function wds_check_required(id, name) {
  if (jQuery('#' + id).val() == '') {
    alert(name + '* field is required.');
    wds_change_tab(jQuery(".wds_tab_label[tab_type='slides']"), 'wds_slides_box');
    /*wds_change_tab(jQuery(".wds_tab_label[tab_type='settings']"), 'wds_settings_box');
    wds_change_nav(jQuery(".wds_nav_tabs li[tab_type='global']"), 'wds_nav_global_box');*/
    jQuery('#' + id).attr('style', 'border-color: #FF0000;');
    jQuery('#' + id).focus();
    jQuery('html, body').animate({
      scrollTop:jQuery('#' + id).offset().top - 200
    }, 500);
    return true;
  }
  else {
    return false;
  }
}

// Show/hide order column and drag and drop column.
function spider_show_hide_weights() {
  if (jQuery("#show_hide_weights").val() == 'Show order column') {
    jQuery(".connectedSortable").css("cursor", "default");
    jQuery("#tbody_arr").find(".handle").hide(0);
    jQuery("#th_order").show(0);
    jQuery("#tbody_arr").find(".spider_order").show(0);
    jQuery("#show_hide_weights").val("Hide order column");
    if (jQuery("#tbody_arr").sortable()) {
      jQuery("#tbody_arr").sortable("disable");
    }
  }
  else {
    jQuery(".connectedSortable").css("cursor", "move");
    var page_number;
    if (jQuery("#page_number") && jQuery("#page_number").val() != '' && jQuery("#page_number").val() != 1) {
      page_number = (jQuery("#page_number").val() - 1) * 20 + 1;
    }
    else {
      page_number = 1;
    }
    jQuery("#tbody_arr").sortable({
      handle:".connectedSortable",
      connectWith:".connectedSortable",
      update:function (event, tr) {
        jQuery("#draganddrop").attr("style", "");
        jQuery("#draganddrop").html("<strong><p>Changes made in this table should be saved.</p></strong>");
        var i = page_number;
        jQuery('.spider_order').each(function (e) {
          if (jQuery(this).find('input').val()) {
            jQuery(this).find('input').val(i++);
          }
        });
      }
    });//.disableSelection();
    jQuery("#tbody_arr").sortable("enable");
    jQuery("#tbody_arr").find(".handle").show(0);
    jQuery("#tbody_arr").find(".handle").attr('class', 'handle connectedSortable');
    jQuery("#th_order").hide(0);
    jQuery("#tbody_arr").find(".spider_order").hide(0);
    jQuery("#show_hide_weights").val("Show order column");
  }
}

// Check all items.
function spider_check_all_items() {
  spider_check_all_items_checkbox();
  // if (!jQuery('#check_all').attr('checked')) {
    jQuery('#check_all').trigger('click');
  // }
}
function spider_check_all_items_checkbox() {
  if (jQuery('#check_all_items').attr('checked')) {
    jQuery('#check_all_items').attr('checked', false);
    jQuery('#draganddrop').hide();
  }
  else {
    var saved_items = (parseInt(jQuery(".displaying-num").html()) ? parseInt(jQuery(".displaying-num").html()) : 0);
    var added_items = (jQuery('input[id^="check_pr_"]').length ? parseInt(jQuery('input[id^="check_pr_"]').length) : 0);
    var items_count = added_items + saved_items;
    jQuery('#check_all_items').attr('checked', true);
    if (items_count) {
      jQuery('#draganddrop').html("<strong><p>Selected " + items_count + " item" + (items_count > 1 ? "s" : "") + ".</p></strong>");
      jQuery('#draganddrop').show();
    }
  }
}

function spider_check_all(current) {
  if (!jQuery(current).attr('checked')) {
    jQuery('#check_all_items').attr('checked', false);
    jQuery('#draganddrop').hide();
  }
}

// Set uploader to button class.
function spider_uploader(button_id, input_id, delete_id, img_id) {
  if (typeof img_id == 'undefined') {
    img_id = '';
  }
  jQuery(function () {
    var formfield = null;
    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function (html) {
      if (formfield) {
        var fileurl = jQuery('img', html).attr('src');
        if (!fileurl) {
          var exploded_html;
          var exploded_html_askofen;
          exploded_html = html.split('"');
          for (i = 0; i < exploded_html.length; i++) {
            exploded_html_askofen = exploded_html[i].split("'");
          }
          for (i = 0; i < exploded_html.length; i++) {
            for (j = 0; j < exploded_html_askofen.length; j++) {
              if (exploded_html_askofen[j].search("href")) {
                fileurl = exploded_html_askofen[i + 1];
                break;
              }
            }
          }
          if (img_id != '') {
            alert('You must select an image file.');
            tb_remove();
            return;
          }
          window.parent.document.getElementById(input_id).value = fileurl;
          window.parent.document.getElementById(button_id).style.display = "none";
          window.parent.document.getElementById(input_id).style.display = "inline-block";
          window.parent.document.getElementById(delete_id).style.display = "inline-block";
        }
        else {
          if (img_id == '') {
            alert('You must select an audio file.');
            tb_remove();
            return;
          }
          window.parent.document.getElementById(input_id).value = fileurl;
          window.parent.document.getElementById(button_id).style.display = "none";
          window.parent.document.getElementById(delete_id).style.display = "inline-block";
          if ((img_id != '') && window.parent.document.getElementById(img_id)) {
            window.parent.document.getElementById(img_id).src = fileurl;
            window.parent.document.getElementById(img_id).style.display = "inline-block";
          }
        }
        formfield.val(fileurl);
        tb_remove();
      }
      else {
        window.original_send_to_editor(html);
      }
      formfield = null;
    };
    formfield = jQuery(this).parent().parent().find(".url_input");
    tb_show('', 'media-upload.php?type=image&TB_iframe=true');
    jQuery('#TB_overlay,#TB_closeWindowButton').bind("click", function () {
      formfield = null;
    });
    return false;
  });
}

// Remove uploaded file.
function spider_remove_url(button_id, input_id, delete_id, img_id) {
  if (typeof img_id == 'undefined') {
    img_id = '';
  }
  if (document.getElementById(button_id)) {
    // document.getElementById(button_id).style.display = '';
  }
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = '';
    // document.getElementById(input_id).style.display = 'none';
  }
  if (document.getElementById(delete_id)) {
    // document.getElementById(delete_id).style.display = 'none';
  }
  if ((img_id != '') && document.getElementById(img_id)) {
    document.getElementById(img_id).style.backgroundImage = "url('')";
    // document.getElementById(img_id).src = '';
    // document.getElementById(img_id).style.display = 'none';
  }
}

function spider_reorder_items(tbody_id) {
  jQuery("#" + tbody_id).sortable({
    handle: ".connectedSortable",
    connectWith: ".connectedSortable",
    update: function (event, tr) {
      spider_sortt(tbody_id);
    }
  });
}

function spider_sortt(tbody_id) {
  var str = "";
  var counter = 0;
  jQuery("#" + tbody_id).children().each(function () {
    str += ((jQuery(this).attr("id")).substr(3) + ",");
    counter++;
  });
  jQuery("#albums_galleries").val(str);
  if (!counter) {
    document.getElementById("table_albums_galleries").style.display = "none";
  }
}

function spider_remove_row(tbody_id, event, obj) {
  var span = obj;
  var tr = jQuery(span).closest("tr");
  jQuery(tr).remove();
  spider_sortt(tbody_id);
}

function spider_jslider(idtaginp) {
  jQuery(function () {
    var inpvalue = jQuery("#" + idtaginp).val();
    if (inpvalue == "") {
      inpvalue = 50;
    }
    jQuery("#slider-" + idtaginp).slider({
      range:"min",
      value:inpvalue,
      min:1,
      max:100,
      slide:function (event, ui) {
        jQuery("#" + idtaginp).val("" + ui.value);
      }
    });
    jQuery("#" + idtaginp).val("" + jQuery("#slider-" + idtaginp).slider("value"));
  });
}

function spider_get_items(e) {
  if (e.preventDefault) {
    e.preventDefault();
  }
  else {
    e.returnValue = false;
  }
  var trackIds = [];
  var titles = [];
  var types = [];
  var tbody = document.getElementById('tbody_albums_galleries');
  var trs = tbody.getElementsByTagName('tr');
  for (j = 0; j < trs.length; j++) {
    i = trs[j].getAttribute('id').substr(3);
    if (document.getElementById('check_' + i).checked) {
      trackIds.push(document.getElementById("id_" + i).innerHTML);
      titles.push(document.getElementById("a_" + i).innerHTML);
      types.push(document.getElementById("url_" + i).innerHTML == "Album" ? 1 : 0);
    }
  }
  window.parent.bwg_add_items(trackIds, titles, types);
}

function bwg_check_checkboxes() { 
  var flag = false;
  var ids_string = jQuery("#ids_string").val();
  ids_array = ids_string.split(",");
  for (var i in ids_array) {
    if (ids_array.hasOwnProperty(i) && ids_array[i]) {
      if (jQuery("#check_" + ids_array[i]).attr('checked') == 'checked') {
        flag = true;
      }
	}
  }
  if(flag) {
    if(jQuery(".buttons_div_right").find("a").hasClass( "thickbox" )) {       
      return true; 
	}
	else { 
	  jQuery(".buttons_div_right").find("a").addClass( "thickbox thickbox-preview" );
	  jQuery('#draganddrop').hide();
	  return true;
	}
  } 
  else { 
	jQuery(".buttons_div_right").find("a").removeClass( "thickbox thickbox-preview" );
    jQuery('#draganddrop').html("<strong><p>You must select at least one item.</p></strong>");
    jQuery('#draganddrop').show();
	return false;
  }  
}

function preview_built_in_watermark() {
  setTimeout(function() {
    watermark_type = window.parent.document.getElementById('built_in_watermark_type_text').checked;
    if (watermark_type) {
      watermark_text = document.getElementById('built_in_watermark_text').value;
      watermark_font_size = document.getElementById('built_in_watermark_font_size').value * 400 / 500;
      watermark_font = 'bwg_' + document.getElementById('built_in_watermark_font').value.replace('.TTF', '').replace('.ttf', '');
      watermark_color = document.getElementById('built_in_watermark_color').value;
      watermark_opacity = 100 - document.getElementById('built_in_watermark_opacity').value;
      watermark_position = jQuery("input[name=built_in_watermark_position]:checked").val().split('-');
      document.getElementById("preview_built_in_watermark").style.verticalAlign = watermark_position[0];
      document.getElementById("preview_built_in_watermark").style.textAlign = watermark_position[1];
      stringHTML = '<span style="cursor:default;margin:4px;font-size:' + watermark_font_size + 'px;font-family:' + watermark_font + ';color:#' + watermark_color + ';opacity:' + (watermark_opacity / 100) + ';filter: Alpha(opacity=' + watermark_opacity + ');" class="non_selectable">' + watermark_text + '</span>';
      document.getElementById("preview_built_in_watermark").innerHTML = stringHTML;
    }
    watermark_type = window.parent.document.getElementById('built_in_watermark_type_image').checked;
    if (watermark_type) {
      watermark_url = document.getElementById('built_in_watermark_url').value;
      watermark_size = document.getElementById('built_in_watermark_size').value;
      watermark_position = jQuery("input[name=built_in_watermark_position]:checked").val().split('-');
      document.getElementById("preview_built_in_watermark").style.verticalAlign = watermark_position[0];
      document.getElementById("preview_built_in_watermark").style.textAlign = watermark_position[1];
      stringHTML = '<img class="non_selectable" src="' + watermark_url + '" style="margin:0 4px 0 4px;max-width:95%;width:' + watermark_size + '%;" />';
      document.getElementById("preview_built_in_watermark").innerHTML = stringHTML;
    }
  }, 50);
}

function bwg_built_in_watermark(watermark_type) {
  jQuery("#built_in_" + watermark_type).attr('checked', 'checked');
  jQuery("#tr_built_in_watermark_url").css('display', 'none');
  jQuery("#tr_built_in_watermark_size").css('display', 'none');
  jQuery("#tr_built_in_watermark_opacity").css('display', 'none');
  jQuery("#tr_built_in_watermark_text").css('display', 'none');
  jQuery("#tr_built_in_watermark_font_size").css('display', 'none');
  jQuery("#tr_built_in_watermark_font").css('display', 'none');
  jQuery("#tr_built_in_watermark_color").css('display', 'none');
  jQuery("#tr_built_in_watermark_position").css('display', 'none');
  jQuery("#tr_built_in_watermark_preview").css('display', 'none');
  jQuery("#preview_built_in_watermark").css('display', 'none');
  switch (watermark_type) {
    case 'watermark_type_text':
    {
      jQuery("#tr_built_in_watermark_opacity").css('display', '');
      jQuery("#tr_built_in_watermark_text").css('display', '');
      jQuery("#tr_built_in_watermark_font_size").css('display', '');
      jQuery("#tr_built_in_watermark_font").css('display', '');
      jQuery("#tr_built_in_watermark_color").css('display', '');
      jQuery("#tr_built_in_watermark_position").css('display', '');
      jQuery("#tr_built_in_watermark_preview").css('display', '');
      jQuery("#preview_built_in_watermark").css('display', 'table-cell');
      break;
    }
    case 'watermark_type_image':
    {
      jQuery("#tr_built_in_watermark_url").css('display', '');
      jQuery("#tr_built_in_watermark_size").css('display', '');
      jQuery("#tr_built_in_watermark_position").css('display', '');
      jQuery("#tr_built_in_watermark_preview").css('display', '');
      jQuery("#preview_built_in_watermark").css('display', 'table-cell');
      break;
    }
  }
}

function bwg_inputs() {
  jQuery(".spider_int_input").keypress(function (event) {
    var chCode1 = event.which || event.paramlist_keyCode;
    if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57) && (chCode1 != 46) && (chCode1 != 45)) {
      return false;
    }
    return true;
  });
}

function bwg_enable_disable(display, id, current) {
  jQuery("#" + current).attr('checked', 'checked');
  jQuery("#" + id).css('display', display);
}

function bwg_change_album_view_type(type) {
  if (type == 'thumbnail') {
    jQuery("#album_thumb_dimensions").html('Album thumb dimensions: ');
	jQuery("#album_thumb_dimensions_x").css('display', '');
	jQuery("#album_thumb_height").css('display', '');
  }
  else {
    jQuery("#album_thumb_dimensions").html('Album thumb width: '); 
    jQuery("#album_thumb_dimensions_x").css('display', 'none');
	jQuery("#album_thumb_height").css('display', 'none');
  }
}

function spider_check_isnum(e) {
  var chCode1 = e.which || e.paramlist_keyCode;
  if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57) && (chCode1 != 46) && (chCode1 != 45)) {
    return false;
  }
  return true;
}

function wds_add_image_url(id) {
  jQuery('#add_image_url_button').attr("onclick", "if (spider_set_image_url('" + id + "')) {jQuery('.opacity_add_image_url').hide();} return false;");
  jQuery('.opacity_add_image_url').show();
  return false;
}

function spider_set_image_url(id) {
  if (!jQuery("#image_url_input").val()) {
    return false;
  }
  jQuery("#image_url" + id).val(jQuery("#image_url_input").val());
  jQuery("#thumb_url" + id).val(jQuery("#image_url_input").val());
  jQuery("#wds_preview_image" + id).css("background-image", "url('" + jQuery("#image_url_input").val() + "')");
  jQuery("#delete_image_url" + id).css("display", "inline-block");
  jQuery("#wds_preview_image" + id).css("display", "inline-block");
  jQuery("#image_url_input").val("");
  jQuery("#type" + id).val("image");
  jQuery("#trlink" + id).show();
  return true;
}

function spider_media_uploader(id, e) {
  var custom_uploader;
  e.preventDefault();
  // If the uploader object has already been created, reopen the dialog.
  if (custom_uploader) {
    custom_uploader.open();
    // return;
  }
  // Extend the wp.media object.
  var library_type = (id == 'music') ? 'audio' : 'image'
  custom_uploader = wp.media.frames.file_frame = wp.media({
    title: 'Choose ' + library_type,
    library : { type : library_type},
    button: { text: 'Insert'},
    multiple: false
  });
  // When a file is selected, grab the URL and set it as the text field's value
  custom_uploader.on('select', function() {
    attachment = custom_uploader.state().get('selection').first().toJSON();
    var image_url = attachment.url;
    var thumb_url = (attachment.sizes && attachment.sizes.thumbnail)  ? attachment.sizes.thumbnail.url : image_url;
    switch (id) {
      case 'settings': {
        document.getElementById("background_image_url").value = image_url;
        document.getElementById("background_image").src = image_url;
        document.getElementById("button_bg_img").style.display = "none";
        document.getElementById("delete_bg_img").style.display = "inline-block";
        document.getElementById("background_image").style.display = "";
        document.getElementById("background_image_url").style.display = "";
        break;
      }
      case 'watermark': {
        document.getElementById("built_in_watermark_url").value = image_url; 
        preview_built_in_watermark();
        break;
      }
      case 'music': {
        var music_url = image_url;
        document.getElementById("music_url").value = music_url;
        break;
      }
      default: {
        jQuery("#image_url" + id).val(image_url);
        jQuery("#thumb_url" + id).val(thumb_url);
        jQuery("#wds_preview_image" + id).css("background-image", "url('" + image_url + "')");
        jQuery("#delete_image_url" + id).css("display", "inline-block");
        jQuery("#wds_preview_image" + id).css("display", "inline-block");
        jQuery("#type" + id).val("image");
        jQuery("#trlink" + id).show();
      }
	  }
  });
  // Open the uploader dialog.
  custom_uploader.open();
}

function wds_change_sub_tab(that, box) {
  jQuery("#sub_tab").val(jQuery(that).attr("tab_type"));
  jQuery(".wds_tabs a").removeClass("wds_sub_active");
  jQuery(that).parent().addClass("wds_sub_active");
  jQuery(".wds_box").removeClass("wds_sub_active");
  jQuery("." + box).addClass("wds_sub_active");
  jQuery(".wds_sub_active .wds_tab_title").select();
  jQuery(".wds_sub_active .wds_tab_title").focus();
}

function wds_change_tab(that, box) {
  jQuery("#tab").val(jQuery(that).attr("tab_type"));
  jQuery(".wds_tabs a").removeClass("wds_active");
  jQuery(that).parent().addClass("wds_active");
  jQuery(".wds_box").removeClass("wds_active");
  jQuery("." + box).addClass("wds_active");
}

function wds_change_nav(that, box) {
  jQuery("#nav_tab").val(jQuery(that).attr("tab_type"));
  jQuery(".wds_nav_tabs li").removeClass("wds_active");
  jQuery(that).addClass("wds_active");
  jQuery(".wds_nav_box").removeClass("wds_active");
  jQuery("." + box).addClass("wds_active");
}

function wds_showhide_layer(tbodyID, always_show) {
  jQuery(".wds_layer_tr").not("#" + tbodyID + " .wds_layer_tr").hide();
  jQuery("#" + tbodyID).children().each(function() {
    if (!jQuery(this).hasClass("wds_layer_head_tr")) {
      if (jQuery(this).is(':hidden') || always_show) {
        jQuery(this).show();
      }
      else {
        jQuery(this).hide();
      }
    }
  });
}

function wds_delete_layer(id, layerID) {
  if (confirm("Do you want to delete layer?")) {
    var prefix = "slide" + id + "_layer" + layerID;
    jQuery("#" + prefix).remove();
    jQuery("#" + prefix + "_tbody").remove();

    var layerIDs = jQuery("#slide" + id + "_layer_ids_string").val();
    layerIDs = layerIDs.replace(layerID + ",", "");
    jQuery("#slide" + id + "_layer_ids_string").val(layerIDs);
    var dellayerIds = jQuery("#slide" + id + "_del_layer_ids_string").val() + layerID + ",";
    jQuery("#slide" + id + "_del_layer_ids_string").val(dellayerIds);
  }
}

function wds_duplicate_layer(type, id, layerID) {
  var prefix = "slide" + id + "_layer" + layerID;
  var new_layerID = "pr_" + wds_layerID;
  var new_prefix = "slide" + id + "_layer" + new_layerID;
  var new_tbodyID = new_prefix + "_tbody";
  jQuery("#" + new_prefix + "_text").val(jQuery("#" + prefix + "_text").val());
  jQuery("#" + new_prefix + "_link").val(jQuery("#" + prefix + "_link").val());
  jQuery("#" + new_prefix + "_left").val(0);
  jQuery("#" + new_prefix + "_top").val(0);
  jQuery("#" + new_prefix + "_start").val(jQuery("#" + prefix + "_start").val());
  jQuery("#" + new_prefix + "_end").val(jQuery("#" + prefix + "_end").val());
  jQuery("#" + new_prefix + "_delay").val(jQuery("#" + prefix + "_delay").val());
  jQuery("#" + new_prefix + "_duration_eff_in").val(jQuery("#" + prefix + "_duration_eff_in").val());
  jQuery("#" + new_prefix + "_duration_eff_out").val(jQuery("#" + prefix + "_duration_eff_out").val());
  jQuery("#" + new_prefix + "_color").val(jQuery("#" + prefix + "_color").val());
  jQuery("#" + new_prefix + "_size").val(jQuery("#" + prefix + "_size").val());
  jQuery("#" + new_prefix + "_padding").val(jQuery("#" + prefix + "_padding").val());
  jQuery("#" + new_prefix + "_fbgcolor").val(jQuery("#" + prefix + "_fbgcolor").val());
  jQuery("#" + new_prefix + "_transparent").val(jQuery("#" + prefix + "_transparent").val());
  jQuery("#" + new_prefix + "_border_width").val(jQuery("#" + prefix + "_border_width").val());
  jQuery("#" + new_prefix + "_border_color").val(jQuery("#" + prefix + "_border_color").val());
  jQuery("#" + new_prefix + "_border_radius").val(jQuery("#" + prefix + "_border_radius").val());
  jQuery("#" + new_prefix + "_shadow").val(jQuery("#" + prefix + "_shadow").val());
  jQuery("#" + new_prefix + "_image_url").val(jQuery("#" + prefix + "_image_url").val());
  jQuery("#" + new_prefix + "_image_width").val(jQuery("#" + prefix + "_image_width").val());
  jQuery("#" + new_prefix + "_image_height").val(jQuery("#" + prefix + "_image_height").val());
  jQuery("#" + new_prefix + "_alt").val(jQuery("#" + prefix + "_alt").val());
  jQuery("#" + new_prefix + "_imgtransparent").val(jQuery("#" + prefix + "_imgtransparent").val());
  jQuery("#" + new_prefix + "_hover_color").val(jQuery("#" + prefix + "_hover_color").val());
  jQuery("#" + new_prefix + "_type").val(jQuery("#" + prefix + "_type").val());
  if (jQuery("#" + prefix + "_published1").is(":checked")) {
    jQuery("#" + new_prefix + "_published1").attr("checked", "checked");
  }
  else if (jQuery("#" + prefix + "_published0").is(":checked")) {
    jQuery("#" + new_prefix + "_published0").attr("checked", "checked");
  }
  if (jQuery("#" + prefix + "_image_scale").is(":checked")) {
    jQuery("#" + new_prefix + "_image_scale").attr("checked", "checked");
  }
  jQuery("#" + new_prefix + "_transition option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_transition").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_ffamily option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_ffamily").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_fweight option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_fweight").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_border_style option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_border_style").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_social_button option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_social_button").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_layer_effect_in option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_layer_effect_in").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  jQuery("#" + new_prefix + "_layer_effect_out option").each(function() {
    if (jQuery(this).val() == jQuery("#" + prefix + "_layer_effect_out").val()) {
      jQuery(this).attr("selected", "selected");
    }
  });
  if (type == "text") {
    wds_new_line(new_prefix);
    jQuery("#" + new_prefix).attr({
      id: new_prefix,
      onclick: "wds_showhide_layer('" + new_prefix + "_tbody', 1)",
      style: "z-index: " + jQuery("#" + new_prefix + "_depth").val() +
             "left: 0; top: 0; display: inline-block;" +
             "color: #" + jQuery("#" + prefix + "_color").val() + "; " +
             "font-size: " + jQuery("#" + prefix + "_size").val() + "px; " +
             "line-height: " + jQuery("#" + prefix + "_size").val() + "px; " +
             "font-family: " + jQuery("#" + prefix + "_ffamily").val() + "; " +
             "font-weight: " + jQuery("#" + prefix + "_fweight").val() + "; " +
             "padding: " + jQuery("#" + prefix + "_padding").val() + "; " +
             "background-color: " + wds_hex_rgba(jQuery("#" + prefix+ "_fbgcolor").val(), (100 - jQuery("#" + prefix+ "_transparent").val())) + "; " +
             "border: " + jQuery("#" + prefix + "_border_width").val() + "px " + jQuery("#" + prefix+ "_border_style").val() + " #" + jQuery("#" + prefix+ "_border_color").val() + "; " +
             "border-radius: " + jQuery("#" + prefix + "_border_radius").val() + ";"
    });
    wds_text_width("#" + new_prefix + "_image_width", new_prefix);
    wds_text_height("#" + new_prefix + "_image_height", new_prefix);
    wds_break_word("#" + new_prefix + "_image_scale", new_prefix);
  }
  else if (type == "image") {
    jQuery("#wds_preview_image" + id).append(jQuery("<img />").attr({
      id: new_prefix,
      src: jQuery("#" + prefix).attr("src"),
      class: "wds_draggable_" + id + " wds_draggable",
      onclick: "wds_showhide_layer('" + new_prefix + "_tbody', 1)",
      style: "z-index: " + jQuery("#" + new_prefix + "_depth").val() + "; " +
             "left: 0; top: 0; " +
             "opacity: " + (100 - jQuery("#" + prefix + "_imgtransparent").val()) / 100 + "; filter: Alpha(opacity=" + (100 - jQuery("#" + prefix+ "_imgtransparent").val()) + "); " +
             "border: " + jQuery("#" + prefix + "_border_width").val() + "px " + jQuery("#" + prefix+ "_border_style").val() + " #" + jQuery("#" + prefix+ "_border_color").val() + "; " +
             "border-radius: " + jQuery("#" + prefix + "_border_radius").val() + "; " +
             "box-shadow: " + jQuery("#" + prefix + "_shadow").val() + "; " +
             "position: absolute;"
    }));
    wds_scale("#" + new_prefix + "_image_scale", new_prefix);
  }
  jscolor.bind();
  wds_drag_layer(id);
}

var wds_layerID = 0;
function wds_add_layer(type, id, layerID, event, duplicate) {
  var layers_count = jQuery(".wds_slide" + id + " tbody").length;
  wds_layerID = layers_count;
  if (typeof layerID == "undefined" || layerID == "") {
    var layerID = "pr_" + wds_layerID;
    jQuery("#slide" + id + "_layer_ids_string").val(jQuery("#slide" + id + "_layer_ids_string").val() + layerID + ',');
  }
  if (typeof duplicate == "undefined") {
    var duplicate = 0;
  }

  var layer_effects_in_option = "";
  var layer_effects_out_option = "";
  var free_layer_effects = ['none', 'bounce', 'tada', 'bounceInDown', 'bounceOutUp', 'fadeInLeft', 'fadeOutRight'];
  var layer_effects_in = {
    'none' : 'None',
    'bounce' : 'Bounce',
    'tada' : 'Tada',
    'bounceInDown' : 'BounceInDown',
    'fadeInLeft' : 'FadeInLeft',
    'flash' : 'Flash',
    'pulse' : 'Pulse',
    'rubberBand' : 'RubberBand',
    'shake' : 'Shake',
    'swing' : 'Swing',
    'wobble' : 'Wobble',
    'hinge' : 'Hinge',
    'lightSpeedIn' : 'LightSpeedIn',
    'rollIn' : 'RollIn',
	
    'bounceIn' : 'BounceIn',
    'bounceInLeft' : 'BounceInLeft',
    'bounceInRight' : 'BounceInRight',
    'bounceInUp' : 'BounceInUp',

    'fadeIn' : 'FadeIn',
    'fadeInDown' : 'FadeInDown',
    'fadeInDownBig' : 'FadeInDownBig',
    'fadeInLeftBig' : 'FadeInLeftBig',
    'fadeInRight' : 'FadeInRight',
    'fadeInRightBig' : 'FadeInRightBig',
    'fadeInUp' : 'FadeInUp',
    'fadeInUpBig' : 'FadeInUpBig',

    'flip' : 'Flip',
    'flipInX' : 'FlipInX',
    'flipInY' : 'FlipInY',

    'rotateIn' : 'RotateIn',
    'rotateInDownLeft' : 'RotateInDownLeft',
    'rotateInDownRight' : 'RotateInDownRight',
    'rotateInUpLeft' : 'RotateInUpLeft',
    'rotateInUpRight' : 'RotateInUpRight',
	
    'zoomIn' : 'ZoomIn',
    'zoomInDown' : 'ZoomInDown',
    'zoomInLeft' : 'ZoomInLeft',
    'zoomInRight' : 'ZoomInRight',
    'zoomInUp' : 'ZoomInUp',  
  };

  var layer_effects_out = {
    'none' : 'None',
    'bounce' : 'Bounce',
    'tada' : 'Tada',
    'bounceOutUp' : 'BounceOutUp',
    'fadeOutRight' : 'FadeOutRight',
    'flash' : 'Flash',
    'pulse' : 'Pulse',
    'rubberBand' : 'RubberBand',
    'shake' : 'Shake',
    'swing' : 'Swing',
    'wobble' : 'Wobble',
    'hinge' : 'Hinge',
    'lightSpeedOut' : 'LightSpeedOut',
    'rollOut' : 'RollOut',

    'bounceOut' : 'BounceOut',
    'bounceOutDown' : 'BounceOutDown',
    'bounceOutLeft' : 'BounceOutLeft',
    'bounceOutRight' : 'BounceOutRight',

    'fadeOut' : 'FadeOut',
    'fadeOutDown' : 'FadeOutDown',
    'fadeOutDownBig' : 'FadeOutDownBig',
    'fadeOutLeft' : 'FadeOutLeft',
    'fadeOutLeftBig' : 'FadeOutLeftBig',
    'fadeOutRightBig' : 'FadeOutRightBig',
    'fadeOutUp' : 'FadeOutUp',
    'fadeOutUpBig' : 'FadeOutUpBig',

    'flip' : 'Flip',
    'flipOutX' : 'FlipOutX',
    'flipOutY' : 'FlipOutY',

    'rotateOut' : 'RotateOut',
    'rotateOutDownLeft' : 'RotateOutDownLeft',
    'rotateOutDownRight' : 'RotateOutDownRight',
    'rotateOutUpLeft' : 'RotateOutUpLeft',
    'rotateOutUpRight' : 'RotateOutUpRight',

    'zoomOut' : 'ZoomOut',
    'zoomOutDown' : 'ZoomOutDown',
    'zoomOutLeft' : 'ZoomOutLeft',
    'zoomOutRight' : 'ZoomOutRight',
    'zoomOutUp' : 'ZoomOutUp',  
  };

  for (var i in layer_effects_in) {
    layer_effects_in_option += '<option ' + ((jQuery.inArray(i, free_layer_effects) == -1) ? 'disabled="disabled" title="This effect is disabled in free version."' : '') + ' value="' + i + '">' + layer_effects_in[i] + '</option>';
  }
  for (var i in layer_effects_out) {
    layer_effects_out_option += '<option ' + ((jQuery.inArray(i, free_layer_effects) == -1) ? 'disabled="disabled" title="This effect is disabled in free version."' : '') + ' value="' + i + '">' + layer_effects_out[i] + '</option>';
  }
  
  var font_families_option = "";
  var families = {'arial' : 'Arial', 'lucida grande' : 'Lucida grande', 'segoe ui' : 'Segoe ui', 'tahoma' : 'Tahoma', 'trebuchet ms' : 'Trebuchet ms', 'verdana' : 'Verdana', 'cursive' : 'Cursive', 'fantasy' : 'Fantasy', 'monospace' : 'Monospace', 'serif' : 'Serif'};
  for (var i in families) {
    font_families_option += '<option value="' + i + '">' + families[i] + '</option>';
  }
  var font_weights_option = "";
  var font_weights = {'lighter' : 'Lighter', 'normal' : 'Normal', 'bold' : 'Bold'};
  for (var i in font_weights) {
    font_weights_option += '<option value="' + i + '">' + font_weights[i] + '</option>';
  }
  var border_styles_option = "";
  var border_styles = {'none' : 'None', 'solid' : 'Solid', 'dotted' : 'Dotted', 'dashed' : 'Dashed', 'double' : 'Double', 'groove' : 'Groove', 'ridge' : 'Ridge', 'inset' : 'Inset', 'outset' : 'Outset'};
  for (var i in border_styles) {
    border_styles_option += '<option value="' + i + '">' + border_styles[i] + '</option>';
  }
  var social_button_option = "";
  var social_button = {"facebook" : "Facebook", "google-plus" : "Google+", "twitter" : "Twitter", "pinterest" : "Pinterest", "tumblr" : "Tumblr"};
  for (var i in social_button) {
    social_button_option += '<option value="' + i + '">' + social_button[i] + '</option>';
  }

  var prefix = "slide" + id + "_layer" + layerID;
  var tbodyID = prefix + "_tbody";

  jQuery(".wds_slide" + id + " table").append(jQuery("<tbody />").attr("id", tbodyID));
  var tbody = '<tr class="wds_layer_head_tr">' +
                '<td colspan="4" class="wds_layer_head">' +
                  '<div class="handle connectedSortable" title="Drag to re-order"></div>' +
                  '<span class="wds_layer_label" onclick="wds_showhide_layer(\'' + tbodyID + '\', 0)"><input id="' + prefix + '_title" name="' + prefix + '_title" type="text" class="wds_layer_title" style="width: 80px;" value="Layer ' + wds_layerID + '" /></span>' +
                  '<span class="wds_layer_remove" title="Delete layer" onclick="wds_delete_layer(\'' + id + '\', \'' + layerID + '\')"></span>' +
                  '<span class="wds_layer_dublicate" title="Duplicate layer" onclick="wds_add_layer(\'' + type + '\', \'' + id + '\', \'\', event, 1); wds_duplicate_layer(\'' + type + '\', \'' + id + '\', \'' + layerID + '\');"></span>' +
                  '<input type="text" name="' + prefix + '_depth" id="' + prefix + '_depth" prefix="' + prefix + '" value="' + wds_layerID + '" class="wds_layer_depth spider_int_input" onkeypress="return spider_check_isnum(event)" onchange="jQuery(\'#' + prefix + '\').css({zIndex: jQuery(this).val()})" title="z-index" /></td>' +
              '</tr>';
  var text = '<td class="spider_label"><label for="' + prefix + '_text">Text: </label></td>' +
             '<td><textarea id="' + prefix + '_text" name="' + prefix + '_text" style="width: 222px; height: 60px; resize: vertical;" onkeyup="wds_new_line(\'' + prefix + '\')">Sample text</textarea></td>';
  var text_dimensions = '<td class="spider_label"><label for="' + prefix + '_image_width">Dimensions: </label></td>' +
                        '<td>' +
                          '<input id="' + prefix + '_image_width" class="spider_int_input" type="text" onchange="wds_text_width(this,\'' + prefix + '\')" value="" name="' + prefix + '_image_width" /> x ' +
                          '<input id="' + prefix + '_image_height" class="spider_int_input" type="text" onchange="wds_text_height(this,\'' + prefix + '\')" value="" name="' + prefix + '_image_height" /> % ' +
                          '<input id="' + prefix + '_image_scale" type="checkbox" onchange="wds_break_word(this, \'' + prefix + '\')" name="' + prefix + '_image_scale" checked="checked"/> Break-word' +
                          '<div class="spider_description">Leave blank to keep the initial dimensions.</div></td>';
  var alt = '<td class="spider_label"><label for="' + prefix + '_alt">Alt: </label></td>' +
             '<td><input type="text" id="' + prefix + '_alt" name="' + prefix + '_alt" value="" size="39" /><div class="spider_description"></div></td>';
  var link = '<td class="spider_label"><label for="' + prefix + '_link">Link: </label></td>' +
             '<td><input type="text" id="' + prefix + '_link" name="' + prefix + '_link" value="" size="39" /><div class="spider_description">Use http:// and https:// for external links.</div></td>';
  var position = '<td class="spider_label"><label>Position: </label></td>' +
                 '<td> X <input type="text" name="' + prefix + '_left" id="' + prefix + '_left" value="0" class="spider_int_input" onkeypress="return spider_check_isnum(event)" onchange="jQuery(\'#' + prefix + '\').css({left: jQuery(this).val() + \'px\'})" />' +
                     ' Y <input type="text" name="' + prefix + '_top" id="' + prefix + '_top" value="0" class="spider_int_input" onkeypress="return spider_check_isnum(event)" onchange="jQuery(\'#' + prefix + '\').css({top: jQuery(this).val() + \'px\'})" /><div class="spider_description"></div></td>';
  var published = '<td class="spider_label"><label>Published: </label></td>' +
                  '<td><input type="radio" id="' + prefix + '_published1" name="' + prefix + '_published" checked="checked" value="1" ><label for="' + prefix + '_published1">Yes</label>' +
                      '<input type="radio" id="' + prefix + '_published0" name="' + prefix + '_published" value="0" /><label for="' + prefix + '_published0">No</label><div class="spider_description"></div></td>';
  var color = '<td class="spider_label"><label for="' + prefix + '_color">Color: </label></td>' +
               '<td><input type="text" name="' + prefix + '_color" id="' + prefix + '_color" value="" class="color" onchange="jQuery(\'#' + prefix + '\').css({color: \'#\' + jQuery(this).val()})" /><div class="spider_description"></div></td>';
  var size = '<td class="spider_label"><label for="' + prefix + '_size">Size: </label></td>' +
              '<td><input type="text" name="' + prefix + '_size" id="' + prefix + '_size" value="18" class="spider_int_input" onkeypress="return spider_check_isnum(event)" onchange="jQuery(\'#' + prefix + '\').css({fontSize: jQuery(this).val() + \'px\', lineHeight: jQuery(this).val() + \'px\'})" /> px<div class="spider_description"></div></td>';
  var ffamily = '<td class="spider_label"><label for="' + prefix + '_ffamily">Font family: </label></td>' +
                '<td><select name="' + prefix + '_ffamily" id="' + prefix + '_ffamily" onchange="jQuery(\'#' + prefix + '\').css({fontFamily: jQuery(this).val()})">' + font_families_option + '</select><div class="spider_description"></div></td>';
  var fweight = '<td class="spider_label"><label for="' + prefix + '_fweight">Font weight: </label></td>' +
                '<td><select name="' + prefix + '_fweight" id="' + prefix + '_fweight" onchange="jQuery(\'#' + prefix + '\').css({fontWeight: jQuery(this).val()})">' + font_weights_option + '</select><div class="spider_description"></div></td>';
  var padding = '<td class="spider_label"><label for="' + prefix + '_padding">Padding: </label></td>' +
                 '<td><input type="text" name="' + prefix + '_padding" id="' + prefix + '_padding" value="5px" class="spider_char_input" onchange="document.getElementById(\'' + prefix + '\').style.padding=jQuery(this).val()" /><div class="spider_description">Use CSS type values.</div></td>';
  var fbgcolor = '<td class="spider_label"><label for="' + prefix + '_fbgcolor">Background Color: </label></td>' +
                 '<td><input type="text" name="' + prefix + '_fbgcolor" id="' + prefix + '_fbgcolor" value="000000" class="color" onchange="jQuery(\'#' + prefix + '\').css({backgroundColor: wds_hex_rgba(jQuery(this).val(), 100 - jQuery(\'#' + prefix + '_transparent\').val())})" /><div class="spider_description"></div></td>';
  var fbgtransparent = '<td class="spider_label"><label for="' + prefix + '_transparent">Transparent: </label></td>' +
                       '<td><input type="text" name="' + prefix + '_transparent" id="' + prefix + '_transparent" value="50" class="spider_int_input" onkeypress="return spider_check_isnum(event)" onchange="jQuery(\'#' + prefix + '\').css({backgroundColor: wds_hex_rgba(jQuery(\'#' + prefix + '_fbgcolor\').val(), 100 - jQuery(this).val())})" /> %<div class="spider_description">Value must be between 0 to 100.</div></td>';
  var imgtransparent = '<td class="spider_label"><label for="' + prefix + '_imgtransparent">Transparent: </label></td>' +
                       '<td><input type="text" name="' + prefix + '_imgtransparent" id="' + prefix + '_imgtransparent" value="0" class="spider_int_input" onkeypress="return spider_check_isnum(event)" onchange="jQuery(\'#' + prefix + '\').css({opacity: (100 - jQuery(this).val()) / 100, filter: \'Alpha(opacity=\' + 100 - jQuery(this).val() + \')\'})" /> %<div class="spider_description">Value must be between 0 to 100.</div></td>';
  var border_width = '<td class="spider_label"><label for="' + prefix + '_border_width">Border: </label></td>' +
                     '<td><input type="text" name="' + prefix + '_border_width" id="' + prefix + '_border_width" value="2" class="spider_int_input" onkeypress="return spider_check_isnum(event)" onchange="jQuery(\'#' + prefix + '\').css({borderWidth: jQuery(this).val() + \'px\'})" /> px ' +
                        '<select name="' + prefix + '_border_style" id="' + prefix + '_border_style" style="width: 80px;" onchange="jQuery(\'#' + prefix + '\').css({borderStyle: jQuery(this).val()})">' + border_styles_option + '</select> ' +
                        '<input type="text" name="' + prefix + '_border_color" id="' + prefix + '_border_color" value="" class="color" onchange="jQuery(\'#' + prefix + '\').css({borderColor: \'#\' + jQuery(this).val()})" /><div class="spider_description"></div></td>';
  var border_radius = '<td class="spider_label"><label for="' + prefix + '_border_radius">Radius: </label></td>' +
                      '<td><input type="text" name="' + prefix + '_border_radius" id="' + prefix + '_border_radius" value="2px" class="spider_char_input" onchange="jQuery(\'#' + prefix + '\').css({borderRadius: jQuery(this).val()})" /><div class="spider_description">Use CSS type values.</div></td>';
  var shadow = '<td class="spider_label"><label for="' + prefix + '_shadow">Shadow: </label></td>' +
               '<td><input type="text" name="' + prefix + '_shadow" id="' + prefix + '_shadow" value="" class="spider_char_input" onchange="jQuery(\'#' + prefix + '\').css({boxShadow: jQuery(this).val()})" /><div class="spider_description">Use CSS type values.</div></td>';
  var dimensions = '<td class="spider_label"><label>Dimensions: </label></td>' +
                   '<td>' +
                     '<input type="hidden" name="' + prefix + '_image_url" id="' + prefix + '_image_url" />' +
                     '<input type="text" name="' + prefix + '_image_width" id="' + prefix + '_image_width" value="" class="spider_int_input" onkeyup="wds_scale(\'#' + prefix + '_image_scale\', \'' + prefix + '\')" /> x ' +
                     '<input type="text" name="' + prefix + '_image_height" id="' + prefix + '_image_height" value="" class="spider_int_input" onkeyup="wds_scale(\'#' + prefix + '_image_scale\', \'' + prefix + '\')" /> px ' +
                     '<input type="checkbox" name="' + prefix + '_image_scale" id="' + prefix + '_image_scale" onchange="wds_scale(this, \'' + prefix + '\')" />Scale' +
                     '<div class="spider_description"></div></td>';
  var social_button = '<td class="spider_label"><label for="' + prefix + '_social_button">Social button: </label></td>' +
                      '<td><select name="' + prefix + '_social_button" id="' + prefix + '_social_button" onchange="jQuery(\'#' + prefix + '\').attr(\'class\', \'wds_draggable fa fa-\' + jQuery(this).val())">' + social_button_option + '</select><div class="spider_description"></div></td>';
  var transparent = '<td class="spider_label"><label for="' + prefix + '_transparent">Transparent: </label></td>' +
                    '<td><input type="text" name="' + prefix + '_transparent" id="' + prefix + '_transparent" value="0" class="spider_int_input" onkeypress="return spider_check_isnum(event)" onchange="jQuery(\'#' + prefix + '\').css({opacity: (100 - jQuery(this).val()) / 100, filter: \'Alpha(opacity=\' + 100 - jQuery(this).val() + \')\'})" /> %<div class="spider_description">Value must be between 0 to 100.</div></td>';
  var hover_color = '<td class="spider_label"><label for="' + prefix + '_hover_color">Hover Color: </label></td>' +
                    '<td><input type="text" name="' + prefix + '_hover_color" id="' + prefix + '_hover_color" value="" class="color" onchange="jQuery(\'#' + prefix + '\').hover(function() { jQuery(this).css({color: \'#\' + jQuery(\'#' + prefix + '_hover_color\').val()}); }, function() { jQuery(this).css({color: \'#\' + jQuery(\'#' + prefix + '_color\').val()}); })" /><div class="spider_description"></div></td>';
  var layer_type = '<input type="hidden" name="' + prefix + '_type" id="' + prefix + '_type" value="' + type + '" />';
  var layer_effect_in = '<td class="spider_label"><label>Effect in: </label></td>' +
                   '<td>' +
                    '<span style="display: table-cell;">' +
                      '<input type="text" name="' + prefix + '_start" id="' + prefix + '_start" value="1000" class="spider_int_input" /> ms' +
                      '<div class="spider_description">Start</div>' +
                    '</span>' +
                    '<span style="display: table-cell;">' +
                      '<select name="' + prefix + '_layer_effect_in" id="' + prefix + '_layer_effect_in" style="width:150px;" onchange="wds_trans_effect_in(\'' + id + '\', \'' + prefix + '\', ' + ((type == 'social') ? 1 : 0) + '); wds_trans_end(\'' + prefix + '\', jQuery(this).val());">' + layer_effects_in_option + '</select>' +
                      '<div class="spider_description">Effect</div>' +
                    '</span>' +
                    '<span style="display: table-cell;">' +
                      '<input id="' + prefix + '_duration_eff_in" class="spider_int_input" type="text"  onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_in(\'' + id + '\', \'' + prefix + '\', ' + ((type == 'social') ? 1 : 0) + '); wds_trans_end(\'' + prefix + '\', jQuery(\'#' + prefix + '_layer_effect_in\').val());" value="1000" name="' + prefix + '_duration_eff_in"> ms' +
                      '<div class="spider_description">Duration</div>' +
                    '</span>' +
                    '<div class="spider_description spider_free_version">Some effects are disabled in free version.</div>' +
                   '</td>';
  var layer_effect_out = '<td class="spider_label"><label>Effect out: </label></td>' +
                   '<td>' +
                    '<span style="display: table-cell;">' +
                      '<input type="text" name="' + prefix + '_end" id="' + prefix + '_end" value="3000" class="spider_int_input" /> ms' +
                      '<div class="spider_description">Start</div>' +
                    '</span>' +
                    '<span style="display: table-cell;">' +
                      '<select name="' + prefix + '_layer_effect_out" id="' + prefix + '_layer_effect_out" style="width:150px;" onchange="wds_trans_effect_out(\'' + id + '\', \'' + prefix + '\', ' + ((type == 'social') ? 1 : 0) + '); wds_trans_end(\'' + prefix + '\', jQuery(this).val());">' + layer_effects_out_option + '</select>' +
                      '<div class="spider_description">Effect</div>' +
                    '</span>' +
                    '<span style="display: table-cell;">' +
                      '<input id="' + prefix + '_duration_eff_out" class="spider_int_input" type="text" onkeypress="return spider_check_isnum(event)" onchange="wds_trans_effect_out(\'' + id + '\', \'' + prefix + '\', ' + ((type == 'social') ? 1 : 0) + '); wds_trans_end(\'' + prefix + '\', jQuery(\'#' + prefix + '_layer_effect_out\').val());" value="1000" name="' + prefix + '_duration_eff_out"> ms' +
                      '<div class="spider_description">Duration</div>' +
                    '</span>' +
                    '<div class="spider_description spider_free_version">Some effects are disabled in free version.</div>' + 
                   '</td>';

  switch(type) {
    case 'text': {
      jQuery("#wds_preview_image" + id).append(jQuery("<span />").attr({
        id: prefix,
        class: "wds_draggable_" + id + " wds_draggable",
        onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
        style: "z-index: " + layerID.replace("pr_", "") + "; " +
               "word-break: keep-all;" +
               "display: inline-block; " +
               "left: 0; top: 0; " +
               "color: #FFFFFF; " +
               "font-size: 18px; " +
               "line-height: 18px; " +
               "font-family: Arial; " +
               "font-weight: normal; " +
               "padding: 5px; " +
               "background-color: " + wds_hex_rgba('000000', 50) + "; " +
               "border-radius: 2px;"
      }).html("Sample text"));
      jQuery("#" + tbodyID).append(tbody +
        '<tr class="wds_layer_tr">' +
          text +
          layer_effect_in +
        '</tr><tr class="wds_layer_tr">' +
          text_dimensions +
          layer_effect_out +
        '</tr><tr class="wds_layer_tr">' +
          position +
          padding +
        '</tr><tr class="wds_layer_tr">' +
          size +
          fbgcolor +
        '</tr><tr class="wds_layer_tr">' +
          color +
          fbgtransparent +  
        '</tr><tr class="wds_layer_tr">' +
          ffamily +
          border_width +
        '</tr><tr class="wds_layer_tr">' +
          fweight +
          border_radius +
        '</tr><tr class="wds_layer_tr">' +
          link +
          shadow +
        '</tr><tr class="wds_layer_tr">' +
          published +
        '</tr>' + layer_type
      );
      break;
    }
    case 'image': {
      var tbody_html = tbody +
      '<tr class="wds_layer_tr">' +
        dimensions +
        layer_effect_in +
      '</tr><tr class="wds_layer_tr">' +
        alt +
        layer_effect_out +
      '</tr><tr class="wds_layer_tr">' +
        link +
        border_width +
      '</tr><tr class="wds_layer_tr">' +
        position +
        border_radius +
      '</tr><tr class="wds_layer_tr">' +
        imgtransparent + 
        shadow +
      '</tr><tr class="wds_layer_tr">' +
        published +
      '</tr>' + layer_type;
      if (!duplicate) {
        image_escape = wds_add_image_layer(prefix, event, tbodyID, id, layerID, tbody_html);
      }
      else {
        jQuery("#" + tbodyID).append(tbody_html);
      }
      break;
    }
    default: {
      break;
    }
  }
  if (!duplicate) {
    wds_drag_layer(id);
    jscolor.bind();
  }
  wds_layer_weights(id);
}

function wds_scale(that, prefix) {
  if (jQuery(that).is(':checked')) {
    jQuery("#" + prefix).css({width: "", maxWidth: jQuery("#" + prefix + "_image_width").val() + "px", height: "", maxHeight: jQuery("#" + prefix + "_image_height").val() + "px"});
  }
  else {
    jQuery("#" + prefix).css({maxWidth: "none", width: jQuery("#" + prefix + "_image_width").val() + "px", maxHeight: "none", height: jQuery("#" + prefix + "_image_height").val() + "px"});
  }
}

function wds_drag_layer(id) {
  jQuery(".wds_draggable_" + id).draggable({ containment: "#wds_preview_wrapper_" + id, scroll: false });
  jQuery(".wds_draggable_" + id).bind('dragstart', function(event) {
    jQuery(this).addClass('wds_active_layer');
  }).bind('drag',function(event) {
    // jQuery(this).css({
      // top: event.offsetY,
      // left: event.offsetX
    // });
    var prefix = jQuery(this).attr("id");
    jQuery("#" + prefix + "_left").val(parseInt(jQuery(this).offset().left - jQuery(".wds_preview_image" + id).offset().left));
    jQuery("#" + prefix + "_top").val(parseInt(jQuery(this).offset().top - jQuery(".wds_preview_image" + id).offset().top));
  });
  jQuery(".wds_draggable_" + id).bind('dragstop', function(event) {
    jQuery(this).removeClass('wds_active_layer');
  });
}

function wds_layer_weights(id) {
  jQuery(".ui-sortable" + id + "").sortable({
    handle: ".connectedSortable",
    connectWith: ".connectedSortable",
    update: function (event) {
      var i = 1;
      jQuery(".wds_slide" + id + " .wds_layer_depth").each(function (e) {
        if (jQuery(this).val()) {
          jQuery(this).val(i++);
          prefix = jQuery(this).attr("prefix");
          jQuery("#" + prefix).css({zIndex: jQuery(this).val()});
        }
      });
    }
  });//.disableSelection();
  // jQuery(".ui-sortable").sortable("enable");
}

function wds_slide_weights() {
  jQuery(".aui-sortable").sortable({
    connectWith: ".connectedSortable",
    items: ".connectedSortable",
    update: function (event) {
      var i = 1;
      jQuery(".wbs_subtab input[id^='order']").each(function (e) {
        if (jQuery(this).val()) {
          jQuery(this).val(i++);
        }
      });
    }
  });
  jQuery(".aui-sortable").disableSelection();
}

function wds_add_image_layer(prefix, e, tbodyID, id, layerID, tbody_html) {
  var custom_uploader;
  e.preventDefault();
  // If the uploader object has already been created, reopen the dialog.
  if (custom_uploader) {
    custom_uploader.open();
    return;
  }
  // Extend the wp.media object.
  custom_uploader = wp.media.frames.file_frame = wp.media({
    title: 'Choose Image',
    library : { type : 'image'},
    button: { text: 'Insert'},
    multiple: false
  });
  // When a file is selected, grab the URL and set it as the text field's value
  custom_uploader.on('select', function() {
    jQuery("#" + tbodyID).append(tbody_html);
    attachment = custom_uploader.state().get('selection').first().toJSON();
    jQuery("#wds_preview_image" + id).append(jQuery("<img />").attr({
      id: prefix,
      class: "wds_draggable_" + id + " wds_draggable",
      onclick: "wds_showhide_layer('" + tbodyID + "', 1)",
      src: attachment.url,
      style: "z-index: " + layerID.replace("pr_", "") + "; " +
             "left: 0; top: 0; " +
             "border: 2px none #FFFFFF; " +
             "border-radius: 2px; " +
             "opacity: 1; filter: Alpha(opacity=100); " +
             "/*position: absolute;*/"
    }));

    var att_width = attachment.width ? attachment.width : jQuery("#" + prefix).width();
    var att_height = attachment.height ? attachment.height : jQuery("#" + prefix).height();
    var width = Math.min(att_width, jQuery("#wds_preview_image" + id).width());
    var height = Math.min(att_height, jQuery("#wds_preview_image" + id).height());

    jQuery("#" + prefix + "_image_url").val(attachment.url);
    jQuery("#" + prefix + "_image_width").val(width);
    jQuery("#" + prefix + "_image_height").val(height);
    jQuery("#" + prefix + "_image_scale").attr("checked", "checked");
    wds_scale("#" + prefix + "_image_scale", prefix);
    wds_drag_layer(id);
    jscolor.bind();
  });

  // Open the uploader dialog.
  custom_uploader.open();
}

function wds_hex_rgba(color, transparent) {
  color = "#" + color;
  var redHex = color.substring(1, 3);
  var greenHex = color.substring(3, 5);
  var blueHex = color.substring(5, 7);

  var redDec = parseInt(redHex, 16);
  var greenDec = parseInt(greenHex, 16);
  var blueDec = parseInt(blueHex, 16);

  var colorRgba = 'rgba(' + redDec + ', ' + greenDec + ', ' + blueDec + ', ' + transparent / 100 + ')';
  return colorRgba;
}

function wds_add_slide() {
  var slides_count = jQuery(".wbs_subtab a[id^='wbs_subtab']").length;
  var slideID = "pr_" + ++slides_count;
  jQuery("#slide_ids_string").val(jQuery("#slide_ids_string").val() + slideID + ',');
  jQuery(".wds_slides_box *").removeClass("wds_sub_active");
  jQuery(
    '<a id="wbs_subtab' + slideID + '" class="connectedSortable wds_sub_active" href="#">' +
      ' <input type="text" id="title' + slideID + '" name="title' + slideID + '" value="Slide" class="wds_tab_title" tab_type="slide' + slideID + '" onclick="wds_change_sub_tab(this, \'wds_slide' + slideID + '\')" />' +
      ' <span class="wds_tab_remove" title="Delete slide" onclick="wds_remove_slide(\'' + slideID + '\')"></span>' +
      ' <input type="hidden" name="order' + slideID + '" id="order' + slideID + '" value="' + slides_count + '" /></a>').insertBefore(".wds_add_layer");
  jQuery(".wbs_subtab").after(
    '<div class="wds_box wds_sub_active wds_slide' + slideID + '">' +
      '<table class="ui-sortable' + slideID + '">' +
        '<thead><tr><td colspan="4"> </td></tr></thead>' +
        '<tbody>' +
          '<input type="hidden" name="type' + slideID + '" id="type' + slideID + '" value="image" />' +
          '<tr><td class="spider_label"><label>Published: </label></td>' +
              '<td><input id="published' + slideID + '1" type="radio" value="1" checked="checked" name="published' + slideID + '">' +
                  '<label for="published' + slideID + '1">Yes</label>' +
                  '<input id="published' + slideID + '0" type="radio" value="0" name="published' + slideID + '">' +
                  '<label for="published' + slideID + '0">No</label></td>' +
          '</tr><tr id="trlink' + slideID + '"><td class="spider_label"><label for="link' + slideID + '">Link the slide: </label></td>' +
                   '<td><input id="link' + slideID + '" type="text" size="39" value="" name="link' + slideID + '" /></td>' +
          '</tr><tr><td colspan="4">' +
            ' <input id="button_image_url' + slideID + '" class="button-primary" type="button" value="Add Image from Media Library" onclick="spider_media_uploader(\'' + slideID + '\', event); return false;">' +
            ' <input class="button-primary" type="button" value="Add Image URL" onclick="wds_add_image_url(\'' + slideID + '\')">' +
            ' <input class="button-secondary wds_free_button" type="button" value="Add Video" onclick="alert(\'This functionality is disabled in free version.\')">' +
            ' <input id="delete_image_url' + slideID + '" class="button-secondary" type="button" value="Remove" onclick="spider_remove_url(\'button_image_url' + slideID + '\', \'image_url' + slideID + '\', \'delete_image_url' + slideID + '\', \'wds_preview_image' + slideID + '\')">' +
            ' <input id="image_url' + slideID + '" type="hidden" style="display: inline-block;" value="" name="image_url' + slideID + '">' +
            ' <input id="thumb_url' + slideID + '" type="hidden" value="" name="thumb_url' + slideID + '"></td>' +
          '</tr><tr><td colspan="4">' +
            '<div id="wds_preview_wrapper_' + slideID + '" class="wds_preview_wrapper" style="width: ' + jQuery("#width").val() + 'px; height: ' + jQuery("#height").val() + 'px;">' +
            '<div class="wds_preview" style="overflow: hidden; position: absolute; width: inherit; height: inherit; background-color: transparent; background-image: none; display: block;">' +
            '<div id="wds_preview_image' + slideID + '" class="wds_preview_image' + slideID + '" ' +
                 'style="background-color: ' + wds_hex_rgba(jQuery("#background_color").val(), (100 - jQuery("#background_transparent").val())) + '; ' +
                        'background-image: url(\'\'); ' +
                        'background-position: center center; ' +
                        'background-repeat: no-repeat; ' +
                        'background-size: ' + jQuery('input[name=bg_fit]:radio:checked').val() + '; ' +
                        'border-width: ' + jQuery('#glb_border_width').val() + 'px; ' +
                        'border-style: ' + jQuery('#glb_border_style').val() + '; ' +
                        'border-color: #' + jQuery('#glb_border_color').val() + '; ' +
                        'border-radius: ' + jQuery('#glb_border_radius').val() + '; ' +
                        'box-shadow: ' + jQuery('#glb_box_shadow').val() + '; ' +
                        'width: inherit; height: inherit;"> </div></div></div></td>' +
          '</tr><tr><td colspan="4">' +
            ' <input class="button-primary button button-small" type="button" value="Add Text Layer" onclick="wds_add_layer(\'text\', \'' + slideID + '\'); return false;">' +
            ' <input class="button-primary button button-small" type="button" value="Add Image Layer" onclick="wds_add_layer(\'image\', \'' + slideID + '\', \'\', event); return false;">' +
            ' <input class="button-secondary button button-small wds_free_button" type="button" value="Add Social Buttons Layer" onclick="alert(\'This functionality is disabled in free version.\'); return false;"></td>' +
          '</tr></tbody></table>' +
          '<input id="slide' + slideID + '_layer_ids_string" name="slide' + slideID + '_layer_ids_string" type="hidden" value="" />' +
          '<input id="slide' + slideID + '_del_layer_ids_string" name="slide' + slideID + '_del_layer_ids_string" type="hidden" value="" />' +
          '<script>' +
            'jQuery(window).load(function() {' +
              'wds_drag_layer(\'' + slideID + '\');' +
            '});' +
            'spider_remove_url(\'button_image_url1\', \'image_url' + slideID + '\', \'delete_image_url' + slideID + '\', \'wds_preview_image' + slideID + '\');' +
          '</script>' +
          '</div>');
  wds_slide_weights();
}

function wds_remove_slide(slideID) {
  if (confirm("Do you want to delete slide?")) {
    jQuery("#sub_tab").val("");
    jQuery(".wds_slides_box *").removeClass("wds_sub_active");
    jQuery(".wds_slide" + slideID).remove();
    jQuery("#wbs_subtab" + slideID).remove();

    var slideIDs = jQuery("#slide_ids_string").val();
    slideIDs = slideIDs.replace(slideID + ",", "");
    jQuery("#slide_ids_string").val(slideIDs);
    var delslideIds = jQuery("#del_slide_ids_string").val() + slideID + ",";
    jQuery("#del_slide_ids_string").val(delslideIds);

    var slides = jQuery(".wbs_subtab a[id^='wbs_subtab']");
    for (var i in slides) {
      firstSlideID = slides[i].id.replace("wbs_subtab", "");
      break;
    }
    jQuery("#wbs_subtab" + firstSlideID).addClass("wds_sub_active");
    jQuery(".wds_slide" + firstSlideID).addClass("wds_sub_active");
  }
}

function wds_trans_end(id, effect) {
  var transitionEvent = wds_whichTransitionEvent();
  var e = document.getElementById(id);
  transitionEvent && e.addEventListener(transitionEvent, function() {
    console.log("aaa");
    jQuery("#" + id).removeClass("animated").removeClass(effect);
  });
}

function wds_whichTransitionEvent() {
  var t;
  var el = document.createElement('fakeelement');
  var transitions = {
    'animation':'animationend',
    'OAnimation':'oAnimationEnd',
    'MozAnimation':'animationend',
    'WebkitAnimation':'webkitAnimationEnd'
  }
  for (t in transitions) {
    if (el.style[t] !== undefined) {
      return transitions[t];
    }
  }
}

function wds_new_line(prefix) {
  jQuery("#" + prefix).html(jQuery("#" + prefix + "_text").val().replace(/(\r\n|\n|\r)/gm, "<br />"));
}

function wds_trans_effect_in(slider_id, prefix, social) {
  var social_class = "";
  if (social) {
    social_class = ' fa fa-' + jQuery("#" + prefix + "_social_button").val();
  }
  jQuery("#" + prefix).css(
    '-webkit-animation-duration', jQuery("#" + prefix + "_duration_eff_in").val() / 1000 + "s").css(
    'animation-duration' , jQuery("#" + prefix + "_duration_eff_in").val() / 1000 + "s");
  jQuery("#" + prefix).removeClass().addClass(
    jQuery("#" + prefix + "_layer_effect_in").val() + " animated wds_draggable_" + slider_id + social_class + " wds_draggable ui-draggable");
}

function wds_trans_effect_out(slider_id, prefix, social) {
  var social_class = "";
  if (social) {
    social_class = ' fa fa-' + jQuery("#" + prefix + "_social_button").val();
  }
  jQuery("#" + prefix).css(
    '-webkit-animation-duration', jQuery("#" + prefix + "_duration_eff_out").val() / 1000 + "s").css(
    'animation-duration' , jQuery("#" + prefix + "_duration_eff_out").val() / 1000 + "s");
  jQuery("#" + prefix).removeClass().addClass(
    jQuery("#" + prefix + "_layer_effect_out").val() + " animated wds_draggable_" + slider_id + social_class + " wds_draggable ui-draggable");
}

function wds_break_word(that, prefix) {
  if (jQuery(that).is(':checked')) {
    jQuery("#" + prefix).css({wordBreak: "keep-all"});
  }
  else {
    jQuery("#" + prefix).css({wordBreak: "break-all"});  
  }
}

function wds_text_width(that, prefix) {
  var width = parseInt(jQuery(that).val());
  if (width) {
    if (width >= 100) {
      width = 100;
      jQuery("#" + prefix).css({left : 0});
      jQuery("#" + prefix + "_left").val(0);
    }
    else {
      var layer_left_position = parseInt(jQuery("#" + prefix).css("left"));	
      var layer_parent_div_width = parseInt(jQuery("#" + prefix).parent().width());
      var left_position_in_percent = (layer_left_position / layer_parent_div_width) * 100;
      if ((parseInt(left_position_in_percent) + width) > 100) {
        var left_in_pix = parseInt((100 - width) * (layer_parent_div_width / 100));
        jQuery("#" + prefix).css({left : left_in_pix + "px"});
        jQuery("#" + prefix + "_left").val(left_in_pix);
      }
    }
    jQuery("#" + prefix).css({width: width + "%"});
    jQuery(that).val(width);
  }
  else {
    jQuery("#" + prefix).css({width: ""});
    jQuery(that).val("");
  }
}

function wds_text_height(that, prefix) {
  var height = parseInt(jQuery(that).val());
  if (height) {
    if (height >= 100) {
      jQuery("#" + prefix).css({top : 0});
      jQuery("#" + prefix + "_top").val(0);
    }
    else {
      var layer_top_position = parseInt(jQuery("#" + prefix).css("top"));	
      var layer_parent_div_height = parseInt(jQuery("#" + prefix).parent().height());
      var top_position_in_percent = (layer_top_position / layer_parent_div_height) * 100;
      if ((parseInt(top_position_in_percent) + height) > 100) {
        var top_in_pix = parseInt((100 - height) * (layer_parent_div_height / 100 ));
        jQuery("#" + prefix).css({top : top_in_pix});
        jQuery("#" + prefix + "_top").val(top_in_pix);
      }
    }
    jQuery("#" + prefix).css({height: height + "%"});
    jQuery(that).val(height);
  }
  else {
    jQuery("#" + prefix).css({height: ""});
    jQuery(that).val("");
  }
}
