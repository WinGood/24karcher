<?mgSEO($data);?>
<? if(empty($_GET['search'])): ?>
  <? if($data['is_main_cat']): ?>
  <div class="page-content">
    [brcr]
    <h1 class="title-page"><?=$data['titeCategory'];?></h1>
    <div class="desc-cat">
      <?$catImg = getImgCat($data['id_category']);?>
      <?if(!empty($catImg)):?>
      <img src="<?=SITE.'/'.$catImg;?>">
      <?else:?>
      <img src="http://placehold.it/1031x245.jpg">
      <?endif;?>
      <div class="desc-cat-text bor-b">
        <?=$data['cat_desc'];?>
      </div><!-- !div.desc-cat-text -->
    </div><!-- !div.desc-cat -->
    <?if(!empty($data['category_info'])):?>
    <?foreach($data['category_info'] as $item):?>
    <div class="good-bottom bor-b">
      <h3 class="title-sub-cat">
        <a href="<? echo SITE.'/'.$item['parent_url'].$item['url'];?>"><?=$item['title'];?>:</a>
        <span><?=$item['count'];?></span>
      </h3>
      <div class="like-goods clearfix">
      <?if(!empty($item['items'])):?>
        <?foreach($item['items'] as $product):?>
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
        <?endforeach;?>
        <?else:?>
        <p>Товары не найдены</p>
      <?endif;?>
      </div><!-- !div.like-goods -->
    </div><!-- !div.good-bottom -->
    <?endforeach;?>
    <?else:?>
    <div class="white-box bor-b">
      <p>Информация не найдена</p>
    </div>
    <?endif;?>
  </div><!-- !div.page-content -->
  <? else: ?>
  <div class="page-content">
    [brcr]
    <h1 class="title-page"><?=$data['titeCategory'];?></h1>
    <div class="desc-cat">
      <?$catImg = getImgCat($data['id_category']);?>
      <?if(!empty($catImg)):?>
      <img src="<?=SITE.'/'.$catImg;?>">
      <?else:?>
      <img src="http://placehold.it/1031x245.jpg">
      <?endif;?>
      <div class="desc-cat-text bor-b">
        <?=$data['cat_desc'];?>
      </div><!-- !div.desc-cat-text -->
    </div><!-- !div.desc-cat -->
    <div class="box-sort-params">
      <table width="100%">
        <tr>
          <td class="left">
            <label for="slcSort">Сортировать</label>
            <select name="slcSort" id="slcSort" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
              <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'sort', 'priceDesc');?>" <?=$_GET['sort'] == 'priceDesc' ? 'selected="selected"' : '' ;?>>По убыванию цены</option>
              <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'sort', 'priceAsc');?>" <?=$_GET['sort'] == 'priceAsc' ? 'selected="selected"' : '' ;?>>По возрастанию цены</option>
              <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'sort', 'comments');?>" <?=$_GET['sort'] == 'comments' ? 'selected="selected"' : '' ;?>>Обсуждаемые</option>
              <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'sort', 'popular');?>" <?=$_GET['sort'] == 'popular' ? 'selected="selected"' : '' ;?>>Популярности</option>
            </select>
          </td>
          <td class="left">
            <label for="slcShow">Показывать</label>
            <select name="slcShow" id="slcShow" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
              <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'count', 8);?>"  <?=$_GET['count'] == 8  ? 'selected="selected"' : '' ;?>>8</option>
              <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'count', 12);?>" <?=$_GET['count'] == 12 ? 'selected="selected"' : '' ;?>>12</option>
              <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'count', 16);?>" <?=$_GET['count'] == 16 ? 'selected="selected"' : '' ;?>>16</option>
            </select>
          </td>
          <td class="left">
            <label for="chkStock">
              <?$_GET['stock'] == 1 ? $valCheck = 0 : $valCheck = 1;?>
              <input type="checkbox" value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'stock', $valCheck);?>" name="chkStock" id="chkStock" <?=$_GET['stock'] ? 'checked="checked"' : '';?> onchange="window.location = this.value;">
              Только в наличии
            </label>
          </td>
          <td class="right">
            <?$valView = $_GET['type-view'];?>
            <a href="<?=URL::add_get($_SERVER['REQUEST_URI'], 'type-view', 'grid');?>" class="icon-grid <?=($valView == 'grid' OR $valView == '') ? 'select' : '';?>"></a>
            <a href="<?=URL::add_get($_SERVER['REQUEST_URI'], 'type-view', 'list');?>" class="icon-list <?=$valView == 'list' ? 'select' : '';?>"></a>
          </td>
        </tr>
      </table>
    </div><!-- !div.box-sort-params -->
    <div id="space-goods">
      <?if(empty($_GET['type-view']) OR $_GET['type-view'] == 'grid'):?>
      <? $Iteration = 0; ?>
          <?if(!empty($data['items'])):?>
            <?foreach($data['items'] as $item):?>
            <? if($Iteration == 0 OR ($Iteration % 4) == 0): ?>
            <div class="grid-view-goods clearfix">
            <?endif;?>
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
              <?$Iteration++;?>
              <? if(($Iteration % 4) == 0): ?>
              </div><!-- div.grid-view-goods.clearfix -->
              <?endif;?>
            <?endforeach;?>
            <? if($Iteration == 0): ?>
            </div><!-- div.grid-view-goods.clearfix -->
            <? endif; ?>
          <?else:?>
          <div class="white-box bor-b">
            <p>Товары не найдены</p>
          </div>
          <?endif;?>
      <?else:?>
      <?if(!empty($data['items'])):?>
          <div class="list-view-goods">
            <!-- Фото название наличие цена -->
            <table width="100%">
              <thead>
                <tr>
                  <td width="5%" class="text-center">Фото</td>
                  <td width="29%">Название</td>
                  <td width="5%" class="text-center">Наличие</td>
                  <td width="6%" class="text-center">Цена, руб.</td>
                  <td width="2%" class="text-center"></td>
                </tr>
              </thead>
              <tbody>
                <?foreach($data['items'] as $item):?>
                <tr class="bor-b">
                  <td class="list-img">
                    <img src="<?php echo $item['image_url'] ? SITE.'/uploads/thumbs/70_'.$item['image_url'] : SITE."/uploads/no-img.jpg" ?>" alt="<?=$item['title'];?>" title="<img src='<?php echo $item['image_url'] ? SITE.'/uploads/thumbs/70_'.$item['image_url'] : SITE."/uploads/no-img.jpg" ?>' width='175'>">
                  </td>
                  <td class="title-good-list"><a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a></td>
                  <td class="text-center"><?=$item['count'] == 0 ? '(нет в наличии)' : '(есть в наличии)';?></td>
                  <td class="text-center price"><?=$item['price'];?></td>
                  <td class="text-center">
                    <a class="add-cart add-cart-small-btn <?=checkGoodInCart($item['id']) ? 'on' : '';?>" href="<?=SITE;?>/catalog?inCartProductId=<?=$item['id'];?>" data-item-id="<?=$item['id'];?>" title="Добавить в корзину '<?=$item['title'];?>'">
                      <i class="icon-basket"></i>
                    </a>
                  </td>
                </tr>
                <?endforeach;?>
              </tbody>
            </table>
          </div><!-- !div.list-view-goods -->
      <?else:?>
      <div class="white-box bor-b">
        <p>Товары не найдены</p>
      </div>
      <?endif;?>
      <?endif;?>
    </div><!-- !div#space-goods -->
    <div class="clearfix" style="margin-top:15px;">
      <?=$data['pager'];?>
    </div>
  </div><!-- !div.page-content -->
  <? endif; ?>
