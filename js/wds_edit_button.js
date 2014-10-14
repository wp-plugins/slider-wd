(function() {
  tinymce.create('tinymce.plugins.wds_mce', {
    init : function(ed, url) {
      ed.addCommand('mcewds_mce', function() {
        ed.windowManager.open({
          file : wds_admin_ajax,
					width : 400 + ed.getLang('wds_mce.delta_width', 0),
					height : 250 + ed.getLang('wds_mce.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url /* Plugin absolute URL.*/
				});
			});
      ed.addButton('wds_mce', {
        title : 'Insert Slider',
        cmd : 'mcewds_mce',
        image: url + '/images/wds_edit_but.png'
      });
    }
  });
  tinymce.PluginManager.add('wds_mce', tinymce.plugins.wds_mce);
})();
