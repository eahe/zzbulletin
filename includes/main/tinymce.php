
<!-- TinyMCE -->
<?php echo "<script type='text/javascript' src='" . $rootUrl . "editors/tinymce/jscripts/tiny_mce/tiny_mce.js'></script>"; ?>

<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "1",
		height: "200",
		theme : "advanced",
		relative_urls: false,

		plugins : "save, emotions, iespell, paste, visualchars, autosave, inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "restoredraft, |,bold, italic, underline, strikethrough,| ,justifyleft, justifycenter, justifyright, justifyfull, | ,cut, copy, paste, | ,undo ,redo, | ,link, unlink, | ,charmap",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,

		// Example content CSS (should be your site CSS)
		content_css : "<?php echo $rootUrl; ?>themes/contents.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});

	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "2",
		height: "350",
		theme : "advanced",
		relative_urls: false,

		plugins : "autosave, lists, pagebreak, style, layer, table, save, advhr, advimage, advlink, emotions, iespell, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template, inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "restoredraft, | ,bold, italic, underline, strikethrough,| ,justifyleft, justifycenter, justifyright, justifyfull, cut, copy, paste ,|,search,replace,|,bullist,numlist,|,outdent,indent, |,undo, redo, link, unlink, | table,| ,hr, removeformat, |,sub, sup",
		theme_advanced_buttons2 : "table, charmap, image, emotions, iespell, media, advhr,| ,ltr ,rtl,| ,fullscreen, cite, abbr, acronym,| ,pagebreak, print",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,

		// Example content CSS (should be your site CSS)
		content_css : "<?php echo $rootUrl; ?>themes/contents.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
	
		tinyMCE.init({
		// General options
		mode : "exact",
		elements : "3",
		height: "200",
		theme : "advanced",
		relative_urls: false,

		plugins : "save, emotions, iespell, paste, visualchars, autosave, inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "restoredraft, |,bold, italic, underline, strikethrough,| ,justifyleft, justifycenter, justifyright, justifyfull, | ,cut, copy, paste, |, undo ,redo, | ,link, unlink, | ,charmap, image, emotions,",
	
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,

		// Example content CSS (should be your site CSS)
		content_css : "<?php echo $rootUrl; ?>themes/contents.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});

</script>
<!-- /TinyMCE -->