<?php MG::enableTemplate(); ?>
<?mgSEO(array('meta_title' => 'asf'));?>
<?$site = SITE;?>
<?$breadcrumbs = <<<EOF
<div class="site-breadcrumbs">
    <ul class="site-breadcrumbs-list">
        <li class="site-breadcrumbs-list-item"><a href="$site">Главная</a></li>
        <li class="site-breadcrumbs-list-item separator">/</li>
        <li class="site-breadcrumbs-list-item current">Каталог</li>
        <li class="site-breadcrumbs-list-item separator">/</li>
        <li class="site-breadcrumbs-list-item current">Бренд</li>
    </ul>
</div>
EOF;
?>
<?MG::set('breadcrumbs', $breadcrumbs);?>
<?MG::set('pageTitle', $data['title']);?>
<?MG::set('isNotWrapper', true);?>
<div class="products-wrapper">   
    <?php
    $actionButton = MG::getSetting('actionInCatalog') === "true" ? 'actionBuy' : 'actionView';
    $currency = MG::getSetting('currency');
    $item = brand::getProductsByBrand($_GET['brand']);
    $items = $item['items'];
    $brand = $item['brand'];
    ?>
<?php if (!empty($brand)) { ?>
      <h1 class="new-products-title"><?php echo $brand['brand'] ?></h1> 
      <div class="cat-desc">	        
          <div class="cat-desc-img">
              <img src="<?php echo $brand['url'] ?>" alt="<?php echo $brand['brand'] ?>" title="<?php echo $brand['brand'] ?>" >
          </div>
          <div class="cat-desc-text"><?php echo $brand['desc'] ?></div>	
          <div class="clear"></div>
      </div>
    <?php } ?>
    <?php
    foreach ($items['catalogItems'] as &$item) {
      $imagesUrl = explode("|", $item['image_url']);
      $items = "";
      if (!empty($imagesUrl[0])) {
        $item['image_url'] = $imagesUrl[0];
      }
      if ($item['activity'] == 1) {
        ?>

        <div class="product-wrapper">
            <div class="product-image">
                <?php
                echo $item['recommend'] ? '<span class="sticker-recommend"></span>' : '';
                echo $item['new'] ? '<span class="sticker-new"></span>' : '';
                ?> 
                <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo $item["product_url"] ?>">
                    <img src="<?php echo $item["image_url"] ? SITE.'/uploads/thumbs/70_'.$item["image_url"] : SITE."/uploads/no-img.jpg" ?>" alt="">
                </a>
            </div>
            <div class="product-name">
                <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?>/<?php echo $item["product_url"] ?>"><?php echo $item["title"] ?></a>
            </div>       

            <span class="product-price"><?php echo $item["price"] ?> <?php echo $currency; ?></span>
            <!--Кнопка, кототорая меняет свое значение с "В корзину" на "Подробнее"-->
            <?php
            if (!$item['liteFormData']) {
              if ($item['count'] == 0) {
                echo $item['actionView'];
              } else {
                echo $item[$actionButton];
              }
            } else {
              echo $item['liteFormData'];
            }
            ?>

        </div>
        <?php
      }
    }
    ?>
    <div class="clear"></div> 
</div>
