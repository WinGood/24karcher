<?php
class Models_Index {
	public function getPopularGoods($count)
	{
		//$pGoods = PopularGoods::getProduct($count);
		//return $pGoods;
	}

	public function getNewGoods($count)
	{
		$nGoods = NewGoods::getNewGoods($count);
		return $nGoods;
	}
}