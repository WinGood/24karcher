<?php
function getSaleGoods()
{
	$model = new Models_Catalog;
	$saleProducts = $model->getListByUserFilter(MG::getSetting('countSaleProduct'), ' p.old_price>0 and p.activity=1 ORDER BY sort ASC');
	foreach ($saleProducts['catalogItems'] as &$item) {
		$item["recommend"] = 0;
		$item["new"] = 0;  
		$imagesUrl = explode("|", $item['image_url']);
		$item["image_url"] = "";
		if (!empty($imagesUrl[0]))
		{
			$item["image_url"] = $imagesUrl[0];
		}
	}

	return $saleProducts['catalogItems'];
}

function getBestSeller($count = 4)
{
	$getOrder = DB::query("SELECT order_content FROM `".PREFIX.'order'."`");
	if(DB::numRows($getOrder) != 0)
	{
		$product = new Models_Product();
		while ($row = DB::fetchArray($getOrder))
		{
			$orderData[] = unserialize(stripslashes($row['order_content']));
		}
		$res = array();
		foreach($orderData as $k => $v)
		{
			foreach($v as $key => $val)
			{
				$res[] = $product->getProduct($val['id']);
			}
		}
		$goodsSale = getCount($res);
		return array_slice($goodsSale, 0, $count);
	}
}

function getCount($goods)
{
	$count = array();
	foreach ($goods as $item)
	{
		// Кол-во одного товара в заказе
		$countInOrder = $item['count'];
		// Ключ массива - id товара
		if(isset($count[$item['id']]))
		{
			$count[$item['id']]['count_sale'] += $countInOrder;
			$count[$item['id']] += $item;
		}
		else
		{
			$count[$item['id']] = array('count_sale' => $countInOrder);
			$count[$item['id']] += $item;
		}
	}
	// Сортируем по полю count_sale в порядке убывания
	usort($count, function($a, $b){
		return ($a['count_sale'] - $b['count_sale']);
	});
	return $count;
}

function getImgPage($id)
{
	return PagesImg::getImgPage($id);
}

function getImgCat($id)
{
	return CategoryImg::getImgCat($id);
}

function checkGoodInCart($id)
{
	if(!empty($_SESSION['cart']))
	{
	  foreach($_SESSION['cart'] as $prd)
	  {
	    if($prd['id'] == $id)
	    {
	    	return TRUE;
	    }
	  }
	}
}
