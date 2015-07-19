<?php if(!empty($data['categories'])):?>
    <ul>
        <?php foreach($data['categories'] as $category):?>
            <li><a href="<?php echo SITE."/blog/".$category['url']?>" <?php echo($category['url']==$data['selected_category'])?'class="selected"':''?>>
              <?php echo $category['title']; echo ($data['showCnt'])?'('.$category['cnt'].')':''?>
            </a></li>
        <?php endforeach;?>
    </ul>
<?php endif;?>