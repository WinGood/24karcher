<?$data['category']['meta_title'] = $data['category']['title'];?>
<? mgSEO($data['category']); ?>
<?if (!class_exists('Blog')) {
    echo "Плагин не подключен!";

    return false;
}?>
<? $site = SITE; ?>
<?$breadcrumbs = <<<EOF
<div class="site-breadcrumbs">
    <ul class="site-breadcrumbs-list">
        <li class="site-breadcrumbs-list-item"><a href="$site">Главная</a></li>
        <li class="site-breadcrumbs-list-item separator">/</li>
        <li class="site-breadcrumbs-list-item current">Новости</li>
    </ul>
</div>
EOF;
?>
<? MG::set('breadcrumbs', $breadcrumbs); ?>
<? MG::set('pageTitle', 'Новости'); ?>
<? MG::set('isNotWrapper', true); ?>
<?php foreach ($data["entity"] as $news): ?>
    <div class="news-preview clearfix">
        <div class="left thumbnail">
            <? if (!empty($news['image_url'])): ?>
                <img src="<?php echo SITE ?>/uploads/blog/thumbs/70_<?php echo $news['image_url'] ?>"
                     alt="<?php echo $news['title'] ?>" title="<?php echo $news['title'] ?>">
                <? else: ?>
                <img src="<?= SITE . '/mg-admin/design/images/no-img.png'; ?>" alt=""/>
            <? endif; ?>
        </div>
        <div class="content">
            <a href="<?php echo SITE ?><?php echo $news['path']; ?>"
               class="title-news"><?php echo $news['title']; ?></a>
            <?php echo mb_substr(strip_tags(PM::stripShortcodes($news['description'])), 0, 300, 'utf-8') . "..."; ?>
            <a href="<?php echo SITE ?><?php echo $news['path']; ?>" class="link-news">Подробней &#8594;</a>
        </div>
    </div>
<?php endforeach; ?>
<?php echo $data['pagination']; ?>
