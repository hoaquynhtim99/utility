<!-- BEGIN: main -->
<div class="clear"></div>
<div class="unti-content" style="height:{HEIGHT}px">
	<!-- BEGIN: row -->
	<div class="item-wrap">
		<div class="item-content">
			<div class="img">
				<a href="{ROW.url}" title="{ROW.title}"><img src="{ROW.images}" alt="{ROW.images}" width="100"/></a>
			</div>
			<h3><a href="{ROW.url}" title="{ROW.title}">{ROW.title}</a></h3>
			<p>{ROW.introtext}</p>
		</div>
	</div>
	<!-- END: row -->
</div>
<div class="clear"></div>
<script type="text/javascript">
$(window).load(function(){
	var content_width = $('.unti-content').width();
	var item_width = content_width / 3;
	
	$('.unti-content .item-wrap').css({
		'width' : item_width,
		'left' : item_width,
		'display' : 'block',
	});
	
	var animationTime = 1000;
	var pTop = 0;
	var pLeft = 0;
	var cItem = 0;
	var item_height = 210;
	
	$.each( $('.unti-content .item-wrap'), function(){
		cItem ++;
		$(this).animate({
			'top' : pTop,
			'left' : pLeft,
		},animationTime);
		pLeft += item_width;
		if( cItem % 3 == 0 ){
			pTop += item_height;
			pLeft = 0;
		}
	});
	
	$('.unti-content .item-wrap').hover( function(){
		$('.unti-content .item-wrap').css({'z-index':1});
		$(this).css({
			'z-index':99,
		});
		$(this).find('.item-content').css({
			"-moz-box-shadow":"inset 0 0 20px rgba(256, 256, 256, .9),0 0 10px rgba(0, 0, 0, .4)",
			"-ms-box-shadow":"inset 0 0 20px rgba(256, 256, 256, .9),0 0 10px rgba(0, 0, 0, .4)",
			"-webkit-box-shadow":"inset 0 0 20px rgba(256, 256, 256, .9),0 0 10px rgba(0, 0, 0, .4)",
			"box-shadow":"inset 0 0 20px rgba(256, 256, 256, .9),0 0 10px rgba(0, 0, 0, .4)",
		});
	},function(){
		$(this).css({
			'z-index':1,
		});
		$(this).find('.item-content').css({
			"-moz-box-shadow":"inset 0 0 6px rgba(0, 0, 0, 0),0 0 10px rgba(256, 256, 256, 0)",
			"-ms-box-shadow":"inset 0 0 6px rgba(0, 0, 0, 0),0 0 10px rgba(256, 256, 256, 0)",
			"-webkit-box-shadow":"inset 0 0 6px rgba(0, 0, 0, 0),0 0 10px rgba(256, 256, 256, 0)",
			"box-shadow":"inset 0 0 6px rgba(0, 0, 0, 0),0 0 10px rgba(256, 256, 256, 0)",
		});
	});
});
</script>
<!-- END: main -->