<?php

/**
 * Класс Pactioner наследник стандарного Actioner
 * Предназначен для выполнения действий,  AJAX запросов плагина 
 *
 * @author Avdeev Mark <mark-avdeev@mail.ru>
 */
class Pactioner extends Actioner {

  private $pluginName = 'partners-program';       

  /**
   * Сохраняет  опции плагина
   * @return boolean
   */
  public function saveBaseOption() {
    $this->messageSucces = 'Настройки применены';
    $this->messageError = 'Настройки не применены';
    if (!empty($_POST['data'])) {
      MG::setOption(array('option' => 'partners-program', 'value' => addslashes(serialize($_POST['data']))));
    }   
    return true;
  }
}