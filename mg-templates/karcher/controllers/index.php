<?php
class Controllers_Index extends BaseController 
{
	public function __construct()
	{
		$modelIndex = new Models_Index;
		//$pageModel  = new Models_Page;
		$page       = MG::get('pages')->getPageByUrl('index');

		$this->data = array(
			'popularGoods'  => getBestSeller(8),
			'newGoods'      => $modelIndex->getNewGoods(8),
			'currency'      => MG::getSetting('currency'),
			'meta_title'    => $page['meta_title'],
			'meta_keywords' => $page['meta_keywords'],
			'meta_desc'     => $page['meta_desc']
		);
	}
}