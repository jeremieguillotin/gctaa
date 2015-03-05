(function() {
	var called = false;
	tinymce.create('tinymce.plugins.boutonGctaa', {
		init : function(ed, url) {
			ed.addButton('boutonGctaa', {
				title : 'GCTAA - Résultats',
				image : url + '/img/boutonGctaa.png',
				cmd : 'mceBoutonGctaaInsert',
			});

			ed.addCommand('mceBoutonGctaaInsert', function(ui, v) {
				tb_show('', ajaxurl + '?action=boutonGctaa_shortcodePrinter');
				if(called == false) {
					called = true;
					jQuery('#mcb_button').live("click", function(e) {
						e.preventDefault();

						tinyMCE.activeEditor.execCommand('mceInsertContent', 0, boutonGctaa_create_shortcode());

						tb_remove();
					});
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
	});
	tinymce.PluginManager.add('boutonGctaa', tinymce.plugins.boutonGctaa);
})();

function boutonGctaa_create_shortcode() {
	return '[GCTAA-resultat=' + jQuery('#GCTAA_IdConcours').val() + ']';
}