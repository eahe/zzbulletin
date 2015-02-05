<?php 
if(!isset($_SESSION['poll1']))
$_SESSION['poll1'] = 1;
if(!isset($_SESSION['poll2']))
$_SESSION['poll2'] = 1;

?>
<script type="text/javascript" >
	$(function(){
			var loader=$('#loader');
			var pollcontainer=$('#pollcontainer');
			loader.fadeIn();
			//Load the poll form
			$.get( "<?php echo $pluginUrl . 'poll.php'; ?>", '', function(data, status){
					pollcontainer.html(data);
					animateResults(pollcontainer);
					pollcontainer.find('#viewresult').click(function(){
							//if user wants to see result
							loader.fadeIn();
							$.get("<?php echo $pluginUrl . 'poll.php'; ?>", 'z=1', function(data,status){
									pollcontainer.fadeOut(1000, function(){
											$(this).html(data);
											animateResults(this);
										});
									loader.fadeOut();
								});
							//prevent default behavior
							return false;
						}).end()
					.find('#pollform').submit(function(){
							var selected_val=$(this).find('input[name=poll]:checked').val();
							if(selected_val!=undefined){
								//post data only if a value is selected
								loader.fadeIn();
								$.post("<?php echo $pluginUrl . 'poll.php'; ?>", $(this).serialize(), function(data, status){
										$('#formcontainer').fadeOut(100, function(){
												$(this).html(data);
												animateResults(this);
												loader.fadeOut();
											});
									});
							}
							//prevent form default behavior
							return false;
						});
					loader.fadeOut();
				});
	
			function animateResults(data){
				$(data).find('.bar').hide().end().fadeIn('slow', function(){
						$(this).find('.bar').each(function(){
								var bar_width=parseInt($(this).prev().find('em').text());
								$(this).css('width', '0%').show().animate({ width: bar_width + '%' }, 1000);
							});
					});
			}
	
		});
</script>

<div style='text-align: center;' class='font1' id="container" >
	<div style='text-align: center;' id="pollcontainer" >
	</div>
</div>
