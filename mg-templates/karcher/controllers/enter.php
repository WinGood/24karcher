<?php
/**
 * Контроллер: Enter
 * 
 * Класс Controllers_Enter обрабатывает действия пользователей на странице авторизации.
 * - Аутентифицирует пользовательские данные;
 * - Проверяет корректность ввода данных с формы авторизации;
 * - При успешной авторизации перенаправляет пользователя в личный кабинет.
 *
 * @author Авдеев Марк <mark-avdeev@mail.ru>
 * @package moguta.cms
 * @subpackage Controller
 */
class Controllers_Enter extends BaseController{

  private $error;
  private $userData;
  private $fPass;

  function __construct(){

    $this->fPass = new Models_Forgotpass;

    if(URL::getQueryParametr('logout')){
      User::logout();
    }

    if(User::isAuth()){
      MG::redirect('/personal');
    }

    if (isset($_POST['registration']))
    {
      // Если данные введены верно.
      if (!$this->unValidForm()) {
        USER::add($this->userData);
        $message = '<span class="succes-reg">Вы успешно зарегистрировались! Для активации пользователя Вам необходимо перейти по ссылке высланной на Ваш электронный адрес <strong>'.$this->userData['email'].'</strong></span>';
        $form = false;
        
        // Рассылаем письма со ссылкой для подтверждения регистрации.
        $this->_sendActivationMail($this->userData['email']);
        unset($_POST);
      } else {
        $error = $this->error;
        $form = true;
      }
    }
    else
    {
      // Если пользователь не авторизован.
      if(!User::isAuth() && (isset($_POST['email'])||isset($_POST['pass']))){     
        if(!User::auth(URL::get('email'), URL::get('pass'))){
          $error = '<span class="msgError">'.'Неправильная пара email-пароль! Авторизоваться не удалось.'.'</span>';    
        }else{
          $this->successfulLogon();
        }     
      }
    }
    
    $data = array (
      'meta_title'    => 'Авторизация',
      'msgError'      => $error,
      'message'       => $message,
      'meta_keywords' => !empty($model->currentCategory['meta_keywords']) ? $model->currentCategory['meta_keywords'] : "Авторизация,вход, войти в личный кабинет",
      'meta_desc'     => !empty($model->currentCategory['meta_desc']) ? $model->currentCategory['meta_desc'] : "Авторизуйтесь на сайте и вы получите дополнительные возможности, недоступные для обычных пользователей.",
    );

    $this->data = $data;
  }


  /**
   * Перенаправляет пользователя на страницу в личном кабинете.
   * @return void
   */
  public function successfulLogon(){

    // Если указан параметр для редиректа после успешной авторизации.
    if($location = URL::getQueryParametr('location')){
      MG::redirect($location);
    }else{

      // Иначе  перенаправляем в личный кабинет.
      MG::redirect('/personal');
    }
  }

  /**
   * Метод проверяет корректность данных введенных в форму регистрации.
   * @return boolean
   */

  public function unValidForm() {

    if (!URL::getQueryParametr('name')) {
      $name = 'Пользователь';
    } else {
      $name = URL::getQueryParametr('name');
    }

    $this->userData = array(
      'pass' => URL::getQueryParametr('pass'),
      'email' => URL::getQueryParametr('email'),
      'role' => 2,
      'name' => $name,
      'sname' => URL::getQueryParametr('sname'),
      'address' => URL::getQueryParametr('address'),
      'phone' => URL::getQueryParametr('phone'),
    );

    $registration = new Models_Registration;

    if ($err = $registration->validDataForm($this->userData)) {
      $this->error = $err;
      return true;
    }

    return false;
  }

  /**
   * Метод отправки письма для активации пользователя.
   * @param type $userEmail
   * @return void 
   */

  private function _sendActivationMail($userEmail) {
    $userId = USER::getUserInfoByEmail($userEmail)->id;
    $hash = $this->fPass->getHash($userEmail);
    $this->fPass->sendHashToDB($userEmail, $hash);
    $siteName = MG::getOption('sitename');
    $message = '
      Здравствуйте!<br>
        Вы получили данное письмо так как зарегистрировались на сайте '.$siteName.' с логином '.$userEmail.'.<br>
        Для активации пользователя и возможности пользоваться личным кабинетом пройдите по ссылке: <a href="'.SITE.'/registration?sec='.$hash.'&id='.$userId.'" target="blank">'.SITE.'/registration?sec='.$hash.'&id='.$userId.'</a>.<br>
        Отвечать на данное сообщение не нужно.';
    $emailData = array(
      'nameFrom' => $siteName,
      'emailFrom' => MG::getSetting('noReplyEmail'),
      'nameTo' => 'Пользователю сайта '.$siteName,
      'emailTo' => $userEmail,
      'subject' => 'Активация пользователя на сайте '.$siteName,
      'body' => $message,
      'html' => true
    );

    $this->fPass->sendUrlToEmail($emailData);
  }


  /**
   * Проверяет корректность ввода данных с формы авторизации.
   * @return void
   */
  public function validForm(){
    $email = URL::getQueryParametr('email');
    $pass = URL::getQueryParametr('pass');

    if(!$email || !$pass){
      // При первом показе, не выводить ошибку.
      if(strpos($_SERVER['HTTP_REFERER'], '/enter')){
        $this->data = array (
          'msgError' => '<span class="msgError">'.'Одно из обязательных полей не заполнено!'.'</span>',
          'meta_title' => 'Авторизация',
          'meta_keywords' => !empty($model->currentCategory['meta_keywords']) ? $model->currentCategory['meta_keywords'] : "Авторизация,вход, войти в личный кабинет",
          'meta_desc' => !empty($model->currentCategory['meta_desc']) ? $model->currentCategory['meta_desc'] : "Авторизуйтесь на сайте и вы получите дополнительные возможности, недоступные для обычных пользователей.",
       );
      }
      return false;
    }
    return true;
  }

}