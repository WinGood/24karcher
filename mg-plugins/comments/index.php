<?php

/*
  Plugin Name: Отзывы покупателей
  Description: Плагин позволяет оставлять отзывы о товарах и статьях сайта. Имеет панель адмнистрирования. Добавить форму отзывов можно вставив шорткод [comments] в любое место страницы.
  Author: HollowJ и Avdeev Mark
  Version: 1.0
 */
$coments = new CommentsToMoguta;
MG::addInformer(array('count'=>$coments->getNewCommentsCount(),'class'=>'comment-wrap','classIcon'=>'comment-small-icon', 'isPlugin'=>true, 'section'=>'comments', 'priority'=>80));

class CommentsToMoguta {

  public function __construct() {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'createDataComments'));
    mgAddAction(__FILE__, array(__CLASS__, 'pagePluginComments'));
    mgAddShortcode('comments', array(__CLASS__, 'showComments'));
    mgAddShortcode('wall-comments', array(__CLASS__, 'wallComments'));
    $meta =
      '<script src="'.SITE.'/mg-plugins/comments/js/comments.js"></script>'.
      '<link href="'.SITE.'/mg-plugins/comments/css/style.css" rel="stylesheet" type="text/css">';
    mgAddMeta($meta);
  }

// При активации создает таблицу в БД и регистрирует новую опцию
  static function createDataComments() {
    $sql = "
  		 CREATE TABLE IF NOT EXISTS `".PREFIX."comments` (
  			`id` INT AUTO_INCREMENT NOT NULL,
        `name` VARCHAR(45) NOT NULL,
        `email` VARCHAR(45) NOT NULL,
        `comment` TEXT NoT NULL,
        `date` TIMESTAMP NOT NULL,
        `uri` VARCHAR(255) NOT NULL,
        `approved` TINYINT NOT NULL DEFAULT 0, 
        PRIMARY KEY(`id`)
  			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


    DB::query($sql);
    MG::setOption('countPrintRowsComments', 5);
  }

  static function getCaptcha()
  {
    include 'Captcha.php';
    $capthaClass = new Captcha();
    return $capthaClass->get_captcha();
  }

  /**
   * Создает каркас для вывода плагина. Обрабатывается шорткодом
   * @return type 
   */
  static function showComments() {
    $captha = self::getCaptcha();
    $html = '
    <div class="add-cmt-form hide white-box">
    <div class="comments-msg"></div>
      <div class="form-el">
        <label for="txtCmt">Комментарий</label>
        <textarea name="comment" id="txtCmt"></textarea>
      </div>
    ';

    if(!User::getThis())
    {
      $html .= '
      <div class="form-el">
        <label for="txtName">Имя / Псевдоним</label>
        <input type="text" name="name" id="txtName">
      </div>
      <div class="form-el">
        <label for="txtEmail">Email</label>
        <input type="text" name="email" id="txtEmail">
      </div>
      ';
    }
    else
    {
      $user = User::getThis();
      $html .= '
      <div class="form-el">
        <label for="txtName">Имя / Псевдоним</label>';
      $html .= "<input type='text' name='name' id='txtName' value='".$user->name."' disabled='disabled'>
      </div>";
    }

    $html .= '
    <div class="form-el">
      <label for="intCode">Код на картинке</label>';
    $html .= "<img src='".SITE.'/'.$captha."'>";
    $html .=  '<input type="text" name="intCode" id="intCode">
    </div>
    ';

    $html .= '
    <div class="clearfix buttons-form-cmt">
      <div class="left add">
        <button class="sendComment">Отправить</button>
      </div>
      <div class="left back">
        <button id="jq-hide-form-static-page">Отмена</button>
      </div>
    </div>
    </div><!-- !div.add-cmt-form -->
    ';

    $comments = self::getComments();

    if (!empty($comments['comments']))
    {
      foreach ($comments['comments'] as $item)
      {
        $timestamp = strtotime($item['date']);
        $date      = date('Y-m-d', $timestamp);
        $html .= "
        <div class='comment comment-white bor-b'>
          <p class='usr-name'>".$item['name']."</p>
          <p class='usr-txt'>".$item['comment']."</p>
          <p class='cmt-data text-right'>".$date."</p>
        </div><!-- !div.comment -->";
        }
      $html .= '<div class="clearfix">'.$comments['pagination'].'</div>'; 
    }
    else
    {
      $html .= "<div class='comment-post comment comment-white bor-b'>Еще никто не оставил комментарий. Вы можете быть первым!</div>";
    }

    return $html;
  }

  /**
   * Получаем все записи комментариев к этой странице
   * @return type 
   */
  static function getComments() {
    $result = array();

    // Запрос для генерации блока пагинации 
    $sql = "
      SELECT id, name, comment, date
      FROM `".PREFIX."comments` 
      WHERE uri = ".DB::quote(URL::getClearUri())." AND approved = '1'
      ORDER BY `date` DESC";

    //Получаем блок пагинации
    if ($_GET["comm_page"]) {
      $page = $_GET["comm_page"]; //если был произведен запрос другой страницы, то присваиваем переменной новый индекс
    }

    $navigator = new Navigator($sql, $page, MG::getSetting('countPrintRowsComments'), 4, false, "comm_page"); //определяем класс
    $comments = $navigator->getRowsSql();
    $pagination = $navigator->getPager();

    /*
     * Получаем  комментарии.	
     */
    foreach ($comments as $key => $value) {
      $comments[$key]['date'] = date('d.m.Y H:i', strtotime($comments[$key]['date']));
    }

    $result['comments'] = $comments;
    $result['pagination'] = $pagination;

    return $result;
  }

  /**
   * Получаем количество новых комментариев 
   */
  static function getNewCommentsCount() {   
    $exist=false;
    $result = DB::query('SHOW TABLES');
      while($row = DB::fetchArray($result)){
        if( PREFIX."comments"==$row[0]){
          $exist=true;
        };
      }
      

    if ($exist){
      $sql = "
        SELECT `id`
        FROM `".PREFIX."comments`
        WHERE `approved`=0";

      $res = DB::query($sql);
      $count = DB::numRows($res); 

    }
    return $count?$count:0;
  }
  
  /**
   * Вывод страницы плагина в админке
   */
  static function pagePluginComments() {
    $lang = PM::plugLocales('comments');
    if ($_POST["page"])
      $page = $_POST["page"]; //если был произведен запрос другой страницы, то присваиваем переменной новый индекс

    $countPrintRowsComments = MG::getSetting('countPrintRowsComments');

    $navigator = new Navigator("SELECT  *  FROM `".PREFIX."comments` ORDER BY `id` DESC", $page, $countPrintRowsComments); //определяем класс
    $comments = $navigator->getRowsSql();
    $pagination = $navigator->getPager('forAjax');

    // подключаем view для страницы плагина
    include 'pagePlugin.php';
  }
  
   /**
   * Вывод всех комментариев
   */
  static function wallComments() {
    $comments = self::getComments();
    $html .= '<div class="reviews-big">';
    if (!empty($comments['comments'])) {
      foreach ($comments['comments'] as $item) {
        $html .= '
         <div class="reviews-info">
          <span class="user-name">'.$item['name'].'</span>
          <span class="add-date">'.$item['date'].'</span>
         </div>   
          <p>'.$item['comment'].'</p>
        ';
      }
      $html .= '</div>'.$comments['pagination'];
    }
   return $html;
  }

}