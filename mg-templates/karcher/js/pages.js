$(document).ready(function() {

	$('#img-big').bxSlider({
		pagerCustom: '#img-thumbs',
		controls: false,
		adaptiveHeight: true
	});

	$('.nav-content li > ul').parent().addClass('parentItem');
	
	$('#img-big li a').fancybox();
	
	$('.carousel').bxSlider({
		slideWidth: 5000,
		minSlides: 3,
		maxSlides: 3,
		slideMargin: 0,
		pager: false
	});

	$('#tabs-ftr ul').idTabs();

	// Оформление заказа
	if($('#pageOrder').length > 0)
	{
		var totalSum = parseInt($('#pageOrder #totalSum').text());
		var delCost = 0;

		$('#resNoDel').text(totalSum + ' руб.');
		$('#costDel').text(delCost + ' руб.');
		$('#resSumma').text(totalSum + delCost + ' руб.');

		$('#pageOrder #orderDelivery input').bind('change', function(){
			delCost = parseInt($(this).data('cost'));
			$('#costDel').text(delCost + ' руб.');
			$('#resSumma').text(totalSum + delCost + ' руб.');
		});
	}
	
	/*---------- !Good comments! -------------*/

	$('.add-cmt-form.hide').hide();
	
	$('.jq-add-goods-cmt').bind('click', function(){
		$('.jq-add-goods-cmt').hide();
		$('.add-cmt-form').show();
		return false;
	});

	$('#jq-hide-form-goods-cmt').bind('click', function(){
		$('.add-cmt-form').hide();
		$('.jq-add-goods-cmt').show();
		return false;
	});

	$('.jq-add-cmt-static-page').bind('click', function(){
		$('.add-cmt-form').show();
		return false;
	});

	$('#jq-hide-form-static-page').bind('click', function(){
		$('.add-cmt-form').hide();
		return false;
	});

	/*---------- !Good comments! -------------*/

	$('#img-small-items a').bind('click', function(){
		var srcImg = $(this).find('img').attr('src');
		$('#img-big img').attr('src', srcImg);
		return false;
	});

	$('.add-cart-small-btn').tooltipster({
		theme: '.tooltipster-shadow',
		position: 'bottom-right'
	});

	$('.list-img img').tooltipster({
		theme: '.tooltipster-shadow',
		position: 'left'
	});

	$('#txtPhone').tooltipster({
		theme: '.tooltipster-shadow',
		position: 'bottom-left'
	});

	$('#txtDate').tooltipster({
		theme: '.tooltipster-shadow',
		position: 'bottom-left'
	});

	$('#txtPhone').mask('9(999) 999-99-99');
	$('#txtDate').mask('99-99-9999');
});