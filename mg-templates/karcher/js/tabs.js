$(document).ready(function() {
	$('.tabs-content .tab:not(.tab-first)').hide();
	$('.tabs-box .tabs-ui a').bind('click', function(){
		var curr 	  = $(this).closest('.tabs-box').find('.tabs-content .tab:visible');
		var tabs      = $(this).closest('.tabs-box').find('.tabs-content .tab');
		var operation = $(this).attr('rel');
		if(operation == 'next')
		{
			showNext(curr, tabs);
		}
		else
		{
			showPrev(curr, tabs);
		}
		return false; 
	});

	function showNext(curr, tabs)
	{
		var next = (curr.next().length) ? curr.next() : tabs.first();
		curr.hide();
		next.show();
	}
	function showPrev(curr, tabs)
	{
		var next = (curr.prev().length) ? curr.prev() : tabs.last();
		curr.hide();
		next.show();
	}
});