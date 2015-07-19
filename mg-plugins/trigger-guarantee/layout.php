<?php echo ('<link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/font-awesome.min.css"  type="text/css">')?>
<div class="mg-trigger-guarantee <?php echo $options['layout'] ? $options['layout'] : 'vertleft' ?>" <?php echo $styleTrigg?>>
    <?php echo $trigger['title'] ? "<h2>".$trigger['title']."</h2>" : "" ?>
    <?php foreach ($trigger['elements'] as $elem): ?>
      <div class="mg-trigger" style="background-color: #<?php echo $options['background'] ?>; height:<?php echo $options['height'] ?>px; <?php echo $widthTrig?>">
          <span class="mg-trigger-icon" style="<?php echo $float ?>">
              <?php $elem['icon'] = str_replace('>', $style.'>', $elem['icon']) ?>
              <?php echo $elem['icon'] ?>
          </span>
          <span class="mg-trigger-text" >
              <?php echo $elem['text'] ?>
          </span>
      </div>
    <?php endforeach; ?>
</div>
