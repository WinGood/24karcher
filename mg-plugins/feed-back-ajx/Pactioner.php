<?php
class Pactioner extends Actioner 
{
	public function send()
	{
		if(empty($_POST['fio']) || empty($_POST['email']) || empty($_POST['message']) || empty($_POST['capcha']))
		{
			$this->messageError = 'Необходимо заполнить все поля!';
			return FALSE;
		}
		else
		{
			if(!preg_match('/^[A-Za-z0-9._-]+@[A-Za-z0-9_-]+.([A-Za-z0-9_-][A-Za-z0-9_]+)$/', $_POST['email']))
			{     
			  $this->messageError = 'Неверно заполнено поле e-mail!';
			  return FALSE;
			}

			if($_POST['capcha'] != $_SESSION['capcha'])
			{
			  $this->messageError = 'Неверно введен код с картинки';
			  return FALSE;
			}
		}

		$feedBack = new Models_Feedback;
		$error    = $feedBack->isValidData($_POST);	
		$sitename = MG::getSetting('sitename');       
		$message  = str_replace('№', '#', $feedBack->getMessage());
		$mails    = explode(',', MG::getSetting('adminEmail'));    
		
		foreach($mails as $mail)
		{
			if(preg_match('/^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/', $mail))
			{
				Mailer::addHeaders(array("Reply-to" => $feedBack->getEmail()));
				Mailer::sendMimeMail(array(
					'nameFrom'  => $feedBack->getFio(),
					'emailFrom' => $feedBack->getEmail(),
					'nameTo'    => $sitename,
					'emailTo'   => $mail,
					'subject'   => 'Сообщение с формы обратной связи',
					'body'      => $message,
					'html'      => true
					));
			}
		}

		$this->messageSucces = 'Ваше сообщение успешно отправлено! В ближайшее время мы свяжемся с вами.';
		return TRUE;
	}
}