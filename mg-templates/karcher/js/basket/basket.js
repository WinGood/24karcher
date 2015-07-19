$(document).ready(function() {

	var basket 	 = $('#bottom-basket');
	var emptyMsg = '<li class="empty-basket">Корзина пуста</li>';

	init();

	// Показ содержимого корзины
	basket.find('.title').bind('click', function(){
		basket.toggleClass('show-basket');
	});

	// Добавить в корзину
	$('.add-cart').bind('click', function(){
		$(this).css({'position' : 'relative'}).effect('transfer', {to: '#bottom-basket', className: 'ui-effects-transfer'}, 1000);
		addBasketAnimate($(this));
		addBasket($(this));
		return false;
	});

	// Удаление товара из корзины
	$(document).on('click', '.good-basket button', function(){
		if(confirm('Вы действительно хотите удалить товар из корзины?'))
			deleteItem($(this));
		return false;
	});

	// Изменение кол-ва товара
	$(document).on('focus', '.good-basket input', function(){
		previousVal = $(this).val();
	});

	$(document).on('change', '.good-basket input', function(){
		changeCount($(this));
	});

	function init()
	{
		if($('.empty-basket').text() != '')
			bottomPanel('hide');
		else
			bottomPanel('show');
	}

	function addBasketAnimate(button)
	{
		var img = button.closest('.product-preview-box').find('img');
		$(button).effect('transfer', {to: '#bottom-basket'}, 1000);

		// button.closest('.product-preview-box').attr('id', 'currImg');
		// console.log(pos);
		// img.clone().css({'position' : 'absolute', 'z-index' : '1000'}).prependTo('#currImg').animate({
		// 	opacity: 0.5,
		// 	top: pos.top,
		// 	left: pos.left,
		// 	width: 50,
		// 	height: 50
		// }, 1000, function(){
		// 	$(this).remove();
		// 	button.closest('.product-preview-box').attr('id', '')
		// });
	}

	function addBasket(el)
	{
		var request = 'inCartProductId=' + $(el).data('item-id') + "&amount_input=1";
		$.ajax({
			type: "POST",
			url: mgBaseDir + "/cart",
			data: "updateCart=1&" + request,
			dataType: "json",
			cache: false,
			success: function(response)
			{
				if ('success' == response.status)
				{
					dataCart = '';
					response.data.dataCart.forEach(updateBasketList);
					basket.find('ul').html(dataCart);
					updateBasket(response.data.cart_count, response.data.cart_price_wc);
					bottomPanel('show');
				}
			}
		});
	}

	function changeCount(el)
	{
		var newVal  = parseInt(el.val());
		var itemId  = el.data('item-id');
		var count 	= newVal - previousVal;
		if(newVal > 0)
		{
			var request = 'inCartProductId=' + itemId + '&amount_input=' + count;
			$.ajax({
				type: "POST",
				url: mgBaseDir + "/cart",
				data: "updateCart=1&" + request,
				dataType: "json",
				cache: false,
				success: function(response)
				{
					if ('success' == response.status)
					{
						console.log(response);
						updateBasket(response.data.cart_count, response.data.cart_price_wc);
					}
				}
			});
		}
		else
		{
			if(confirm('Вы действительно хотите удалить товар из корзины?'))
				deleteItem(el);
			else
				$(el).val(previousVal);
		}
	}

	function deleteItem(el)
	{
		var itemId = $(el).data('item-id');
		$.ajax({
			type: "POST",
			url: mgBaseDir + "/cart",
			data: {
				action: "cart",
				delFromCart: 1,
				itemId: itemId,
			},
			dataType: "json",
			cache: false,
			success: function(response)
			{
				if ('success' == response.status)
				{
					updateBasket(response.data.cart_count, response.data.cart_price_wc);
					el.parent().remove();
					if(response.data.cart_count == 0)
					{
						basket.find('ul').append(emptyMsg);
						bottomPanel('hide');
					}
				}
			}
		});
	}

	function bottomPanel(typeOperation)
	{
		if(String(typeOperation) == 'show')
		{
			basket.find('.bottom-title, .order-basket').removeClass('hide');
			basket.find('.bottom-title, .order-basket').addClass('active');
		}
		else
		{
			basket.find('.bottom-title, .order-basket').removeClass('active');
			basket.find('.bottom-title, .order-basket').addClass('hide');
		}
	}

	function updateBasket(countItem, cartPrice)
	{
		basket.find('.title .count-static').text(countItem + ' шт.');
		basket.find('.bottom-title .right').text(countItem + ' x ' + cartPrice);
	}

	function updateBasketList(element, index, array)
	{
		dataCart += '<li class="good-basket clearfix">'
			+ '<span class="name-good">'+ element.title +'</span>'
			+ '<span class="price">'+ element.priceInCart +'</span>'
			+ '<input type="text" name="count" value="' + element.countInCart + '" data-item-id="' + element.id + '">'
			+ '<button data-item-id="' + element.id + '">X</button>';
	}
});