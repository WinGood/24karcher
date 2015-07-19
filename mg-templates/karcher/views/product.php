<? mgSEO($data); ?>
<div class="page-content">
	[brcr]
	<div class="good-top clearfix">
		<div class="left img-good">
			<div id="image-block">
				<div id="img-big-box">
					<ul id="img-big">
						<?foreach($data['images_product'] as $image):?>
						<li>
							<a href="<?php echo $image ? SITE.'/uploads/'.$image: SITE."/uploads/no-img.jpg" ?>" rel="gallery-goods-pic" title="<?=$data['title'];?>">
								<img src="<?php echo $image ? SITE.'/uploads/thumbs/70_'.$image: SITE."/uploads/no-img.jpg" ?>" alt="<?=$data['title'];?>">
							</a>
						</li>
						<?endforeach;?>
					</ul>
				</div><!-- !div#img-big-box -->
				<?if(count($data['images_product']) > 1):?>
				<div id="img-thumbs">
					<?$Iteration = 0;?>
					<?foreach($data['images_product'] as $image):?>
					<a href="#" data-slide-index="<?=$Iteration;?>"><img src="<?php echo $image ? SITE.'/uploads/thumbs/70_'.$image: SITE."/uploads/no-img.jpg" ?>"></a>
					<?$Iteration++;?>
					<?endforeach;?>
				</div>
				<?endif;?>
			</div><!-- !div#image-block -->
		</div><!-- !div.left.img-good -->
		<div class="left good-option">
			<div class="border-b-dsh">
				<h1 class="title-good title-for-basket"><?=$data['title'];?></h1>
				<p class="good-cat"><?=$data['cat_title'];?></p>
			</div>
			<div class="border-b-dsh clearfix">
				<div class="left">
					<div class="price-block">
						<? if(!empty($data['old_price'])): ?>
						<span class="old-price"><?=$data['old_price'];?> <?=$data['currency'];?></span>
						<? endif; ?>
						<span class="price"><?=$data['price'];?> <?=$data['currency'];?></span>
					</div><!-- !div.price-block-->
					<table width="100%" class="short-ftr">
						<tr>
							<td class="title-ftr">Артикул:</td>
							<td><?=$data['code'];?></td>
						</tr>
						<tr>
							<td class="title-ftr">Наличие:</td>
							<td><?=(is_integer($data['count']) AND $data['count'] == 0) ? '(нет в наличии)' : '(есть в наличии)';?></td>
						</tr>
						<tr>
							<td class="title-ftr">Гарантия:</td>
							<td>2 года</td>
						</tr>
<!-- 						<tr>
							<td class="title-ftr">Масса:</td>
							<td>13.5 кг.</td>
						</tr> -->
						<tr>
							<td class="title-ftr">Производитель:</td>
							<td>Karcher</td>
						</tr>
					</table><!-- !table.short-ftr -->
				</div>
				<div class="right">
					<a class="add-cart add-cart-gv" href="<?=SITE;?>/catalog?inCartProductId=<?=$data['id'];?>" data-item-id="<?=$data['id'];?>"><i class="icon-basket"></i> Добавить в корзину</a>
				</div>
			</div><!-- !div.border-b-dsh -->
			<div class="border-b-dsh">
				<?=$data['description'];?>
			</div>				
		</div><!-- !div.right.good-option -->
	</div><!-- !div.good-top -->
	<div class="good-middle">
		<div id="tabs-ftr"> 
			<ul class="clearfix bor-b btn-tabs"> 
			    <!-- <li><a href="#ftr-g">Характеристики</a></li>  -->
			    <li><a href="#cmt-g" class="selected">Комментарии</a></li>  
			    <!-- <li><a href="#acr-g">Аксессуары</a></li> -->
		    </ul>
		    <div id="tabs-contents" class="bor-b">
			    <!-- <div id="ftr-g">
			    	<p class="title-tabs">Характеристики <?=$data['title'];?>:</p>
			    	<table class="table">
			    		<tr>
			    			<td class="title-fld">Расход воздуха:</td>
			    			<td>72 (л/с)</td>
			    		</tr>
			    		<tr>
			    			<td class="title-fld">Разрежение:</td>
			    			<td>210 (мбар)</td>
			    		</tr>
			    		<tr>
			    			<td class="title-fld">Водяной фильтр:</td>
			    			<td>2 (л)</td>
			    		</tr>
			    		<tr>
			    			<td class="title-fld">Макс. потребляемая мощность:</td>
			    			<td>1400 (Вт)</td>
			    		</tr>
			    		<tr>
			    			<td class="title-fld">Длина кабеля:</td>
			    			<td>7.5 (м.)</td>
			    		</tr>
			    		<tr>
			    			<td class="title-fld">Масса:</td>
			    			<td>13.5 (кг)</td>
			    		</tr>
			    		<tr>
			    			<td class="title-fld">Размеры (Д х Ш х В):</td>
			    			<td>480 x 305 x 520 (мм)</td>
			    		</tr>
			    		<tr>
			    			<td class="title-fld">Уровень шума:</td>
			    			<td>69 (дБ/А)</td>
			    		</tr>
			    		<tr>
			    			<td class="title-fld">Комплектация:</td>
			    			<td>
			    				<ol>
			    					<li>Всасывающий шланг длиной 2 м с мягкой рукояткой</li>
			    					<li>Телескопическая всасывающая труба из высококачественной стали</li>
			    					<li>Переключаемая насадка для сухой уборки</li>
			    					<li>Щелевая насадка</li>
			    					<li>Насадка для мягкой мебели</li>
			    					<li>Фильтр Perma Power</li>
			    					<li>Фильтр HEPA plus</li>
			    					<li>Пеногаситель</li>
			    				</ol>
			    			</td>
			    		</tr>
			    	</table>
			    </div> --> 
			    <div id="cmt-g">			    	
			    	<div class="clearfix">
			    		<div class="left">
			    			<p class="title-tabs">Комментарии к товару <?=$data['title'];?>:</p>
			    		</div>
			    		<div class="right">
			    			<a href="#" class="add-cmt jq-add-cmt-static-page">Добавить комментарий</a>
			    		</div>
			    	</div><!-- !div.clearfix -->
			    	<div class="comments-wr">
			    		[comments]
			    	</div><!-- !div.comments-wr -->			    	
			    </div> 
