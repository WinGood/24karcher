<?php

/*
  Plugin Name: Партнерская программа
  Description: Устанавливает связь между оплаченными заказами и пользователем, благодаря которому был оплачен заказ. Добавляет страницу /affiliate, на которой необходимо разместить информацию о вашей партнерской программе. Шорт код [data-balance] необходимо разместить на странице личного кабинета в файле 'ваша тема'/views/personal.php , для отображения баланса партнеров.
  Author: Avdeev Mark
  Version: 1.1
 */

if (URL::isSection('personal') || URL::isSection('affiliate')) {
  mgAddMeta("<link rel='stylesheet' href='".SITE."/mg-plugins/partners-program/css/style.css' type='text/css' />");
}

new PartnerProgram;

class PartnerProgram {

  static public $percent = 20; //процент для партнеров
  static public $exitMoneyLimit = 50; //минимальная сумма для вывода
  
  public function __construct() {
    
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'createDateBase'));
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin'));
    mgAddShortcode('data-balance', array(__CLASS__, 'getBalance'));
    mgAddShortcode('affiliate', array(__CLASS__, 'affiliatePanel'));

    // установка куки если есть гет параметр
    if (isset($_GET['partnerId']) && is_numeric($_GET['partnerId'])) {
      self::setPartnerCookie($_GET['partnerId']);
    }

    // при каждом оформлении заказа создавать запись в партнерской таблице
    mgAddAction('models_order_addorder', array(__CLASS__, 'partnerToOrder'), 1);
    
    // ждем когда придет оплата
    mgAddAction('controllers_payment_actionwhenpayment', array(__CLASS__, 'eventPayment'), 1);   
    
    $option = MG::getSetting('partners-program');
    $option = stripslashes($option);
    $options = unserialize($option);   
    self::$percent = $options['percent'];
    self::$exitMoneyLimit = $options['exitMoneyLimit'];
  }
  
  //Пришла оплата заказа по электронным деньгам
  static function eventPayment($arg) { 
    self::updateOrder($arg);
  }

  /**
   * Создает таблицу для функционирования плагина партнерки
   */
  static function createDateBase() {
    DB::query("CREATE TABLE IF NOT EXISTS `".PREFIX."partner` (
      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Номер партнера',
      `user_id` int(11) NOT NULL COMMENT 'Партнер',
      `percent` float NOT NULL COMMENT 'Процент', 
      `payments_amount` float NOT NULL COMMENT 'Всего было выплачено', 
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

    DB::query("
      CREATE TABLE IF NOT EXISTS `".PREFIX."partner_order` (
      `partner_id` int(11) NOT NULL,
      `order_id` int(11) UNIQUE NOT NULL,
      `percent` double NOT NULL,
      `summ` double NOT NULL
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Связь партнеров с оплаченными заказами';
     "
    );

    DB::query("
      CREATE TABLE IF NOT EXISTS `".PREFIX."partner_payments_amount` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `partner_id` int(11) NOT NULL,
        `date` datetime NOT NULL,
        `summ` double NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
     "
    );

    //добавляем статическу страницу /affiliate, чтобы ее было удобно редактировать.
    DB::query("INSERT IGNORE INTO `".PREFIX."page` ( `title`, `url`, `html_content`, `meta_title`, `meta_keywords`, `meta_desc`) VALUES
                ( 'Партнерская программа', 'affiliate.html', '[affiliate]', 'Партнерская программа', 'Партнерская программа', 'Партнерская программа на ".MG::getSetting('sitename').", зарабатывайте с нами!');");
   
    $array = Array(
      'percent' => 20,
      'exitMoneyLimit' => 1000,      
    ); 
    
    MG::setOption(array('option' => 'partners-program', 'value' => addslashes(serialize($array))));   
    
  }

  /**
   * Выводит страницу настроек плагина в админке
   */
  static function pageSettingsPlugin() {  
    
    if ($_POST["page"]){
      $page = $_POST["page"]; //если был произведен запрос другой страницы, то присваиваем переменной новый индекс
    }
    
    $navigator = new Navigator("
      SELECT p. * , u.email, COUNT( p.id ) as count_orders
      FROM  `".PREFIX."partner` AS p
      LEFT JOIN  `".PREFIX."user` AS u ON u.id = p.user_id
      LEFT JOIN  `".PREFIX."partner_order` AS po ON po.partner_id = p.id
      GROUP BY p.id
      ORDER BY p.payments_amount DESC  ", $page, 20); //определяем класс
    $partners = $navigator->getRowsSql();
    $pagination = $navigator->getPager('forAjax');

    $option = MG::getSetting('partners-program');
    $option = stripslashes($option);
    $options = unserialize($option); 
    // подключаем view для страницы плагина
    include 'pagePlugin.php';
  }

  /**
   * Проверяем, нужно ли отчислить коммисионные по пришедшей оплате для заказа с сервисов оплаты
   * @param $arg - параметры переданые из payment.php
   */
  static function updateOrder($arg) {
    $orderId = $arg["args"]["paymentOrderId"];
    $model = new Models_Order;
    $order = $model->getOrder(PREFIX.'order.id='.$orderId);
    
    // если статус заказа становится "Оплачен или выполнен", то отправляем письмо админу, о том что заказ оформлен благодаря партнеру.
    // в базе сохраняется привязка. Если в последствии изменить статус, то привязка останется!
    if ($order[$orderId]['status_id']== 2) {
      $partner = self::closeOrderPartner($orderId);

      if (empty($partner)) {
        return true;
      }

      //Отправляем админам
      $sitename = MG::getSetting('sitename');
      $message = 'Заказ #'.$orderId.' был оплачен после перехода по реферальной ссылке <b>партнера #'.$partner['id'].'</b>.
          На счет пользователя <b>'.$partner['email'].'</b> зачислены коммисионные '.$partner['summ'].' '.MG::getSetting('currency');
     
      $mails = explode(',', MG::getSetting('adminEmail'));

      foreach ($mails as $mail) {
        if (preg_match('/^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/', $mail)) {
          Mailer::addHeaders(array("Reply-to" => MG::getSetting('noReplyEmail')));
          Mailer::sendMimeMail(array(
            'nameFrom' => $sitename,
            'emailFrom' => MG::getSetting('noReplyEmail'),
            'nameTo' => $sitename,
            'emailTo' => $mail,
            'subject' => $partner['percent'].'% от заказа #'.$partner['id'].' зачислено на счет партнеру '.$partner['email'],
            'body' => $message,
            'html' => true
          ));
        }
      }      
     
    }
    
    return true;
  }

  /**
   * При добавлении нового заказа проверям, нет ли партнерской куки.
   */
  static function partnerToOrder($arg) {

    $partnerId = self::getPartnerCookie();
    $partner = self::getPartner(self::getPartnerCookie($partnerId));
    $orderId = $arg['result'];

    if (!empty($partner) && $orderId) {
      $model = new Models_Order;
      $order = $model->getOrder(PREFIX.'order.id='.$orderId);
      $summ = $partner['percent'] * $order[$orderId]['summ'] / 100;
      self::addConnetcToPartner($partnerId, $orderId, $partner['percent'], $summ);
    }

    return $arg['result'];
  }

  /**
   * Добавляет в таблицу partner_orders привязку.
   */
  static function addConnetcToPartner($partnerId, $orderId, $percent, $summ) {
    DB::query("
      INSERT IGNORE INTO  `".PREFIX."partner_order` (`partner_id`, `order_id`, `percent`, `summ`)
      VALUES (".DB::quote($partnerId).", ".DB::quote($orderId).", ".DB::quote($percent).", ".DB::quote($summ).")"
    );
  }

  /**
   * Обработчик шотркода вида [data-balance].
   * Выводит информацию о  заработанных средствах партнера, и о том чколько было выплаченно в общей сумме
   */
  static function getBalance() {
    $id = USER::getThis()->id;

    $data = array('noparnter' => true);
    $result = DB::query('
      SELECT *
      FROM `'.PREFIX.'partner`
      WHERE `user_id` = '.DB::quote($id)
    );
    if ($row = DB::fetchAssoc($result)) {
      $data = $row;

      $result = DB::query('
        SELECT sum(summ) as balance
        FROM `'.PREFIX.'partner_order`
        WHERE `partner_id` = '.DB::quote($row['id'])
      );

      if ($row2 = DB::fetchAssoc($result)) {
        $data['balance'] = $row2['balance'] ? $row2['balance'] : 0;
        ;
      }

      $result = DB::query('
        SELECT sum(summ) as amount
        FROM `'.PREFIX.'partner_payments_amount`
        WHERE `partner_id` = '.DB::quote($row['id'])
      );

      if ($row3 = DB::fetchAssoc($result)) {
        $data['amount'] = $row3['amount'] ? $row3['amount'] : 0;
      }

      $data['exitbalance'] = $data['balance'] - $data['amount'];
      $data['exitbalance'] = $data['exitbalance'] ? $data['exitbalance'] : 0;
    }



    $html .= '<script>
      function orderPartner(summ){
      if(summ>'.self::$exitMoneyLimit.'){
        $(".blockOrder").show();
        $(".showFormOrderParnet").hide();
      } 
      else{
        $(".showFormOrderParnet").replaceWith("<p class=\"notify-error\">Невозможно отправить заявку пока у вас меньше '.self::$exitMoneyLimit.' рублей для вывода средств!</p>");       
      }
      }
      function sendOrderP(){
        var summ = parseFloat($("input[name=paymentPartner]").val());
        $.ajax({
        type: "POST",
        url: "ajax",
        data: {
            pluginHandler: "partners-program", 
            actionerClass: "Partner", 
            action: "sendOrderToPayment", 
            summ:summ          
        },
        dataType: "json",
        cache: false,
        success: function(response){ 
          $(".blockOrder").replaceWith("<span style=\"color:green\">Заявка на выплату <b>"+summ+" рублей</b> принята, наши менеджеры свяжутся с Вами, чтобы уточнить удобный способ перечисления денег!</span>");   
        }
        });
      }
      </script>
    <div class="partnerProgram">
    <div class="becomePartner">'.self::affiliatePanel()."</div>";

    if (!$data['noparnter']) {
      $html .= '
      
      <div class="blockBalance">
      <ul>
        <li><span class="bold-text">Баланс:</span> '.$data['balance'].' руб.</li>
        <li><span class="bold-text">Можно вывести:</span> '.$data['exitbalance'].' руб.</li>
        <li><span class="bold-text">Выплачено:</span> '.$data['amount'].' руб.</li>
      </ul>
      <a href="#" class="showFormOrderParnet" onclick="orderPartner('.$data['exitbalance'].'); return false;">Отправить заявку на вывод средств</a>
        <div class="blockOrder" style="display:none">
         Введите сумму для вывода: <input type="text" name="paymentPartner"  value="'.$data['exitbalance'].'"/> руб.
         <button onclick="sendOrderP();">Отправить заявку</button>
        </div>
      </div>';
    }
    $html .= '</div>';
    return $html;
  }

  /**
   * Устанавливаем  значение партнерской куки на год
   */
  static function setPartnerCookie($id) {
    SetCookie('parnerId', $id, time() + 3600 * 24 * 365);
  }

  /**
   * Получаем значение партнерской куки
   */
  static function getPartnerCookie() {
    return isset($_COOKIE['parnerId']) ? $_COOKIE['parnerId'] : false;
  }

  /**
   * Получаем параметры партнера
   */
  static function getPartner($id) {
    $result = array();

    $res = DB::query("
        SELECT *
        FROM `".PREFIX."partner`
        WHERE id = ".DB::quote($id)
    );

    if ($row = DB::fetchAssoc($res)) {
      $result = $row;
    }

    return $result;
  }

  /**
   * Получаем  параметры партнера при  закрытии заказа оформленного  по его ссылке.
   * id - заказа 
   */
  static function closeOrderPartner($id) {
    $result = array();

    $res = DB::query("
        SELECT u.email, p.*, po.*
        FROM `".PREFIX."partner_order` as po
        LEFT JOIN `".PREFIX."partner` as p ON po.partner_id = p.id
        LEFT JOIN `".PREFIX."user` as u ON u.id = p.user_id
        WHERE po.order_id = ".DB::quote($id)       
    );

    if ($row = DB::fetchAssoc($res)) {
      $result = $row;
    }

    return $result;
  }

  //выводит партнерскую ссылку если ссылки нет, то предлагает стать партнером
  static function affiliatePanel() {
    $id = USER::getThis()->id;

    if (!$id) {
      return 'Пожалуйста, <a href="'.SITE.'/registration">зарегистрируйтесь</a>, чтобы принять участие в партнерской программе '.MG::get('sitename').' и получать '.self::$percent.'% от стоимости заказов ваших друзей и знакомых.';
    }

    $parnterLink = false;
    $result = DB::query('
      SELECT *
      FROM `'.PREFIX.'partner`
      WHERE `user_id` = '.DB::quote($id)
    );

    if ($row = DB::fetchAssoc($result)) {
      $parnterLink = SITE."?partnerId=".$row['id'];
    }
    if ($parnterLink) {
      return '<div class="accostPartner"><p>Уважаемый, партнер! Ваша реферальная ссылка: <span>'.$parnterLink.'</span></p><p>Передайте ее друзьям и знакомым и вы получите '.self::$percent.'% от стоимости их заказа.</p></div>';
    } else {

      $html = '  
      <script>
      function newPartner(){   
      $.ajax({
			type: "POST",
			url: "ajax",
			data: {
			    pluginHandler: "partners-program", 
          actionerClass: "Partner", 
          action: "becomePartner"              
			},
			dataType: "json",
			cache: false,
			success: function(response){      
        location="'.SITE.'/affiliate" ;
    	}
		  });
      }
      </script>
      Здравствуйте, '.USER::getThis()->name.' '.USER::getThis()->sname.'
        мы предлагаем Вам стать нашим партнером и получать '.self::$percent.'% от всех заказов  привлеченных вами клентов.
        <a href="#" class="becomePartner" onclick="newPartner(); return false;">Получить реферальную сылку</a>    
        ';
      return $html;
    }
  }

}