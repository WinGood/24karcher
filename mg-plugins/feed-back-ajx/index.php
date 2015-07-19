<?php
/*
  Plugin Name: Обратная связь на Ajax
  Description: Шорткод [feed-back-ajx]
  Author: Румянцев Олег
  Version: 1.0
 */
new FeedBackAjx;
class FeedBackAjx
{
	private static $pluginName;
	private static $path;

	public function __construct()
	{
		mgAddShortcode('feed-back-ajx', array(__CLASS__, 'handleShortCode'));
		
		self::$pluginName = PM::getFolderPlugin(__FILE__);
		self::$path       = PLUGIN_DIR.self::$pluginName;
		
		if(!URL::isSection('mg-admin'))
		{
			mgAddMeta('<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/feed-back-user.js"></script>');
		}
	}

	public static function handleShortCode()
	{
		$capDir = PATH_TEMPLATE.'/images/cap.png';
		$html = '
		<div id="feed-back-ajx-msg"></div>
		<div class="add-cmt-form">
			<div class="form-el">
				<label for="txtName">Ваше имя:</label>
				<input type="text" name="fio" id="txtName">
			</div>
			<div class="form-el">
				<label for="txtEmail">Email:</label>
				<input type="text" name="email" id="txtEmail">
			</div>
			<div class="form-el">
				<label for="txtMsg">Сообщение:</label>
				<textarea style="min-height:80px;" name="message" id="txtMsg"></textarea>
			</div>
			<div class="form-el">
				<label for="intCapcha">Текст с картинки:</label>
				<img style="margin-top: 5px; border: 1px solid gray; background: url('.$capDir.');" src = "captcha.html" width="140" height="36">
				<br>
				<input style="width:124px;" type="text" id="intCapcha" name="capcha" class="captcha">
			</div>
		</div>
		<div class="form-btn clearfix" style="border:none;padding:0;">
			<button id="feed-back-send">Отправить</button>
		</div>';
		return $html;
	}
}