<!-- 			    <div id="acr-g">
			    	<p class="title-tabs">Аксессуары для Karcher DS 5600:</p>
			    	<div class="carousel-mr">
				    	<div class="carousel">
				    		<div class="slide">
				    			<img src="http://placehold.it/170x170">
				    			<p><a href="" target="_blank">Фильтр HEPA 12 DS 5600</a></p>
				    			<span class="old-price">15 400 руб.</span> <span class="price">12 800 руб.</span>
				    		</div>
				    		<div class="slide">
				    			<img src="http://placehold.it/170x170">
				    			<p><a href="" target="_blank">Фильтр HEPA 12 DS 5600</a></p>
				    			<span class="old-price">15 400 руб.</span> <span class="price">12 800 руб.</span>
				    		</div>
				    		<div class="slide">
				    			<img src="http://placehold.it/170x170">
				    			<p><a href="" target="_blank">Фильтр HEPA 12 DS 5600</a></p>
				    			<span class="old-price">15 400 руб.</span> <span class="price">12 800 руб.</span>
				    		</div>
				    		<div class="slide">
				    			<img src="http://placehold.it/170x170">
				    			<p><a href="" target="_blank">Фильтр HEPA 12 DS 5600</a></p>
				    			<span class="old-price">15 400 руб.</span> <span class="price">12 800 руб.</span>
				    		</div>
				    		<div class="slide">
				    			<img src="http://placehold.it/170x170">
				    			<p><a href="" target="_blank">Фильтр HEPA 12 DS 5600</a></p>
				    			<span class="old-price">15 400 руб.</span> <span class="price">12 800 руб.</span>
				    		</div>
				    	</div>
			    	</div>
			    </div> -->
		    </div><!-- !div#tabs-contents -->
		</div> 
	</div><!-- !div.good-middle -->
	<?if(!empty($data['like_goods'])):?>
	<div class="good-bottom bor-b">
		<p>Похожие товары:</p>
		<div class="like-goods clearfix">
			<div class="like-goods">
				<?foreach($data['like_goods'] as $product):?>
				<?if(!empty($product)):?>
				<div class="like-good">
				  <img src="<?php echo $product['image_url'] ? SITE.'/uploads/thumbs/70_'.$product['image_url'] : SITE."/uploads/no-img.jpg" ?>" alt="<?=$product['title'];?>">
				  <div class="desc">
				    <p class="title"><a href="<?php echo SITE ?>/<?php echo isset($product["category_url"]) ? $product["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($product["product_url"]) ?>"><?php echo $product["title"] ?></a></p>
				    <? if(!empty($product['old_price'])): ?>
				    <span class="old-price"><?=$product['old_price'];?> <?=$data['currency'];?></span>
				    <? endif; ?>
				    <span class="price"><?=$product['price'];?> <?=$data['currency'];?></span>
				  </div>
				  <a class="add-cart" href="<?=SITE;?>/catalog?inCartProductId=<?=$product['id'];?>" data-item-id="<?=$product['id'];?>"><i class="icon-basket"></i> Добавить в корзину</a>
				</div>
				<?endif;?>
				<?endforeach;?>
			</div>
		</div>
	</div>
	<?endif;?>
</div><!-- !div.page-content -->