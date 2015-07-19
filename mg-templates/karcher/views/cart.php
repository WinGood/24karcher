<? mgSEO($data); ?>
<?php mgTitle('Корзина');?>
<div class="page-content">
	<ul class="breadcrumbs bor-b">
	  <li><a href="<?=SITE;?>">Главная</a></li>
	  <span> / </span>
	  <li>Корзина</li>
	</ul><!-- !ul.breadcrumbs -->
	<h1 class="title-page">Корзина товаров</h1>
	<?if(!empty($data['productPositions'])):?>
	<form method="post" action="<?php echo SITE?>/cart">
	<div id="cart-wp">
	  <table width="100%">
	    <thead>
	      <tr>
	        <td width="7%" class="text-center">№</td>
	        <td width="43%">Наименование</td>
	        <td width="13%" class="text-center">Цена, руб.</td>
	        <td width="11%" class="text-center">Кол-во</td>
	        <td width="13%" class="text-center">Сумма, руб.</td>
	        <td width="8%" class="text-center">Удалить</td>
	      </tr>
	    </thead>
	    <tbody>
	      <?$iteration = 1;?>
	      <?foreach($data['productPositions'] as $item):?>
	      <tr>
	        <td class="text-center"><?=$iteration;?></td>
	        <td class="title-good">
	          <a target="_blank" href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a>
	        </td>
	        <td class="text-center"><?=$item['price']?></td>
	        <td class="text-center">
	          <input type="text" class="amount_input zeroToo"  name="item_<?php echo $item['id'] ?>[]" value = "<?php echo $item['countInCart']?>"/>
	          <input type="hidden"  name="property_<?php echo $item['id'] ?>[]" value = "<?php echo $item['property'] ?>"/>
	        </td>
	        <td class="text-center"><?=substr($item['priceInCart'], 0, strpos($item['priceInCart'], 'руб.'));?></td>
	        <td class="text-center">
	          <input type="checkbox" name="del_<?php echo $item['id'] ?>[]" value="<?=$item['id'];?>">
	        </td>
	      </tr>
	      <?$iteration++;?>
	      <?endforeach;?>
	    </tbody>
	    <tfoot>
	      <tr>
	        <td colspan="2" class="tfoot-pd">
	          <a href="<?=SITE;?>/order" class="continue-btn">Оформить заказ</a>
	        </td>
	        <td class="text-center">
	          <button type="submit" name="refresh" class="refresh-btn" title="Пересчитать" value="Пересчитать">Пересчитать</button>
	        </td>
	        <td class="tfoot-pd text-right">
	          <strong>Итого:</strong>
	        </td>
	        <td class="text-center">
	          <strong><?=$data['totalSumm'];?>руб.</strong>
	        </td>
	        <td></td>
	      </tr>
	    </tfoot>
	  </table>
	  </form>
	</div><!-- !div#cart-wp -->
	<?else:?>
	<div class="white-box">Ваша корзина пуста</div>
	<?endif;?>
</div><!-- !div.page-content -->

