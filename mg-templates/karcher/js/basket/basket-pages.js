$(document).ready(function() {

	var countItem = 0;
	var summa 	  = 0;
	var oldCount  = 0;
	init();

	// Показ содержимого корзины
	$('#bottom-basket .title').bind('click', function(){
		$('#bottom-basket').toggleClass('show-basket');
	});

	// Добавить в корзину карточка товара
	$('.good-option .add-cart').bind('click', function(){
		var el = $(this).closest('.good-option');
		dropBasket(el);
		return false;
	});

	// Добавление в корзину кр. описание товара
	$('.like-good .add-cart').bind('click', function(){
		var el = $(this).closest('.like-good');
		dropBasket(el);
		return false;
	});

	$('.product-preview-box .add-cart').bind('click', function(){
		var el = $(this).closest('.product-preview-box');
		dropBasket(el);
		return false;
	});

	$('.add-cart.add-cart-small-btn').bind('click', function(){
		var el = $(this).closest('.bor-b');
		dropBasket(el);
		return false;
	});

	// // Перенос товара
	// $('.product-preview-box').draggable({
	// 	revert: true,
	// 	revertDuration: 0,
	// 	drag: function()
	// 	{
	// 		$(this).addClass('active');
	// 		$(this).closest('.content').addClass('active');
	// 		$('#bottom-basket').addClass('active-basket');
	// 	},
	// 	stop: function()
	// 	{
	// 		$(this).removeClass('active');
	// 		$(this).closest('.content').removeClass('active');
	// 		$('#bottom-basket').addClass('static-basket');
	// 	}
	// });

	// // Бросание товара
	// $('#bottom-basket').droppable({
	// 	activeClass: 'active-basket',
	// 	tolerance: 'touch',
	// 	drop: function(ev, ui)
	// 	{
	// 		dropBasket(ui.draggable);
	// 	}
	// });

	function priceConvert(data)
	{
		var price = Number.prototype.toFixed.call(parseFloat(data) || 0, 0),
			//заменяем точку на запятую
			price_sep   = price.replace(/(\D)/g, "."),
			//добавляем пробел как разделитель в целых
			price_sep   = price_sep.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ");
		return price_sep + '&#8399;';
	}

	// Проверка на пустоту и кол-во товаров в корзине
	function init()
	{
		updateCount();
		// Переменная не успевает инициализироваться
		setTimeout(checkEmpty, 1);
	}

	function dropBasket(move)
	{
		var basket = $('#bottom-basket');
		itemId = basket.find("li[data-id='"+move.attr('data-id')+"']");
		if(itemId.html() != null)
		{
			itemId.find('input').val(parseInt(itemId.find('input').val()) + 1);
			init();
			updateSumma(1, itemId.find('.price').html());
			// Ajax кол-во +1
		}
		else
		{
			addBasket(basket, move);
			init();
			updateSumma(1, move.find('.price').html());
			// Ajax новый товар в корзине
		}
		$('#bottom-basket').addClass('show-basket');
	}

	function updateCount()
	{
		var oInp  = $('#basket-content li input');	
		countItem = 0;

		for(i = 0; i < oInp.length; i++)
		{
			countItem += Number(oInp[i].value);
		}

		$('#bottom-basket .title .count-static').text(countItem + ' шт.');
	}

	function getPrice(str)
	{
		var price = Number(str.replace(/\D+/g,""));
		return price;
	}

	function updateSumma(count, price, is_change, is_delete)
	{
		var price = getPrice(price);
		if(is_change)
		{
			var oldPrice = price * oldCount;
			summa = (summa - oldPrice) + price * count;
		}
		else if(is_delete)
		{
			summa = summa - (price * count);
		}
		else
		{
			summa = summa + (price * count);
		}
		$('#bottom-basket .bottom-title .right').html(countItem + ' x ' + priceConvert(summa));
	}

	function checkEmpty()
	{
		if(countItem == 0)
		{
			$('#basket-content li.empty-basket').show();
			$('.bottom-title, .order-basket').hide();
		}
		else
		{
			$('#basket-content li.empty-basket').hide();
			if($('#bottom-basket').hasClass('show-basket'))
			{
				$('.bottom-title, .order-basket').show();
			}
		}
	}

	function addBasket(basket, move) 
	{
		basket.find("ul").append('<li class="good-basket clearfix" data-id="' + move.attr('data-id') + '">'
			+ '<span class="name-good">'+ move.find('.title-for-basket').html() +'</span>'
			+ '<span class="price">'+ priceConvert(getPrice(move.find('.price').html())) +'</span>'
			+ '<input type="text" name="count" value="1">'
			+ '<button>X</button>');
	}

	$(document).on('focus', '#basket-content li input', function(){
		oldCount = Number($(this).val());
	});

	//Изменение кол-ва товара
	$(document).on('change', '#basket-content li input', function(){
		updateCount();
		updateSumma($(this).val(), $(this).closest('li').find('.price').html(), true);
		// Ajax
	});

	$(document).on('click', '#basket-content button', function(){
		var item 	  = $(this).closest('li');
		var idItem    = item.attr('data-id');
		var countInp  = item.find('input').val();
		var price     = item.find('.price').html();

		item.remove();
		init();

		updateSumma(countInp, price, false, true);
		// Ajax
	});
});