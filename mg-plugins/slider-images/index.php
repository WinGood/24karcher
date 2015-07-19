<?php
/*
	Plugin Name: Слайдер изображений
	Description: Шорткод [slider-images]
	Author: Румянцев Олег
	Version: 0.1
 */

new SliderImages;
class SliderImages
{
	private static $lang = array();
	private static $pluginName;
	private static $path;

	public function __construct()
	{
		mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate'));
		mgAddAction(__FILE__, array(__CLASS__, 'pageSettingsPlugin'));
		mgAddShortcode('slider-images', array(__CLASS__, 'handleShortCode'));

		self::$pluginName = PM::getFolderPlugin(__FILE__);
		self::$lang       = PM::plugLocales(self::$pluginName);
		self::$path       = PLUGIN_DIR.self::$pluginName;

		if(!URL::isSection('mg-admin'))
		{
			mgAddMeta('<link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/jquery.bxslider.css" type="text/css" />');
			mgAddMeta('<link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/slider-images-user.css" type="text/css" />');
			mgAddMeta('<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/jquery.bxslider.min.js"></script>');
			mgAddMeta('<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/slider-images-user.js"></script>');
		}
	}

	static function activate()
	{
		self::createDateBase();
	}

	static function createDateBase()
	{
	    DB::query("
	     CREATE TABLE IF NOT EXISTS `".PREFIX.self::$pluginName."` (
	      `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID слайда',
		  `name_link` varchar(80) NULL DEFAULT NULL COMMENT 'Название ссылки',
	      `url_link` varchar(80) NULL DEFAULT NULL COMMENT 'URL ссылки',
	      `is_link` int(1) NOT NULL DEFAULT 0 COMMENT 'Слайд с ссылкой?', 
	      `desc` text NULL DEFAULT NULL COMMENT 'Описание слайда',   
	      `img` varchar(120) NOT NULL COMMENT 'Название изображения',
	      `sort` int(2) NOT NULL DEFAULT 0 COMMENT 'Сортировка слайдов',
	      `invisible` int(2) NOT NULL DEFAULT 1 COMMENT 'Видимость слайда'
	      PRIMARY KEY (`id`)
	    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");	

	    $seeds = DB::query("SELECT * FROM `".PREFIX.self::$pluginName."`");

	    if(DB::numRows($seeds) == 0)
	    {
	    	DB::query("
	    		INSERT INTO `".PREFIX.self::$pluginName."` VALUES(NULL, 'Заголовок слайда', '#', 'http://placehold.it/860x340', 0, 1)
	    	");
	    }
	}

	static function prepareSettingsPage()
	{
		echo '
			<link rel="stylesheet" href="'.SITE.'/'.self::$path.'/css/slider-images-admin.css" type="text/css" />
			<script type="text/javascript" src="'.SITE.'/'.self::$path.'/js/slider-images-admin.js"></script>
		';
	}

	static function pageSettingsPlugin()
	{		
		$entity = self::getEntity();

		self::prepareSettingsPage();
		include('page-settings.php');			
	}

	static function getEntity()
	{
		$res = DB::query("
			SELECT * FROM `".PREFIX.self::$pluginName."` WHERE invisible = 1 ORDER BY `sort` ASC
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

	static function handleShortCode()
	{
		$res = DB::query("
			SELECT * FROM `".PREFIX.self::$pluginName."` ORDER BY `sort` ASC
		");

		$html = '';
		$array = self::getEntity();
		if(!empty($array))
		{
			$html .= '<div id="slider-box">';
				$html .= '<ul id="slider-img">';
				foreach($array as $item)
				{
					$html .= '<li>';
					if($item['is_link'] == 1)
					{
						$html .= '<div class="slider-desc">';
							$html .= '<p class="title"><a href="'.SITE.'/'.$item['url_link'].'">'.$item['name_link'].'</a></p>';
							$html .= '<span>'.$item['desc'].'</span>';
						$html .= '</div>';
						$html .= '<img src="'.SITE.'/'.PLUGIN_DIR.self::$pluginName.'/img/slides/'.$item['img'].'" title="'.$item['name_link'].'" />';
					}
					else
					{
						$html .= '<img src="'.SITE.'/'.PLUGIN_DIR.self::$pluginName.'/img/slides/'.$item['img'].'"/>';
					}
					$html .= '</li>';
				}
				$html .= '</ul>';
			$html .= '</div>';
		}
		else
		{
			$html .= 'Добавьте слайды в административной панели.';
		}

		return $html;
	}
}
