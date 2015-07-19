<?php
/*
	Plugin Name: Новые товары
	Description: Шорткод [new-goods]
	Author: Румянцев Олег
	Version: 0.1
 */

// Сделать по дате добавления 

new NewGoods;
class NewGoods
{
	static function getNewGoods($count)
	{
		$res = DB::query('SELECT
          DISTINCT p.id,
          CONCAT(c.parent_url,c.url) as category_url,
          p.url as product_url,
          p.*, pv.product_id as variant_exist
        FROM `'.PREFIX.'product` p
        LEFT JOIN `'.PREFIX.'category` c
          ON c.id = p.cat_id
        LEFT JOIN `'.PREFIX.'product_variant` pv
          ON p.id = pv.product_id
        LEFT JOIN (
          SELECT pv.product_id, SUM( pv.count ) AS varcount
          FROM  `'.PREFIX.'product_variant` AS pv
          GROUP BY pv.product_id
        ) AS temp ON p.id = temp.product_id WHERE new = 1 LIMIT '.$count.'');	

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