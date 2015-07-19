<?php
class Controllers_Catalog extends BaseController
{
	public function __construct()
	{			
		$aUri      = URL::getSections();
		$settings  = MG::get('settings');

		// Если нажата кнопка купить.
		$_REQUEST['category_id'] = URL::getQueryParametr('category_id');

		if (!empty($_REQUEST['inCartProductId'])) {
		  $cart = new Models_Cart;
		  $property = $cart->createProperty($_POST);
		  $cart->addToCart($_REQUEST['inCartProductId'], $_REQUEST['amount_input'], $property);
		  SmalCart::setCartData();
		  MG::redirect('/cart');
		}

		$countСatalogProduct = $settings['countСatalogProduct'];
		// Показать первую страницу выбранного раздела.
		$page = 1;

		// Запрашиваемая страница.
		if (isset($_REQUEST['p'])) {
		  $page = $_REQUEST['p'];
		}

		$sortType   = 'desc';
		$countGoods = $settings['countСatalogProduct'];
		$isStock 	= 0;

		// Обработка GET параметров
		if(isset($_GET['count']))
		{
			switch($_GET['count'])
			{
				case 8:
					$countGoods = 8;
				break;
				case 12:
					$countGoods = 12;
				break;
				case 16:
					$countGoods = 16;
				break;
				default:
					$countGoods = 8;
				break;
			}
		}

		if(isset($_GET['sort']))
		{
			switch($_GET['sort'])
			{
				case 'priceDesc':
					$sortType = 'desc';
				break;
				case 'priceAsc':
					$sortType = 'asc';
				break;
				case 'comments':
					$sortType = 'comments';
				break;
				case 'popular':
					$sortType = 'popular';
				break;
				default:
					$sortType = 'desc';
				break;
			}
		}

		if(isset($_GET['stock']))
		{
			if($_GET['stock'] == 1)
				$isStock = 1;
			else
				$isStock = 0;
		}

		$model     = new Models_Catalog;
		$isMainCat = TRUE;
		$subCat    = MG::get('category')->getCategoryList($_REQUEST['category_id']);

		if(empty($subCat))
			$isMainCat = FALSE;

		// Если происходит поиск по ключевым словам.
		$keyword = URL::getQueryParametr('search');

		if (!empty($keyword)) {
		  $items = $model->getListProductByKeyWord($keyword, false, false, false, $sortType, $isStock, $countGoods);

		  $searchData = array('keyword' => $keyword, 'count' => $items['numRows']);
		} else {
			if($isMainCat)
			{
				$model->categoryId = MG::get('category')->getCategoryList($_REQUEST['category_id']);
				$model->categoryId[] = $_REQUEST['category_id'];

				$subCatList = $model->getChildCat();
				if(!empty($subCatList))
				{
					$productList = $model->getMainCatProduct($subCatList);
					$catList     = $model->getMainCatInfo($subCatList);
					$i = 0;
					foreach($catList as $cat)
					{
						foreach($productList as $prd)
						{
							if($prd['cat_id'] == $cat['id'])
							{
								$imagesUrl = explode("|", $prd['image_url']);
								if (!empty($imagesUrl[0]))
								{
								  $prd['image_url'] = $imagesUrl[0];
								}
								$catList[$i]['items'][] = $prd;
							}
						}
						$i++;
					}
				}
			}
			else
			{
				// Получаем список вложенных категорий, для вывода всех продуктов, на страницах текущей категории.
				$model->categoryId = MG::get('category')->getCategoryList($_REQUEST['category_id']);
				// В конец списка, добавляем корневую текущую категорию.
				$model->categoryId[] = $_REQUEST['category_id'];

				$items = $model->getList($countGoods, false, true, $sortType, $isStock);
			}
		}

		$settings = MG::get('settings');

		if(!$isMainCat)
		{
			foreach ($items['catalogItems'] as $item)
			{
			  $productIds[] = $item['id'];
			}

			$product = new Models_Product;
			$blocksVariants = $product->getBlocksVariantsToCatalog($productIds);

			foreach ($items['catalogItems'] as $k => $item)
			{
			  $items['catalogItems'][$k]["recommend"] = 0;
			  $items['catalogItems'][$k]["new"] = 0; 
			  $imagesUrl = explode("|", $item['image_url']);
			  $items['catalogItems'][$k]["image_url"] = "";
			  if (!empty($imagesUrl[0]))
			  {
			    $items['catalogItems'][$k]["image_url"] = $imagesUrl[0];
			  }

			  $items['catalogItems'][$k]['title'] = MG::modalEditor('catalog', $item['title'], 'edit', $item["id"]);
			  
			  // Формируем варианты товара.
			  if ($item['variant_exist']) 
			  {
			  	// Легкая форма без характеристик.
			    $liteFormData = $product->createPropertyForm($param = array(
					'id'                => $item['id'],
					'maxCount'          => $item['count'],
					'productUserFields' => null,
					'action'            => "/catalog",
					'method'            => "POST",
					'ajax'              => true,
					'blockedProp'       => array(),
					'noneAmount'        => true,
					'titleBtn'          => "В корзину",
					'blockVariants'     => $blocksVariants[$item['id']]
			    ));
			    $items['catalogItems'][$k]['liteFormData'] = $liteFormData['html'];
			  }
			}
		}

		$categoryDesc = MG::get('category')->getDesctiption($_REQUEST['category_id']);

		if ($_REQUEST['category_id'])
		{
		  $categoryDesc = MG::inlineEditor(PREFIX.'category', "html_content", $_REQUEST['category_id'], $categoryDesc);
		}

		if($isMainCat)
		{
			$data = array(
				'titeCategory'  => $model->currentCategory['title'],
				'cat_desc'      => $categoryDesc,
				'meta_title'    => !empty($model->currentCategory['meta_title']) ? $model->currentCategory['meta_title'] : $model->currentCategory['title'],
				'meta_keywords' => !empty($model->currentCategory['meta_keywords']) ? $model->currentCategory['meta_keywords'] : "товары,продукты,изделия",
				'meta_desc'     => !empty($model->currentCategory['meta_desc']) ? $model->currentCategory['meta_desc'] : "В каталоге нашего магазина есть все.",
				'is_main_cat' 	=> $isMainCat,
				'category_info'	=> $catList,
				'currency'      => MG::getSetting('currency'),
				'id_category'	=> $model->getCurrentId(),
				'searchData'    => empty($searchData) ? '' : $searchData
			);
		}
		else
		{
			$data = array(
				'items'         => $items['catalogItems'],
				'titeCategory'  => $model->currentCategory['title'],
				'cat_desc'      => $categoryDesc,
				'pager'         => $items['pager'],
				'searchData'    => empty($searchData) ? '' : $searchData,
				'meta_title'    => !empty($model->currentCategory['meta_title']) ? $model->currentCategory['meta_title'] : $model->currentCategory['title'],
				'meta_keywords' => !empty($model->currentCategory['meta_keywords']) ? $model->currentCategory['meta_keywords'] : "товары,продукты,изделия",
				'meta_desc'     => !empty($model->currentCategory['meta_desc']) ? $model->currentCategory['meta_desc'] : "В каталоге нашего магазина есть все.",
				'currency'      => $settings['currency'],
				'actionButton'  => MG::getSetting('actionInCatalog') === "true" ? 'actionBuy' : 'actionView',
				'is_main_cat'	=> $isMainCat,
				'id_category'	=> $model->getCurrentId()
			);
		}

		if ($keyword)
		{
		  $data['meta_title'] = 'Поиск по фразе: '.$keyword;
		}

		$this->data = $data;

		if($aUri[1] == 'catalog' && empty($aUri[2]) && empty($_GET['search']))
		{
			MG::redirect('/');
		}
	}
}