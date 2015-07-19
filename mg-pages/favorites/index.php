<?mgSEO(array('meta_title' => 'Избранные товары'));?>
<?$site = SITE;?>
<?$breadcrumbs = <<<EOF
<div class="site-breadcrumbs">
    <ul class="site-breadcrumbs-list">
        <li class="site-breadcrumbs-list-item"><a href="$site">Главная</a></li>
        <li class="site-breadcrumbs-list-item separator">/</li>
        <li class="site-breadcrumbs-list-item current">Избранные товары</li>
    </ul>
</div>
EOF;
?>
<?MG::set('breadcrumbs', $breadcrumbs);?>
<?MG::set('pageTitle', 'Избранное');?>
<?MG::set('isNotWrapper', true);?>
<?if(empty($data['productPositions'])):?>
    <div class="well">
        <span>Товары в избранном отсутствуют</span>
    </div>
<?else:?>
    <table width="100%" class="table" cellpadding="0" cellspacing="0">
        <form method="post" action="<?php echo SITE?>/cart">
            <thead>
            <tr>
                <td width="7%" class="text-center">№</td>
                <td width="43%">Наименование</td>
                <td width="13%" class="text-center">Цена, руб.</td>
                <td width="8%" class="text-center">Удалить</td>
            </tr>
            </thead>
            <tbody>
            <?$i = 1; foreach($data['productPositions'] as $item):?>
                <tr>
                    <td class="text-center"><?=$i;?></td>
                    <td class="title-good">
                        <a href="<?php echo SITE ?>/<?php echo isset($item["category_url"]) ? $item["category_url"] : 'catalog' ?><?php echo $item["product_url"] ?>" target="_blank">
                            <?php echo $item['title'] ?>
                        </a>
                    </td>
                    <td class="text-center"><?=substr($item['priceInCart'], 0, strpos($item['priceInCart'], 'руб.'));?></td>
                    <td class="text-center">
                        <a class="mp-remove-cart-product" href="<?php echo SITE ?>/favorites?delItemFavorites=<?php echo $item['id']?>"></a>
                        <!-- <input type="checkbox" name="del_<?php echo $item['id'] ?>[]" value="<?=$item['id'];?>"> -->
                    </td>
                </tr>
                <?$i++;?>
            <?endforeach;?>
            </tbody>
        </form>
    </table>
<?endif;?>