<? else: ?>
<div class="page-content">
  <ul class="breadcrumbs bor-b">
    <li><a href="<?=SITE;?>">Главная</a></li>
    <span> / </span>
    <li>Поиск</li>
  </ul><!-- !ul.breadcrumbs -->
<h1 class="title-page">Поиск по фразе "<?=$data['searchData']['keyword'];?>"</h1>
<div class="box-sort-params">
  <table width="100%">
    <tr>
      <td class="left">
        <label for="slcSort">Сортировать</label>
        <select name="slcSort" id="slcSort" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
          <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'sort', 'priceDesc');?>" <?=$_GET['sort'] == 'priceDesc' ? 'selected="selected"' : '' ;?>>По убыванию цены</option>
          <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'sort', 'priceAsc');?>" <?=$_GET['sort'] == 'priceAsc' ? 'selected="selected"' : '' ;?>>По возрастанию цены</option>
          <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'sort', 'comments');?>" <?=$_GET['sort'] == 'comments' ? 'selected="selected"' : '' ;?>>Обсуждаемые</option>
          <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'sort', 'popular');?>" <?=$_GET['sort'] == 'popular' ? 'selected="selected"' : '' ;?>>Популярности</option>
        </select>
      </td>
      <td class="left">
        <label for="slcShow">Показывать</label>
        <select name="slcShow" id="slcShow" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
          <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'count', 8);?>"  <?=$_GET['count'] == 8  ? 'selected="selected"' : '' ;?>>8</option>
          <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'count', 12);?>" <?=$_GET['count'] == 12 ? 'selected="selected"' : '' ;?>>12</option>
          <option value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'count', 16);?>" <?=$_GET['count'] == 16 ? 'selected="selected"' : '' ;?>>16</option>
        </select>
      </td>
      <td class="left">
        <label for="chkStock">
          <?$_GET['stock'] == 1 ? $valCheck = 0 : $valCheck = 1;?>
          <input type="checkbox" value="<?=URL::add_get($_SERVER['REQUEST_URI'], 'stock', $valCheck);?>" name="chkStock" id="chkStock" <?=$_GET['stock'] ? 'checked="checked"' : '';?> onchange="window.location = this.value;">
          Только в наличии
        </label>
      </td>
      <td class="right">
        <?$valView = $_GET['type-view'];?>
        <a href="<?=URL::add_get($_SERVER['REQUEST_URI'], 'type-view', 'grid');?>" class="icon-grid <?=($valView == 'grid' OR $valView == '') ? 'select' : '';?>"></a>
        <a href="<?=URL::add_get($_SERVER['REQUEST_URI'], 'type-view', 'list');?>" class="icon-list <?=$valView == 'list' ? 'select' : '';?>"></a>
      </td>
    </tr>
  </table>
