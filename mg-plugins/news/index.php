<?php
/*
  Plugin Name: Новостная лента
  Description: Позволяет вести новостную ленту добавляя и редактируя тексты новостей. После подключения плагина становится доступной страница [sitename]/news.html , на которой отображается список анонсов всех новостей. Чтобы вывести анонсы новостей в любом месте сайта нужно указать шорткод [news-anons count="3"], где count - число анонсов. А также появляется возможность подписаться на RSS рассылку по адресу [sitename]/news/feed
  Author: Avdeev Mark, update to 3.7.1 Румянцев Олег
  Version: 3.0
 */

/**
 * При активации плагина, создает таблицу для новостей
 * также создает файл news.php , который будет генерироватьодноименную страницу сайта
 * [sitename]/news.html, при необходимости его можно изменять.
 * На данной странице будут выведены анонсы новостей.
 */

new PluginNews();

class PluginNews {

  public function __construct()
  {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'createDateBaseNews'));
    mgAddAction(__FILE__, array(__CLASS__, 'pagePluginNews'));
    mgAddAction('mg_gethtmlcontent', array(__CLASS__, 'printNews'), 1);
    mgAddShortcode('news-anons', array(__CLASS__, 'anonsNews'));
  }

  public static function createDateBaseNews() {
    DB::query("
     CREATE TABLE IF NOT EXISTS  `mpl_news` (
     `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
     `title` VARCHAR( 255 ) NOT NULL ,
     `description` TEXT NOT NULL ,
     `add_date` DATETIME NOT NULL ,
     `url` VARCHAR( 255 ) NOT NULL ,
     `image_url` VARCHAR( 255 ) NOT NULL ,
     `meta_title` varchar(255) NOT NULL,
     `meta_keywords` varchar(512) NOT NULL,
     `meta_desc` text NOT NULL,
     PRIMARY KEY ( `id` )
     ) ENGINE = MYISAM DEFAULT CHARSET=utf8;
   ");

    MG::setOption('countPrintRowsNews', 5);

    $realDocumentRoot = str_replace(DIRECTORY_SEPARATOR.'mg-plugins'.DIRECTORY_SEPARATOR.'news', '', dirname(__FILE__));
    $path = $realDocumentRoot.'/uploads/news/';
    if (!file_exists($path)) {
      chdir($realDocumentRoot."/uploads/");
      mkdir("news", 0777);
      chdir($realDocumentRoot."/uploads/news/");
      mkdir("thumbs", 0777);
    }
  }

  //Выводит полную новость на странице news/[название_новости] и news
  public static function printNews($arg)
  {

    $aUri   = URL::getSections();
    $result = $arg['result'];
    if($aUri[1] == 'news' && empty($aUri[2]))
    {
      MG::titlePage('Новости');
      $listNews = self::getListNews(MG::getOption('countPrintRowsNews'));
      $result = '
      <div class="page-content">
        <ul class="breadcrumbs bor-b">
          <li><a href="'.SITE.'" class="home">Главная</a></li>
          <span> / </span>
          <li>Новости</li>
        </ul><!-- !ul.breadcrumbs -->
        <h1 class="title-page">Новости</h1>';
      if(!empty($listNews['listNews']))
      {
        foreach($listNews['listNews'] as $news)
        {
          $result .= '
          <div class="news-blocks-main">
            <div class="title-news-main">
              <a href="news/'.$news['url'].'">'.ucfirst($news['title']).'</a>
            </div>
            <div class="white-box bor-b clearfix">
              <img src="'.SITE.'/uploads/news/'.$news['image_url'].'" alt="'.$news['title'].'" title="'.$news['title'].'">
              '.mb_substr(strip_tags(PM::stripShortcodes($news['description'])), 0, 140, 'utf-8').'
            </div><!-- !div.white-box -->
          </div><!-- !div.news-blocks-main -->
          ';
        }
        $result .= "$listNews[pagination]";
      }
      else
      {
        $result .= '<div class="white-box bor-b">Новости не найдены</div><!-- !div.page-content-box -->';
      }
      $result .= '</div><!-- !div.page-content -->';
    }
    else
    {
      if (URL::isSection('news'))
      {
        $news = self::getNewsByUrl(URL::getLastSection());

        if (empty($news)) 
        {
          MG::redirect('/404.html');
        }

        MG::titlePage($news['title']);
        MG::seoMeta($news);

        $body = MG::inlineEditor('mpl_news', 'description', $news['id'], $news['description']);

        $result = '
        <div class="page-content">
          <ul class="breadcrumbs bor-b">
            <li><a href="'.SITE.'" class="home">Главная</a></li>
            <span> / </span>
            <li><a href="'.SITE.'/news'.'">Новости</a></li>
            <span> / </span>
            <li>'.$news['title'].'</li>
          </ul><!-- !ul.breadcrumbs -->
          <h1 class="title-page">'.$news['title'].'</h1>
          <div class="white-box bor-b">
            '.$body.'
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
        </div><!-- !div.page-content -->';
      }
    }
    return $result;
  }

  //выводит страницу плагина в админке
  public static function pagePluginNews()
  {
    $lang = PM::plugLocales('news');
    if ($_POST["page"])
      $page = $_POST["page"]; //если был произведен запрос другой страницы, то присваиваем переменной новый индекс

    $countPrintRowsNews = MG::getOption('countPrintRowsNews');

    $navigator  = new Navigator("SELECT  *  FROM `mpl_news` ORDER BY `add_date` DESC", $page, $countPrintRowsNews); //определяем класс
    $news       = $navigator->getRowsSql();
    $pagination = $navigator->getPager('forAjax');

    // подключаем view для страницы плагина
    include 'pagePlugin.php';
  }

   /**
   * Печатает на экран анонс заданной новости
   * @param type $news - массив с данными о новости (полностью запись из БД)
   */

   public static function anonsNews($args)
   {
      $args['count'] = $args['count'] ? $args['count'] : 4;
      $data          = self::getListNews($args['count']);
      $listNews      = $data['listNews'];
      $html          = "";
      if (!empty($listNews))
      {
        $nIteration = 0;
        foreach ($listNews as $news)
        {
          if($nIteration == 0)
            $html .= '<div class="tab tab-first">';
          else if($nIteration % 2 == 0)
            $html .= '<div class="tab">';

          $html .= '
          <div class="news-main-page clearfix">
            <div class="news-img"><img src='.SITE.'/uploads/news/'.$news['image_url'].' alt='.$news['title'].' /></div>
            <div class="desc">
              <p class="title"><a href="news/'.$news['url'].'">'.ucfirst($news['title']).'</a></p>
              <p class="date"><i class="icon-time"></i> '.strftime('%e %B, %Y', strtotime($news['add_date'])).'</p>
              <p class="text-desc">'.ucfirst(mb_substr(strip_tags(PM::stripShortcodes($news['description'])), 0, 140, 'utf-8')).'...</p>
            </div><!-- !div.desc -->
          </div><!-- !div.news-main-page -->';

          $nIteration++;
          if($nIteration % 2 == 0)
            $html .= '</div><!-- !div.tab -->';
        }
    }
    else
    {
      $html .= '<div class="white-box bor-b">Новости не найдены</div><!-- !div.page-content-box -->';
    }
		return $html;
  }


  //Возвращает список новостей
  public static function getListNews($count = 100)
  {
    //Получаем список новостей
    if ($_GET["page"])
      $page = $_GET["page"]; //если был произведен запрос другой страницы, то присваиваем переменной новый индекс

    $navigator  = new Navigator("SELECT  *  FROM `mpl_news` ORDER BY `add_date` DESC", $page, $count); //определяем класс
    $news       = $navigator->getRowsSql();
    $pagination = $navigator->getPager();

    return array('listNews' => $news, 'pagination' => $pagination);
  }

  // Возвращает данные о запрошенной новости.
  public static function getNewsByUrl($url)
  {
    $result = array();
    $res = DB::query('
    SELECT  *
    FROM `mpl_news`
    WHERE url = '.DB::quote($url)
    );
    if ($result = DB::fetchAssoc($res)) {
      return $result;
    }
    return $result;
  }

}
