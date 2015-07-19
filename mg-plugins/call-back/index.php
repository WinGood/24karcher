<?php
/*
  Plugin Name: Обратный звонок
  Description: Шорткод [call-back]
  Author: Румянцев Олег
  Version: 1.0
 */

new CallBack;
class CallBack
{
	private static $lang = array();
	private static $pluginName = 'call_back';
	private static $path;
	
	public function __construct()
	{
		mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate'));
		mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin'));
		mgAddShortcode('call-back', array(__CLASS__, 'handleShortCode'));
		
		self::$lang       = PM::plugLocales(self::$pluginName);
		self::$path       = PLUGIN_DIR.self::$pluginName;
		
		if(!URL::isSection('mg-admin'))
		{
			mgAddMeta('<link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/user.css" type="text/css" />');
			mgAddMeta('<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/jquery.maskedinput.min.js"></script>');
			mgAddMeta('<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/user.js"></script>');
		}

		MG::addInformer(array('count' => self::getEntityActive(), 'class' => 'count-wrap', 'classIcon' => 'message-icon', 'isPlugin' => true, 'section' => 'call-back', 'priority' => 80));
	}
	
	static function activate()
	{
		self::createTable();
	}

	static function createTable()
	{
	    DB::query("
	     CREATE TABLE IF NOT EXISTS `".PREFIX."call_back` (
	      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Порядковый номер записи',
		  `name` text NOT NULL COMMENT 'Имя',
	      `phone` text NOT NULL COMMENT 'Телефон',      
	      `time` timestamp DEFAULT NOW() COMMENT 'Время добавления заявки',
	      `invisible` int(1) NOT NULL COMMENT 'Просмотр заявки',
	      `comment` text NULL COMMENT 'Комментарий к заявке',
	      PRIMARY KEY (`id`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

	    DB::query("
	    	CREATE TABLE IF NOT EXISTS `".PREFIX."call_back_config` (
	    	`id` int(11) NOT NULL AUTO_INCREMENT ,
	    	`send_mail` ENUM('0','1') DEFAULT '0',
	    	`email_address` VARCHAR(200) NOT NULL DEFAULT '".MG::getOption('adminEmail')."',
	    	PRIMARY KEY (`id`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

	    $seeds = DB::query("SELECT * FROM `".PREFIX.'call_back_config'."`");
	    $numb  = DB::numRows($seeds);

	    if($numb == 0)
	    {
	    	DB::query("
	    		INSERT INTO `".PREFIX.'call_back_config'."` VALUES(NULL, '0', '".MG::getOption('adminEmail')."')
	    	");
	    }
	    
	    // Был ли плагин активирован ранее?
	    $res = DB::query("
	    	SELECT id
	    	FROM `".PREFIX."call_back`
	    	WHERE id in (1,2,3)
	    ");
	    
	    // Если плагин впервые активирован, то задаются настройки по умолчанию
	    if (!DB::numRows($res)) 
	    {    
	    	$array = Array(
	    		'countRows' => '10'
	    	); 
	    	MG::setOption(array(
				'option' => 'call-backOption', 
				'value'  => addslashes(serialize($array)
	    	)));  
	    }
	}
	
	static function prepareSettingsPage()
	{
		echo '
			<link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/settings.css" type="text/css" />
			<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/admin.js"></script>
		';
	}
	
	static function pageSettingsPlugin()
	{
		$lang       = self::$lang;
		$pluginName = self::$pluginName;
		
		$option  = MG::getSetting('call-backOption');
		$option  = stripslashes($option);
		$options = unserialize($option);
		
		$res        = self::getEntity($options['countRows']);
		$config 	= self::getConfigPlugin();
		$entity     = $res['entity'];
		$pagination = $res['pagination'];
		
		self::prepareSettingsPage();
		include('page-settings.php');		
	}
	
	static function getEntity()
	{
		$result = array();
		$sql    = "SELECT * FROM `".PREFIX."call_back` ORDER BY id ASC";
		
		if ($_POST["page"])
			$page = $_POST["page"];
		
		$navigator  = new Navigator($sql, $page);
		$entity     = $navigator->getRowsSql();
		$pagination = $navigator->getPager('forAjax');
		$result = array(
			'entity'     => $entity,
			'pagination' => $pagination
		);
		return $result;
	}

	static function getEntityActive()
	{
		$result = array();
		$sql    = "SELECT count(id) as count FROM `".PREFIX."call_back` WHERE invisible = 0";
		$res    = DB::query($sql);
		if($count = DB::fetchAssoc($res))
		  return $count['count'];
		return 0;		
	}

	static function getConfigPlugin()
	{
		$res = DB::query("SELECT * FROM `".PREFIX.'call_back_config'."`");
		return DB::fetchAssoc($res);
	}
		
	static function handleShortCode()
	{
		$html = '<i class="icon-callback"></i> <a id="ajxcallBackBtn" href="#ajxCallBack" class="modalbox orange b-d">обратный звонок</a>';
		$html .= '
		<div id="ajxCallBack">
			<div class="content-modal">
			<p class="title">Заказать обратный звонок</p>
				<div class="form-el">
					<label for="usrName">Ваше имя:</label>
					<input type="text" id="usrName" class="rf" placeholder="Иванов Иван" name="usrName">
				</div>
				<div class="form-el">
					<label for="usrPhone">Ваш телефон:</label>
					<input type="text" id="usrPhone" class="rf" placeholder="_(___) ___ __ __" name="usrPhone">
				</div>
				<div class="form-el">
					<label for="usrComment">Комментарий:</label>
					<textarea name="usrComment" id="usrComment" placeholder="В какое время вам удобно позвонить?"></textarea>
				</div>
				<button>Отправить</button>
			</div>
		</div>';
		return $html;
	}
}