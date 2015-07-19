<?php
class Pactioner extends Actioner 
{
	private $pluginName = 'call_back';

	/* Обработка формы обратной связи, пользователь
	======================================*/

	public function handlerCall()
	{
		$this->messageSucces = $this->lang['ENTITY_SAVE_SUCCESS'];
		$this->messageError  = $this->lang['ENTITY_SAVE_ERROR'];
		unset($_POST['pluginHandler']);
		$conf = $this->getConfigPlugin();

		$msg = 'Обр. звонок. ' . $_POST['name'] . ' ' . $_POST['phone'];
		if(!empty($_POST['comment'])) $msg .= ' ' . $_POST['comment'];

		SMSAlerts::sendsms(null, $msg);

		if($conf['send_mail'])
		{
			if($this->sendMail())
				return true;
			else
				return false;
		}
		else
		{
			if(DB::buildQuery('INSERT INTO `'.PREFIX.$this->pluginName.'` SET ', $_POST))
			{
				return true;
			}
		}
		return false;
	}

	/* UPDATE id поменять при установке на хостинг, админ
	======================================*/
	
	public function updateConfig()
	{
		$this->messageSucces = $this->lang['CONFIG_SAVE_SUCCESS'];
		$this->messageError  = $this->lang['CONFIG_SAVE_ERROR'];
		unset($_POST['pluginHandler']);
		if(DB::query('UPDATE `'.PREFIX.$this->pluginName.'_config'.'` SET '.DB::buildPartQuery($_POST).' WHERE id = 3'))
			return true;

		return false;
	}

	public function visibleEntity()
	{
		$this->messageSucces = $this->lang['OPERATION_SUCCESS'];
		$this->messageError  = $this->lang['OPERATION_ERROR'];
		unset($_POST['pluginHandler']);
		$id = $_POST['id'];
		unset($_POST['id']);

		if(DB::query('UPDATE `'.PREFIX.$this->pluginName.'` SET '.DB::buildPartQuery($_POST).' WHERE id = '.$id.''))
			return true;

		return false;	
	}

	public function deleteEntity()
	{
		$this->messageSucces = $this->lang['ENTITY_DELETE_SUCCESS'];
		$this->messageError  = $this->lang['ENTITY_DELETE_ERROR'];

		if (DB::query('DELETE FROM `'.PREFIX.$this->pluginName.'` WHERE `id`= '.$_POST['id']))
			return true;
		return false;
	}

	private function getConfigPlugin()
	{
		$res = DB::query("SELECT * FROM `".PREFIX.$this->pluginName.'_config'."`");
		return DB::fetchAssoc($res);
	}

	private function sendMail()
	{
		$msg = '';
		foreach ($_POST as $k => $v)
		{
		  $msg .= '<b>'.$k.':'.'</b> '.htmlspecialchars($v).'<br>';
		}
		$msg .= "<b>Отвечать на письмо не нужно</b>";

		$siteName = MG::getOption('sitename');

		return Mailer::sendMimeMail(array(
			'nameFrom'  => $siteName,
			'emailFrom' => MG::getSetting('noReplyEmail'),
			'nameTo'    => 'Администратору сайта '.$siteName,
			'emailTo'   => MG::getOption('adminEmail'),
			'subject'   => 'Форма обратного звонка '.$siteName,
			'body'      => $msg,
			'html'      => true
		));
	}
}