</div><!-- !div.box-sort-params -->
<div id="space-goods">
  <?if(empty($_GET['type-view']) OR $_GET['type-view'] == 'grid'):?>
  <? $Iteration = 0; ?>
      <?if(!empty($data['items'])):?>
        <?foreach($data['items'] as $item):?>
        <? if($Iteration == 0 OR ($Iteration % 4) == 0): ?>
        <div class="grid-view-goods clearfix">
        <?endif;?>
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
          <?$Iteration++;?>
          <? if(($Iteration % 4) == 0): ?>
          </div><!-- div.grid-view-goods.clearfix -->
          <?endif;?>
        <?endforeach;?>
        <? if($Iteration == 0): ?>
        </div><!-- div.grid-view-goods.clearfix -->
        <? endif; ?>
      <?else:?>
      <div class="white-box bor-b">
        <p>Товары не найдены</p>
      </div>
      <?endif;?>
  <?else:?>
  <?if(!empty($data['items'])):?>
      <div class="list-view-goods">
        <!-- Фото название наличие цена -->
        <table width="100%">
          <thead>
            <tr>
              <td width="5%" class="text-center">Фото</td>
              <td width="29%">Название</td>
              <td width="5%" class="text-center">Наличие</td>
              <td width="6%" class="text-center">Цена, руб.</td>
              <td width="2%" class="text-center"></td>
            </tr>
          </thead>
          <tbody>
            <?foreach($data['items'] as $item):?>
            <tr class="bor-b">
              <td class="list-img">
                <img src="<?php echo $item['image_url'] ? SITE.'/uploads/thumbs/70_'.$item['image_url'] : SITE."/uploads/no-img.jpg" ?>" alt="<?=$item['title'];?>" title="<img src='<?php echo $item['image_url'] ? SITE.'/uploads/thumbs/70_'.$item['image_url'] : SITE."/uploads/no-img.jpg" ?>' width='175'>">
              </td>
              <td class="title-good-list"><a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo htmlspecialchars($item["product_url"]) ?>"><?php echo $item["title"] ?></a></td>
              <td class="text-center"><?=$item['count'] == 0 ? '(нет в наличии)' : '(есть в наличии)';?></td>
              <td class="text-center price"><?=$item['price'];?></td>
              <td class="text-center">
                <a class="add-cart add-cart-small-btn <?=checkGoodInCart($item['id']) ? 'on' : '';?>" href="<?=SITE;?>/catalog?inCartProductId=<?=$item['id'];?>" data-item-id="<?=$item['id'];?>" title="Добавить в корзину '<?=$item['title'];?>'">
                  <i class="icon-basket"></i>
                </a>
              </td>
            </tr>
            <?endforeach;?>
          </tbody>
        </table>
      </div><!-- !div.list-view-goods -->
  <?else:?>
  <div class="white-box bor-b">
    <p>Ничего не найдено</p>
  </div>
  <?endif;?>
  <?endif;?>
</div><!-- !div#space-goods -->
<div class="clearfix" style="margin-top:15px;">
  <?=$data['pager'];?>
</div>
</div><!-- !div.page-content -->
<?=$data['is_main_cat'];?>
<? endif; ?>