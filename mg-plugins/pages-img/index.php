<?php
/*
	Plugin Name: Изображения для статических страниц
	Description: Добавляет возможность загружать изображение к статической странице.
	Author: Румянцев Олег
	Version: 0.1
 */
new PagesImg;
class PagesImg {
	private static $lang = array();
	private static $pluginName;
	private static $path;

	public function __construct()
	{
		mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate'));
		mgAddAction('page_getpagesul', array(__CLASS__, 'addUploadField'), 1);
		mgAddAction('page_delpage', array(__CLASS__, 'deletePage'), 1);

		self::$pluginName = PM::getFolderPlugin(__FILE__);
		self::$lang       = PM::plugLocales(self::$pluginName);
		self::$path       = PLUGIN_DIR.self::$pluginName;
	}

	static function addUploadField($arg)
	{
		if($arg['args'][1] == 'admin')
		{
			echo '<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/pages-img.js"></script>';
		}
		return $arg['result'];
	}

	static function deletePage($arg)
	{
		$id_page = $arg['args'][0];
		if (DB::query('DELETE FROM `'.PREFIX.self::$pluginName.'` WHERE `id_page`= '.$id_page))
		{
			return $arg['result'];
		}
	}

	static function activate()
	{
		self::createDataBase();
	}

	static function createDataBase()
	{
	    DB::query("
	     CREATE TABLE IF NOT EXISTS `".PREFIX.self::$pluginName."` (
	      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Порядковый номер',
		  `id_page` int(11) NOT NULL COMMENT 'ID страницы',
	      `img` varchar(120) NOT NULL COMMENT 'Изображение для страницы',      
	      PRIMARY KEY (`id`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}

	static function getImgPage($id)
	{
		if(!empty($id))
		{
			$res = DB::query("SELECT * FROM `".PREFIX.self::$pluginName."` WHERE `id_page`=".$id);
		    if (DB::numRows($res) > 0) 
		    {
		    	$arr = DB::fetchAssoc($res);
		    	if(!empty($arr['img']))
		    		return PLUGIN_DIR.self::$pluginName.'/img/'.$arr['img'];
		    }
		}
		else
		{
			echo 'Не указан обязательный атрибут id';
		}
	}
}