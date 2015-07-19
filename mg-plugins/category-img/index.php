<?php
/*
	Plugin Name: Изображение в категории продуктов
	Description: Добавляет возможность загружать изображения в категории продуктов. Шорткод [category-img id="0"]
	Author: Румянцев Олег
	Version: 0.1
 */

// БАГ С ID И TEXTAREA

new CategoryImg;
class CategoryImg
{
	private static $lang = array();
	private static $pluginName;
	private static $path;

	public function __construct()
	{
		mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate'));
		mgAddAction('category_getcategorylistul', array(__CLASS__, 'addUploadField'), 1);
		mgAddAction('category_delcategory', array(__CLASS__, 'deleteCategory'), 1);

		self::$pluginName = PM::getFolderPlugin(__FILE__);
		self::$lang       = PM::plugLocales(self::$pluginName);
		self::$path       = PLUGIN_DIR.self::$pluginName;
	}

	static function activate()
	{
		self::createDataBase();
	}

	static function addUploadField($arg)
	{
		if($arg['args'][0] == 'admin')
		{
			echo '<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/category-img.js"></script>';
		}
		return $arg['result'];
	}

	static function deleteCategory($arg)
	{
		$id_cat = $arg['args'][0];
		if (DB::query('DELETE FROM `'.PREFIX.self::$pluginName.'` WHERE `id_cat`= '.$id_cat))
		{
			return $arg['result'];
		}
	}

	static function createDataBase()
	{
	    DB::query("
	     CREATE TABLE IF NOT EXISTS `".PREFIX.self::$pluginName."` (
	      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Порядковый номер',
		  `id_cat` int(11) NOT NULL COMMENT 'ID категории',
	      `img` varchar(120) NOT NULL COMMENT 'Изображение для категории',      
	      PRIMARY KEY (`id`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}

	static function getImgCat($id)
	{
		if(!empty($id))
		{
			$res = DB::query("SELECT * FROM `".PREFIX.self::$pluginName."` WHERE `id_cat`=".$id);
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