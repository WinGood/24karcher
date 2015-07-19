<?php

/**
 * Класс Partner наследник стандарного Actioner
 * Предназначен для выполнения действий, запрошеных  AJAX функциями
 *
 * @author Avdeev Mark <mark-avdeev@mail.ru>
 */
class Partner extends Actioner{

  //Сколько всего заработал партнер и сколько ему выплачено
  public function getPartnerBalanse(){
    $data = array('id'=>$_POST['id'],'balance'=>0,'amount'=>0,'exitbalance'=>0);
    $result = DB::query('
      SELECT sum(summ) as balance
      FROM `'.PREFIX.'partner_order`
      WHERE `partner_id` = '.DB::quote($_POST['id'])
    );

    if($row = DB::fetchAssoc($result)){
      $data['balance'] = $row['balance']?$row['balance']:0;
    }
    
    $result = DB::query('
      SELECT sum(summ) as amount
      FROM `'.PREFIX.'partner_payments_amount`
      WHERE `partner_id` = '.DB::quote($_POST['id'])
    );

    if($row = DB::fetchAssoc($result)){
      $data['amount'] = $row['amount']?$row['amount']:0;
    }
    
    $data['exitbalance'] = $data['balance']-$data['amount'];
    $this->data = $data;
    return true;
  }
 
  //Выплата с записью в историю
  public function paymentToPartner(){ 
    DB::query('INSERT INTO '.PREFIX.'partner_payments_amount (partner_id,date,summ) VALUES('.DB::quote($_POST['partner_id']).',now(),'.DB::quote($_POST['summ']).');');
    
    DB::query('UPDATE '.PREFIX.'partner
               SET payments_amount = payments_amount + %d
               WHERE id=%d',$_POST['summ'],$_POST['partner_id']); 
    
    $result = DB::query('
      SELECT payments_amount
      FROM '.PREFIX.'partner
      WHERE `id` = '.DB::quote($_POST['partner_id'])
    );

    if($row = DB::fetchAssoc($result)){
      $this->data['amount'] = $row['payments_amount']?$row['payments_amount']:0;
    }
    return true;
  }
  
  //Отправка заявки админам на выплату партнеру указанной суммы
  public function sendOrderToPayment(){ 
    $id = USER::getThis()->id;
  
    $data = array('noparnter'=>true);
    $result = DB::query('
      SELECT *
      FROM `'.PREFIX.'partner`
      WHERE `user_id` = '.DB::quote($id)
    );
    if($row = DB::fetchAssoc($result)){
    $data = $row;  
    
    $sitename = MG::getSetting('sitename');
      $subj = 'Партнер #'.$row['id'].' на сайте '.$sitename. ' хочет получить выплату';
      $msg = 'Партнер #'.$row['id'].' на сайте '.$sitename. ' хочет получить выплату в размере <b>'.$_POST['summ'].' рублей.</b>        
        <br/> Воспользуйтесь <a href="'.SITE.'/mg-admin">панелью администрирования</a>, чтобы проверить информацию о партнере и его заработке.';
      //если ответил пользователь то письма отправляются админам

        $mails = explode(',', MG::getSetting('adminEmail'));
        // Отправка заявки админам
        foreach($mails as $mail){

          if(preg_match('/^[A-Za-z0-9._-]+@[A-Za-z0-9_-]+.([A-Za-z0-9_-][A-Za-z0-9_]+)$/', $mail)){
            Mailer::addHeaders(array("Reply-to" => $this->email));
            Mailer::sendMimeMail(array(
              'nameFrom' => USER::getThis()->sname." ".USER::getThis()->name,
              'emailFrom' => USER::getThis()->email,
              'nameTo' => $sitename,
              'emailTo' => $mail,
              'subject' => $subj,
              'body' => $msg,
              'html' => true
            ));
          }
        }
        
      //оповещение на мыло партнера
          Mailer::sendMimeMail(array(
            'nameFrom' => $sitename,
            'emailFrom' => "noreply@".$sitename,
            'nameTo' => USER::getThis()->sname." ".USER::getThis()->name,
            'emailTo' => USER::getThis()->email,
            'subject' => 'Отправлена заявка на получение партнерской выплаты на сайте '.$sitename,
            'body' => 'Вами была отправлена заявка на получение партнерской выплаты на сайте '.$sitename.' в размере <b>'.$_POST['summ'].' рублей.</b>
              <br/>Пожалуйста, дождитесь пока мы свяжемся с Вами по электронной почте для учтонения способов перевода денежных средств.
              <br/>Данное письмо сформированно роботом, отвечать на него не надо.',
            'html' => true
          ));
              
    }
    return true;
  }
  /*
   * Добавление пользователя в партнеры по его запросу
   */  
  public function becomePartner(){ 
    if(!USER::getThis()->id) {
      return false;    
    } else{
      DB::query('INSERT INTO '.PREFIX.'partner (user_id,percent,payments_amount) VALUES('.DB::quote(USER::getThis()->id).','.PartnerProgram::$percent.',0);');
    }
    return true;
  }
  

}
