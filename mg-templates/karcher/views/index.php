<? mgSEO($data); ?>
[slider-images]
<div class="tabs-box">	
	<div class="product-list-title clearfix">
		<div class="left"><p>Популярные товары</p></div>
		<div class="right">
			<ul class="tabs-ui">
				<li class="last-tab right-tab"><a href="#" rel="next"></a></li>
				<li class="left-tab"><a href="#" rel="prev"></a></li>
			</ul>
		</div>				
	</div>		
	<div class="product-list tabs-content clearfix">
	<? if(!empty($data['popularGoods'])): ?>
		<? $pIteration = 0; ?>
		<? foreach($data['popularGoods'] as $item): ?>
			<? if($pIteration == 0): ?>
				<div class="tab tab-first">
				<? elseif(($pIteration % 4) == 0): ?>
				<div class="tab">
			<? endif; ?>
			<div class="product-preview-box">
				<img src="<?php echo $item['image_url'] ? SITE.'/uploads/thumbs/70_'.$item['image_url'] : SITE."/uploads/no-img.jpg" ?>" alt="<?=$item['title'];?>">
				<div class="desc-preview">
					<p class="title"><a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a></p>
					<? if(!empty($item['old_price'])): ?>
					<span class="old-price"><?=$item['old_price'];?> <?=$data['currency'];?></span>
					<? endif; ?>
					<span class="price"><?=$item['price'];?> <?=$data['currency'];?></span>
				</div>
				<a class="add-cart" href="<?=SITE;?>/catalog?inCartProductId=<?=$item['id'];?>" data-item-id="<?=$item['id'];?>"><i class="icon-basket"></i> Добавить в корзину</a>
			</div>
			<? $pIteration++; ?>
			<? if($pIteration % 4 == 0): ?>
				</div><!-- !div.tab -->
			<? endif; ?>
		<? endforeach; ?>
		<? if($pIteration == 0): ?>
		</div><!-- !div.tab -->
		<? endif; ?>
	<? endif; ?>					
	</div><!-- !div.product-list.tabs-content -->
</div><!-- !div.tabs-box -->
<div class="tabs-box">	
	<div class="product-list-title clearfix">
		<div class="left"><p>Новые товары</p></div>
		<div class="right">
			<ul class="tabs-ui">
				<li class="last-tab right-tab"><a href="#" rel="next"></a></li>
				<li class="left-tab"><a href="#" rel="prev"></a></li>
			</ul>
		</div>				
	</div>
	<div class="product-list tabs-content clearfix">
	<? if(!empty($data['newGoods'])): ?>
		<? $nIteration = 0; ?>
		<? foreach($data['newGoods'] as $item): ?>
			<? if($nIteration == 0): ?>
				<div class="tab tab-first">
				<? elseif(($nIteration % 4) == 0): ?>
				<div class="tab">
			<? endif; ?>
			<div class="product-preview-box">
				<img src="<?php echo $item['image_url'] ? SITE.'/uploads/thumbs/70_'.$item['image_url'] : SITE."/uploads/no-img.jpg" ?>" alt="<?=$item['title'];?>">
				<div class="desc-preview">
					<p class="title"><a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a></p>
					<? if(!empty($item['old_price'])): ?>
					<span class="old-price"><?=$item['old_price'];?> <?=$data['currency'];?></span>
					<? endif; ?>
					<span class="price"><?=$item['price'];?> <?=$data['currency'];?></span>
				</div>
				<a class="add-cart" href="<?=SITE;?>/catalog?inCartProductId=<?=$item['id'];?>" data-item-id="<?=$item['id'];?>"><i class="icon-basket"></i> Добавить в корзину</a>
			</div>
			<? $nIteration++; ?>
			<? if($nIteration % 4 == 0): ?>
				</div><!-- !div.tab -->
			<? endif; ?>
		<? endforeach; ?>
		<? if($nIteration == 0): ?>
		</div><!-- !div.tab -->
		<? endif; ?>
	<? endif; ?>					
	</div><!-- !div.product-list.tabs-content -->
</div><!-- !div.tabs-box -->
<div class="tabs-box">
	<div class="product-list-title clearfix">
		<div class="left"><p>Последние новости и статьи</p></div>
		<div class="right">
			<ul class="tabs-ui">
				<li class="last-tab right-tab"><a href="#" rel="next"></a></li>
				<li class="left-tab"><a href="#" rel="prev"></a></li>
			</ul>
		</div>				
	</div>
	<div class="product-list tabs-content clearfix">
		[news-anons count='4']
	</div><!-- !div.product-list.tabs-content -->
</div><!-- !div.tabs-box -->