<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<? mgMeta(); ?>
	<link href="<?=PATH_SITE_TEMPLATE;?>/css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php echo PATH_SITE_TEMPLATE ?>/css/jquery.tooltipster.css">
	<link rel="stylesheet" href="<?php echo PATH_SITE_TEMPLATE ?>/css/themes/tooltipster-shadow.css">
	<link rel="stylesheet" href="<?php echo PATH_SITE_TEMPLATE ?>/css/jquery.bxslider.css">
	<link rel="stylesheet" href="<?php echo PATH_SITE_TEMPLATE ?>/css/basket.css">
	<link rel="stylesheet" href="<?php echo PATH_SITE_TEMPLATE ?>/css/pages.css">
	<script type="text/javascript" src="<?php echo PATH_SITE_TEMPLATE ?>/js/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="<?php echo SITE ?>/mg-core/script/jquery.maskedinput.min.js"></script>
	<script src="<?php echo PATH_SITE_TEMPLATE ?>/js/jquery.cookie.js"></script>
	<script src="<?php echo PATH_SITE_TEMPLATE ?>/js/effects.core.js"></script>
	<script src="<?php echo PATH_SITE_TEMPLATE ?>/js/effects.transfer.js"></script>
	<script src="<?php echo PATH_SITE_TEMPLATE ?>/js/jquery.bxslider.min.js"></script>
	<script src="<?php echo PATH_SITE_TEMPLATE ?>/js/jquery.idTabs.min.js"></script>
	<script src="<?php echo PATH_SITE_TEMPLATE ?>/js/jquery.tooltipster.min.js"></script>
	<script src="<?php echo PATH_SITE_TEMPLATE ?>/js/pages.js"></script>
	<script src="<?php echo PATH_SITE_TEMPLATE ?>/js/tabs.js"></script>
	<script src="<?php echo PATH_SITE_TEMPLATE ?>/js/basket/basket.js"></script>
