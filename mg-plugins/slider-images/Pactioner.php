<?php
class Pactioner extends Actioner
{
	private $pluginName = 'slider-images';

	private function checkImg()
	{
		$nameFile  = $_FILES['img']['name'];
		$whileList = array('.jpeg', '.jpg', '.png');
		foreach($whileList as $item)
		{
			if (preg_match("/$item\$/i", $nameFile))
			{
				if(getimagesize($_FILES['img']['tmp_name']))
				{
					return $item;
				}
			}
		}
	}

	public function getSlide()
	{
		$result = DB::query('
		  SELECT *
		  FROM `'.PREFIX.$this->pluginName.'`
		  WHERE `id` = '.DB::quote($_POST['id'])
		);

		if($row = DB::fetchAssoc($result))
		{
			if($row['img'])
			{
				$row['name_img'] = $row['img'];
				$row['img'] = SITE.'/'.PLUGIN_DIR.'slider-images/img/slides/'.$row['img'];
			}
			
			$this->data = $row;
			return true;
		}
		return false;
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

				$savePath = PLUGIN_DIR.$this->pluginName.'/img/slides/'.$new_name;
				if (move_uploaded_file($_FILES['img']['tmp_name'], $savePath))
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

	private function getCountSlide()
	{
		$sql    = "SELECT count(id) as count FROM `".PREFIX.$this->pluginName."`";
		$res    = DB::query($sql);
		if($count = DB::fetchAssoc($res))
		  return $count['count'] + 1;
	}

	public function updateSlide($array)
	{
		$this->messageError  = $this->lang['ENTITY_EDIT_ERROR'];
		$this->messageSucces = $this->lang['ENTITY_EDIT_SUCCESS'];

		$id = $array['id'];
		
		if(!empty($id))
		{
		  if(DB::query("
		    UPDATE `".PREFIX.$this->pluginName."`
		    SET `name_link` = '".$array['name_link']."',
		    	`url_link`  = '".$array['url_link']."',
		    	`is_link`	= 1,
		    	`desc`		= '".$array['desc']."',
		    	`img`		= '".$array['img']."'
		    WHERE id = %d
		  ", $id)){
		    return TRUE;
		  }
		}

		return FALSE;
	}

	public function addSlide()
	{
		$this->messageError  = $this->lang['ENTITY_SAVE_ERROR'];
		$this->messageSucces = $this->lang['ENTITY_SAVE_SUCCESS'];

		unset($_POST['pluginHandler']);

		if(!empty($_POST['id']))
		{
			$this->updateSlide($_POST);
		}
		else
		{
			unset($_POST['id']);
			if(isset($_POST['type']))
			{
				$_POST['sort'] = $this->getCountSlide();
				if($_POST['type'] == 'img')
				{
					$this->data['row']['type'] = $_POST['type'];
					unset($_POST['type']);
					if(DB::buildQuery('INSERT INTO `'.PREFIX.$this->pluginName.'` SET ', $_POST))
					{
						$this->data['row']['id']   = DB::insertId();
						$this->data['row']['sort'] = $_POST['sort'];
						$this->data['row']['img']  = SITE.'/'.PLUGIN_DIR.$this->pluginName.'/img/slides/'.$_POST['img'];
						return true;
					}
				}
				else if($_POST['type'] == 'desc')
				{
					$this->data['row']['type'] = $_POST['type'];
					unset($_POST['type']);
					if(DB::buildQuery('INSERT INTO `'.PREFIX.$this->pluginName.'` SET ', $_POST))
					{
						$this->data['row']['id']        = DB::insertId();
						$this->data['row']['name_link'] = $_POST['name_link'];
						$this->data['row']['url_link']  = $_POST['url_link'];
						$this->data['row']['desc']      = $_POST['desc'];
						$this->data['row']['sort'] 		= $_POST['sort'];
						$this->data['row']['img']       = SITE.'/'.PLUGIN_DIR.$this->pluginName.'/img/slides/'.$_POST['img'];
						return true;
					}
				}
			}
		}
	}

	public function visibleEntity()
	{
		$this->messageSucces = $this->lang['OPERATION_SUCCESS'];
		$this->messageError  = $this->lang['OPERATION_ERROR'];
		unset($_POST['pluginHandler']);
		$id = $_POST['id'];
		unset($_POST['id']);

		if(DB::query('UPDATE `'.PREFIX.$this->pluginName.'` SET '.DB::buildPartQuery($_POST).' WHERE id = '.$id.''))
			return true;

		return false;	
	}

	public function deleteEntity()
	{
		$this->messageSucces = $this->lang['ENTITY_DELETE_SUCCESS'];
		$this->messageError  = $this->lang['ENTITY_DELETE_ERROR'];

		if (DB::query('DELETE FROM `'.PREFIX.$this->pluginName.'` WHERE `id`= '.$_POST['id']))
			return true;
		return false;
	}
}