<?php
class Pactioner extends Actioner {

	private $pluginName = 'pages-img';

	public function getCountPage()
	{
		$sql    = "SELECT MAX(`id`) FROM `".PREFIX.'page'."`";
		$res    = DB::query($sql);
		if($count = DB::fetchArray($res))
		{
			$this->data['count'] = $count[0] + 1;
			return TRUE;
		}
	}

	private function checkImg()
	{
		$nameFile  = $_FILES['imgPage']['name'];
		$whileList = array('.jpeg', '.jpg', '.png');
		foreach($whileList as $item)
		{
			if (preg_match("/$item\$/i", $nameFile))
			{
				if(getimagesize($_FILES['imgPage']['tmp_name']))
				{
					return $item;
				}
			}
		}
	}

	public function loadImg()
	{
		$this->messageSucces = $this->lang['IMG_SUCCESS_UPLOAD'];
		$this->messageError  = $this->lang['IMG_ERROR_UPLOAD'];

		if(isset($_POST))
		{
			if($ext = $this->checkImg($_FILES))
			{
				$gen_name = md5(mt_rand(100, 10000).time());
				$new_name = $gen_name.$ext;

				$savePath = PLUGIN_DIR.$this->pluginName.'/img/'.$new_name;
				if (move_uploaded_file($_FILES['imgPage']['tmp_name'], $savePath))
				{
					$this->data['img'] = $new_name;
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
		else
		{
			return FALSE;
		}
	}

	public function getImg()
	{
		$res = DB::query("SELECT * FROM `".PREFIX.$this->pluginName."` WHERE id_page = ".$_POST['id']."");
		$arr = DB::fetchAssoc($res);
		$this->data['img'] = $arr['img'];
		return TRUE;
	}

	private function isIssetImg($id)
	{
		$res = DB::query("SELECT * FROM `".PREFIX.$this->pluginName."` WHERE id_page = ".$id."");
		$arr = DB::fetchAssoc($res);
		if(!empty($arr))
			return TRUE;
	}

	public function addImg()
	{
		unset($_POST['pluginHandler']);
		if(DB::buildQuery('INSERT INTO `'.PREFIX.$this->pluginName.'` SET ', $_POST))
		{
			return true;
		}
	}

	public function editImg()
	{
		unset($_POST['pluginHandler']);
		$id = $_POST['id_page'];
		if($this->isIssetImg($id))
		{
			//unset($_POST['img']);
			if(DB::query('UPDATE `'.PREFIX.$this->pluginName.'` SET '.DB::buildPartQuery($_POST).' WHERE `id_page` = '.$id))
				return TRUE;
		}
		else
		{
			if(DB::buildQuery('INSERT INTO `'.PREFIX.$this->pluginName.'` SET ', $_POST))
				return TRUE;
		}
	}

}