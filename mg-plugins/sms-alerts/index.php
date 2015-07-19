<?php

/*
  Plugin Name: SMS оповещения
  Description: Позволяет отправлять бесплатные СМС оповещения администратору сайта о новых заказах, а также любых других событиях. Возможна отправка SMS покупателям (платно). Шорт код [sms]Текст ообщения[/sms]
  Author: Антон Кокарев, Румянцев Олег
  Version:  Версия 1.0
 */

new SMSAlerts;

class SMSAlerts {

  public function __construct() {
    mgActivateThisPlugin(__FILE__, array(__CLASS__, 'createDateBase'));
    mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin'));

    if (!URL::isSection('mg-admin')) {
      mgAddShortcode('sms', array(__CLASS__, 'sendsms'));
    }
  }

  /**
   * Создает таблицу настроек в БД при активации плагина
   */
  static function createDateBase() {
    DB::query("
      CREATE TABLE IF NOT EXISTS `".PREFIX."sms_setting` (
       `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Порядковый номер настройки',
       `option` varchar(255) NOT NULL COMMENT 'Имя опции',
       `value` longtext NOT NULL COMMENT 'Значение опции',
       `name` varchar(255) NOT NULL COMMENT 'Название опции',
       PRIMARY KEY (`id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='Настройки плагина SMS' AUTO_INCREMENT=1 ");

    DB::query("INSERT IGNORE INTO `".PREFIX."sms_setting` (`id`, `option`, `value`, `name`) VALUES
      (1, 'nomer', '79991234567', 'NOMER'),
      (2, 'token', 'api_id', 'api_id') ");
  }

  /**
   * Получает текущий номер телефона
   */
  static function getNomer() {
    $nomer = "";
    $res = DB::query(" SELECT `value` FROM `".PREFIX."sms_setting` WHERE `id`= 1");
    if ($row = DB::fetchAssoc($res)) {
      $nomer = $row['value'];
    }
    return $nomer;
  }

  /**
   * Получает текущий токен
   */
  static function getToken() {
    $token = "";
    $res = DB::query(" SELECT `value` FROM `".PREFIX."sms_setting` WHERE `id`= 2");
    if ($row = DB::fetchAssoc($res)) {
      $token = $row['value'];
    }
    return $token;
  }

  /**
   * Страница настроек плагина
   */
  static function pageSettingsPlugin() {
    echo '<link rel="stylesheet" href="../mg-plugins/sms-alerts/css/pageSettings.css" type="text/css" />';

    if (isset($_POST['nomer']) && isset($_POST['token'])) {
      $nomer = $_POST['nomer'];
      $token = $_POST['token'];
      DB::query("
        UPDATE `".PREFIX."sms_setting`
        SET `value` = ".DB::quote($nomer)."
        WHERE id=1
      ");
      DB::query("
        UPDATE `".PREFIX."sms_setting`
        SET `value` = ".DB::quote($token)."
        WHERE id=2
      ");
      echo "<br/><div class=\"sms-setting-update\">Установлен номер для SMS информирования: ".$nomer.". Токен: ".$token."</div>";
    } else {
      $nomer = self::getNomer();
      $token = self::getToken();
    };

    include('page-settings.php');
  }

  /**
   * Отправляет СМС через сервис sms.ru
   */
  static function sendsms_smsru($nomer, $msg, $token) {
    $body = file_get_contents("http://sms.ru/sms/send?api_id=$token&to=$nomer&text=".urlencode($msg));
    return $body;
  }

  /**
   * Обработчик шотркода вида [sms nomer="79123456789" token="abcde123-qwerty-9876"]Текст сообщения[/sms].
   * Отправляет СМС на указанный номер. Если номер не указан, использует номер по-умолчанию.
   * Если токен не указан, использует токен по-умолчанию.
   * Если не указан текст СМС, то сообщение отправлено не будет
   */
  static function sendsms($arg, $content = null) {
    !empty($content) ? $msg = $content : $msg = $arg['content'];
    isset($arg['nomer']) ? $nomer = $arg['nomer'] : $nomer = self::getNomer();
    isset($arg['token']) ? $token = $arg['token'] : $token = self::getToken();

    $res = self::sendsms_smsru($nomer, $msg, $token);

    return "";
  }

}

?>