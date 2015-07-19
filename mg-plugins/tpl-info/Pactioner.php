<?php
class Pactioner extends Actioner 
{
	private $pluginName = 'tpl-info';

	public function editSettings()
	{
		$this->messageSucces = 'Настройки успешно обновлены';
		if (!empty($_POST['options'])) {
		  foreach ($_POST['options'] as $option => $value) {
		    if (!DB::query("UPDATE `".PREFIX."tpl-info` SET `value`='%s' Where `option`='%s'", $value, $option)) {
		      return false;
		    }
		  }

		  return true;
		}
	}

	private function checkImg()
	{
		$nameFile  = $_FILES['photoimg']['name'];
		$whileList = array('.jpeg', '.jpg', '.png');
		foreach($whileList as $item)
		{
			if (preg_match("/$item\$/i", $nameFile))
			{
				if(getimagesize($_FILES['photoimg']['tmp_name']))
				{
					return $item;
				}
			}
		}
	}

	public function updateBanner()
	{
		$this->messageSucces = 'Изображение успешно загруженно';
		$this->messageError  = 'Произошла ошибка при загрузке изображения';

		if(isset($_POST))
		{
			if($ext = $this->checkImg($_FILES))
			{
				$gen_name = md5(mt_rand(100, 10000).time());
				$new_name = $gen_name.$ext;

				$savePath = PLUGIN_DIR.$this->pluginName.'/img/'.$new_name;
				if (move_uploaded_file($_FILES['photoimg']['tmp_name'], $savePath))
				{
					$this->data['photoimg'] = $new_name;
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
}