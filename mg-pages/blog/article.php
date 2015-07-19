<?mgSEO($data);?>
<?$site = SITE;?>
<?$breadcrumbs = <<<EOF
<div class="site-breadcrumbs">
    <ul class="site-breadcrumbs-list">
        <li class="site-breadcrumbs-list-item"><a href="$site">Главная</a></li>
        <li class="site-breadcrumbs-list-item separator">/</li>
        <li class="site-breadcrumbs-list-item"><a href="$site/blog">Новости</a></li>
        <li class="site-breadcrumbs-list-item separator">/</li>
        <li class="site-breadcrumbs-list-item current">$data[title]</li>
    </ul>
</div>
EOF;
?>
<?MG::set('breadcrumbs', $breadcrumbs);?>
<?MG::set('pageTitle', $data['title']);?>
<?MG::set('isNotWrapper', true);?>
<div class="page-content"><?php echo $data['previewText'] . $data['detailText']; ?></div>
[comments]