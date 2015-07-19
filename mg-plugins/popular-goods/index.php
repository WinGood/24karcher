<?php
/*
	Plugin Name: Популярные товары
	Description: Шорткод [popular-goods]
	Author: Румянцев Олег
	Version: 0.1
 */

new PopularGoods;
class PopularGoods
{
	private static $pluginName;

	public function __construct()
	{
		mgActivateThisPlugin(__FILE__, array(__CLASS__, 'activate'));
		mgAddAction('moguta_convertcpuproduct' , array(__CLASS__, 'counterView'), 1);

		self::$pluginName = PM::getFolderPlugin(__FILE__);
	}

	static function activate()
	{
		self::createTable();
	}

	static function createTable()
	{
		DB::query("
		 CREATE TABLE IF NOT EXISTS `".PREFIX.self::$pluginName.'_ips'."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Порядковый номер записи',  
		  `ip_address` varchar(50) NOT NULL COMMENT 'IP пользователя',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

		DB::query("
		 CREATE TABLE IF NOT EXISTS `".PREFIX.self::$pluginName.'_visits'."` (
		  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Порядковый номер записи',
		  `id_product` int(11) NOT NULL COMMENT 'ID продукта', 
		  `date` date NOT NULL COMMENT 'Дата визита',
		  `views` int(12) NOT NULL COMMENT 'Ко-во хитов',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}

	static function counterView($arg)
	{
		$model        = new Models_Product; 
		$product      = $model->getProduct(URL::getQueryParametr('id'));
		$user_ip      = $_SERVER['REMOTE_ADDR'];
		$date         = date('Y-m-d');
		$id_product   = $product['id'];
		$visits_today = DB::query("SELECT `id` FROM `".PREFIX.self::$pluginName.'_visits'."` WHERE `date`='".$date."'");
		
		$product = DB::query("SELECT `id` FROM `".PREFIX.self::$pluginName.'_visits'."` WHERE id_product='".$id_product."'");

		if(!DB::numRows($product))
		{
			DB::query("INSERT INTO `".PREFIX.self::$pluginName.'_visits'."` SET `views`=1, `id_product`='".$id_product."', `date`='".$date."'");
		}
		else
		{
			DB::query("UPDATE `".PREFIX.self::$pluginName.'_visits'."` SET `date`='".$date."', `views`=`views`+1 WHERE `id_product`='".$id_product."'");
		}
		return $arg['result'];
	}

	static function getProduct($count)
	{
		$res = DB::query('SELECT
          DISTINCT p.id,
          CONCAT(c.parent_url,c.url) as category_url,
          p.url as product_url,
          p.*, v.views, pv.product_id as variant_exist
        FROM `'.PREFIX.self::$pluginName.'_visits` v
        INNER JOIN `'.PREFIX.'product` p ON v.id_product = p.id
        LEFT JOIN `'.PREFIX.'category` c
          ON c.id = p.cat_id
        LEFT JOIN `'.PREFIX.'product_variant` pv
          ON p.id = pv.product_id
        LEFT JOIN (
          SELECT pv.product_id, SUM( pv.count ) AS varcount
          FROM  `'.PREFIX.'product_variant` AS pv
          GROUP BY pv.product_id
        ) AS temp ON p.id = temp.product_id ORDER BY v.views DESC LIMIT '.$count.'');	
		if(DB::numRows($res) != 0)
		{
			while ($row = DB::fetchAssoc($res))
			{
				if($row['image_url'])
				{
					$img = explode('|', $row['image_url']);
					$row['image_url'] = $img[0];
				}
				$array[] = $row;
			}
			return $array;
		}	
	}
}