</head>
<body>
	<div class="wrapper">
		<div id="header" class="bor-b">
			<div id="top-navigation" class="clearfix">
			<div class="left">
				<?php mgMenuFull();?>			
			</div>
			<div class="right top-search">	
				<form method="get" action="<?php echo SITE?>/catalog" class="search-form">
					<input type="text" autocomplete="off" name="search" placeholder="поиск по каталогу">
				</form>			
			</div><!-- !div.right.top-search -->
			</div><!-- !div#top-navigation -->
			<div id="header-info" class="clearfix">
				<div class="left logo-box">
					<h1><?=MG::getOption('sitename');?></h1>
					<p class="slogan"><?=TplInfo::getOption('slogan');?></p>
				</div>
				<div class="right cart-basket-box">
				<table width="100%">
					<?if($user = !USER::getThis()):?>
					<tr>
						<td>
							<p><i class="icon-lock"></i> <a href="<?php echo SITE?>/personal" class="orange b-d-ntd"> Личный кабинет</a></p>
						</td>
					</tr>
					<tr>
						<td>
							<p><i class="icon-lock"></i> <a href="<?php echo SITE?>/forgotpass" class="orange b-d-ntd"> Восстановление пароля</a></p>
						</td>
					</tr>
					<?else:?>
					<tr>
						<td><p><i class="icon-lock"></i> <a href="<?php echo SITE?>/personal" class="orange b-d-ntd"> <?=$_SESSION['user']->email;?></a></p></td>
					</tr>
					<tr>
						<td><p><i class="icon-lock"></i> <a href="<?php echo SITE?>/enter?logout=1" class="orange b-d-ntd"> Выход</a></p></td>
					</tr>
					<?endif;?>
				</table>
				</div>
				<div class="right contacts-box">
				<table width="100%">
					<tr class="td-phones">
						<td width="180" height="35">
							<span class="gray"><i class="icon-phone"></i> (391) </span><span class="orange"><?=TplInfo::getOption('phone');?></span>						
						</td>
						<td><span class="gray"><i class="icon-phone"></i> (391) </span><span class="orange"><?=TplInfo::getOption('fax');?></span></td>
					</tr>
					<tr>
						<td>[call-back]</td>
						<td><i class="icon-mail"></i> <a href="mailto:<?=MG::getOption('adminEmail');?>" class="gray"><?=MG::getOption('adminEmail');?></a></td>
					</tr>
				</table>
				</div>			
			</div><!-- !div#header-info -->
		</div><!-- !div#header -->
		<div class="middle">
			<div class="container">
				<main class="content">
				<? $aUri = URL::getSections(); ?>
			<? if(MG::get('isStaticPage')): ?>
				<? $pageId = MG::get('page-id'); ?>
				<div class="page-content">
				<ul class="breadcrumbs bor-b">
					<li><a href="<?=SITE;?>" class="home">Главная</a></li>
					<span> / </span>
					<li><?=MG::get('title');?></li>
				</ul><!-- !ul.breadcrumbs -->
				<h1 class="title-page"><?=MG::get('title');?></h1>
					<? if($aUri[1] == 'contacts' && empty($aUri[2])): ?>
					<div class="white-box bor-b contacts-list">
						<?=$data['content'];?>
					</div><!-- !div.page-content-box.contacts-list -->
					<div class="cart-box bor-b">
						<h3>Связаться с нами</h3>
						<div class="cart-box-body">
							[feed-back-ajx]
						</div>
					</div><!-- !div.cart-box .bor-b -->
					<? else: ?>
						<? if(getImgPage($pageId)): ?>
						<img src="<?=SITE.'/'.getImgPage($pageId);?>">
						<? endif; ?>
						<div class="white-box bor-b">
							<?=$data['content'];?>
						</div><!-- !div.page-content-box -->
						<div class="clearfix static-comments-btn">
							<div class="left">
								<p>Комментарии:</p>
							</div>
							<div class="right">
								<a href="#" class="add-cmt jq-add-cmt-static-page">Добавить комментарий</a>
							</div>
						</div><!-- !div.clearfix -->
						[comments]
					<? endif; ?>
				</div><!-- !div.page-content -->
				<? else: ?>
					<?=$data['content'];?>
				<?endif;?>
				</main><!-- .content -->
			</div><!-- .container-->
			<aside class="left-sidebar">
				<div class="sidebar-box">
					<div class="title-box"><i class="icon-folder"></i>Каталог</div>
					<ul class="nav-content">
						<?=$data['categoryList'];?>
					</ul>
				</div><!-- !div.sidebar-box -->
				<div class="sidebar-box">
					<a href="<? echo SITE . '/' . trim(TplInfo::getOption('leftBannerUrl'), '/');?>"><img src="<?=TplInfo::getBanner();?>"></a>
				</div><!-- !div.sidebar-box -->
				<div class="sidebar-box bor-b">
					<div class="title-box"><i class="icon-ribbon"></i>Бестселлеры</div>
					<? $bestSellers = getBestSeller(); ?>
					<? if(!empty($bestSellers)): ?>
					<? foreach($bestSellers as $item): ?>
						<div class="left-sidebar-product clearfix">
							<div class="left preview-sidebar-img">
								<img src="<?php echo $item['image_url'] ? SITE.'/uploads/thumbs/70_'.$item['image_url'] : SITE."/uploads/no-img.jpg" ?>" alt="<?=$item['title'];?>" />
							</div>
							<div class="left desc">
								<p><a class="orange" href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a></p>
								<? if(!empty($item['old_price'])): ?>
								<span class="old-price"><?=$item['old_price'];?> <?=$data['currency'];?></span>
								<? endif; ?>
								<span class="price"><?=$item['price'];?> <?=$data['currency'];?></span>					
							</div>
						</div><!-- .left-sidebar-product -->
					<? endforeach; ?>
					<? endif; ?>
				</div>
				<div class="sidebar-box bor-b tabs-box">
					<div class="title-box clearfix no-pd">
						<div class="left special-sd-box"><i class="icon-sale"></i>Скидки</div>
						<div class="right">
							<ul class="tabs-ui">
								<li class="last-tab right-tab"><a href="#" rel="next"></a></li>
								<li class="left-tab"><a href="#" rel="prev"></a></li>
							</ul>
						</div>
					</div>
					<div class="tabs-content">
						<? $saleGoods = getSaleGoods(); ?>
						<? $pIteration = 0; ?>
						<? if(!empty($saleGoods)): ?>
						<? foreach($saleGoods as $item): ?>
						<? if($pIteration == 0) $class = 'tab tab-first'; else $class = 'tab'; ?>
							<div class="product-preview-box special-sd-product <?=$class;?>">
								<img src="<?php echo $item['image_url'] ? SITE.'/uploads/thumbs/70_'.$item['image_url'] : SITE."/uploads/no-img.jpg" ?>" alt="<?=$item['title'];?>" />
								<div class="desc-preview">
									<p class="title"><a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a></p>
									<? if(!empty($item['old_price'])): ?>
									<span class="old-price"><?=$item['old_price'];?> <?=$data['currency'];?></span>
									<? endif; ?>
									<span class="price"><?=$item['price'];?> <?=$data['currency'];?></span>	
								</div>					
							</div><!-- !div.product-preview-box.tab -->
						<? $pIteration++;endforeach; ?>
						<? endif; ?>
					</div><!-- !div.tabs-content -->					
				</div><!-- !div.sidebar-box -->			
			</aside><!-- .left-sidebar -->
		</div><!-- .middle-->
	</div><!-- !div.wrapper -->
	<div id="footer">
		<div id="footer-top-bg" class="bor-t">
			<div class="footer-wrapper clearfix">
				<div class="left">
					<p class="title">Контакты</p>
					<ul>
						<li><i class="icon-map-pointer"></i> <?=TplInfo::getOption('addrFooter');?></li>
						<li><i class="icon-phone"></i> 8(391)<?=TplInfo::getOption('phone');?></li>
						<li><i class="icon-mail"></i> <a href="mailto:<?=MG::getOption('adminEmail');?>"><?=MG::getOption('adminEmail');?></a></li>
					</ul>
				</div>
				<div class="left-small">
					<p class="title">Информация</p>
					<ul class="info-list">
						<li><a href="">Возврат товара</a></li>
						<li><a href="">Условия соглашения</a></li>
						<li><a href="">Сервисное облуживание</a></li>
						<li><a href="">Подарочные сертификаты</a></li>
						<li><a href="">Акции</a></li>
						<li><a href="">Карта сайта</a></li>
					</ul>
				</div>
				<div class="left-big">
					<p class="title">О нас</p>
					<?=TplInfo::getOption('aboutFooter');?>
				</div>
			</div><!-- !div.footer-wrapper -->
		</div><!-- !div#footer-top-bg -->
		<div id="footer-bottom-bg">
			<div id="copyright-info" class="footer-wrapper">
				<p><span>&#169; 2014</span> <?=MG::getOption('sitename');?> <span><?=TplInfo::getOption('slogan');?></span></p>
			</div>
		</div><!-- !#footer-bottom-bg -->	
	</div><!-- !div#footer -->
	<div id="bottom-basket" class="static-basket">
		<div class="title clearfix">
			<span class="left">Корзина</span>
			<span class="right count">цена</span>
			<span class="right count-static"><?php echo $data['cartCount'] ? $data['cartCount'] : 0 ?> шт.</span>
		</div>
		<div id="basket-content">
			<ul>
				<? if(!empty($data['cartData']['dataCart'])): ?>
				<? foreach($data['cartData']['dataCart'] as $item): ?>
					<li class="good-basket clearfix">
						<span class="name-good"><?=$item['title'];?></span>
						<span class="price"><?=$item['price'];?> <?php echo $data['currency']; ?></span>
						<input type="text" name="count" value="<?=$item['countInCart'];?>" data-item-id="<?=$item['id'];?>">
						<button data-item-id="<?=$item['id'];?>">X</button>
					</li>
				<? endforeach; ?>
				<? else: ?>
				<li class="empty-basket">Корзина пуста</li>
				<? endif; ?>
			</ul>
		</div>
		<div class="bottom-title clearfix">
			<span class="left">Итого:</span>
			<span class="right"><?php echo $data['cartCount'] ? $data['cartCount'] : 0 ?> x <?php echo $data['cartData']['cart_price_wc'] ?></span>
		</div>
		<div class="order-basket clearfix">
			<span class="right"><a href="<?php echo SITE ?>/order">Оформить заказ &#8594;</a></span>
		</div>
	</div><!-- !div#bottom-basket -->
</body>