<?php 
echo "<script src='" . $rootUrl . "jquery/jquery-ui.min.js'></script>";
echo "<script src='" . $rootUrl . "jquery/jquery.easy-confirm-dialog2.js'></script>";
echo "<script src='" . $rootUrl . "jquery/jquery.easy-confirm-dialog.js'></script>";
?>

<!-- "Warning dialog. This js is for verifying if something should be deleted." -->
<script type="text/javascript">
	$(document).ready(function() {
			$(".confirm").easyconfirm({locale: { title: 'Warning!', button: ['Cancel','Ok']}});

			$(".ui-dialog-titlebar").addClass("ui-widget-header2");
			$(".ui-widget-content").addClass("ui-widget-content2");
		});
</script>

<!-- "This is for the help dialog." -->
<script type="text/javascript">
	$(document).ready(function() {
			$(".confirm2").easyconfirm2({locale: { title: 'Message.', button: ['Ok']}});
		});

</script>