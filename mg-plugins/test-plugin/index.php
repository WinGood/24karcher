<?php
/*
Plugin Name: TestPlugin
*/
new TestPlugin;
class TestPlugin
{
	public function __construct()
	{
		mgAddAction('models_product_getproduct' , array(__CLASS__, 'discountToProduct'), 1);
	}

	static function discountToProduct($arg)
	{
		echo '<span style="color: green">Эту страницу можно редактировать в панели администрирования</span>';
		return $arg['result'];
	}
}