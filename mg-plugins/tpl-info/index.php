<?php
/*
	Plugin Name: Настройки шаблона
	Description: Редактирование второстепенной информации в шаблоне
	Author: Румянцев Олег
	Version: 0.1
 */

new TplInfo;
class TplInfo
{
	private static $pluginName;
	private static $path;

	public function __construct()
	{
		mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate'));
		mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin'));

		self::$pluginName = PM::getFolderPlugin(__FILE__);
		self::$path       = PLUGIN_DIR.self::$pluginName;
	}

	static function activate()
	{
		self::createDateBase();
	}

	static function createDateBase()
	{
	    DB::query("
	     CREATE TABLE IF NOT EXISTS `".PREFIX.self::$pluginName."` (
	      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
		  `option` varchar(80) NOT NULL COMMENT 'Название опции',
	      `value` TEXT NOT NULL COMMENT 'Значение опции',
	      `name` varchar(120) NOT NULL COMMENT 'Название опции',
	      `desc` varchar(120) NOT NULL COMMENT 'Описание опции',
	      PRIMARY KEY (`id`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");	

	    $seeds = DB::query("SELECT * FROM `".PREFIX.self::$pluginName."`");

	    if(DB::numRows($seeds) == 0)
	    {
	    	DB::query("
	    		INSERT INTO `".PREFIX.self::$pluginName."` VALUES
	    		(NULL, 'phone', '2-88-56-57', 'Телефон', 'Телефон отображается на сайте'),
	    		(NULL, 'fax', '2-88-56-57', 'Факс', 'Факс отображается на сайте'),
	    		(NULL, 'leftBanner', 'banner.jpg', 'Адрес картинки', 'Адрес картинки отображается в левой колонке'),
	    		(NULL, 'leftBannerUrl', '/page', 'Url баннера', 'Адрес страницы, переход по клику на баннер'),
	    		(NULL, 'slogan', 'Интернет магазин техники Karcher', 'Слоган сайта', 'Слоган сайта отображается в шапке сайта'),
	    		(NULL, 'aboutFooter', '<p>В нашем магазине вы всегда сможете купить широкий спектр бытового и профессионального моечного оборудования Karcher по низкой цене: мойки высокого давления, пылесосы, минимойки, электровеникии и другую технику.</p><p>Наши консультанты помогут вам подобрать именно тот товар который подойдет для решения ваших задач. Ни один клиент будь то представитель клининговой компании или же простая домохозяйка, не уйдет от нас недовольным!</p>', 'Блок О нас', 'Информация в блоке О нас в футере'),
	    		(NULL, 'addrFooter', 'г. Красноярск, ул. Вавилова, 1 стр. 39 первый этаж', 'Адрес магазина', 'Адрес магазина, отображается в футере')
	    	");
	    }
	}

	static function prepareSettingsPage()
	{
		echo '
			<link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/settings.css" type="text/css" />
			<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/admin.js"></script>
		';
	}

	static function getEntity()
	{
		$res = DB::query("
			SELECT * FROM `".PREFIX.self::$pluginName."`
		");

		if(DB::numRows($res) != 0)
		{
			while ($row = DB::fetchAssoc($res))
			{
				$array[] = $row;
			}
			return $array;
		}
	}

	static function getBanner()
	{
		return PLUGIN_DIR.'tpl-info/img/'.self::getOption('leftBanner');
	}

	static function getOption($name)
	{
		$res = DB::query("
			SELECT value FROM `".PREFIX.self::$pluginName."` WHERE `option` = '$name'
		");

		if(DB::numRows($res) != 0)
		{
			while ($row = DB::fetchAssoc($res))
			{
				$array[] = $row;
			}
			return $array[0]['value'];
		}
	}

	static function pageSettingsPlugin()
	{		
		$entity = self::getEntity();

		self::prepareSettingsPage();
		include('page-settings.php');			
	}
}
