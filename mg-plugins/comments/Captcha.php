<?php

/**
 * Библиотека для работы с каптчей
 * @author  Румянцев Олег
 * @version 0.1
 * Create: 23.07.13
 * Last.Update: 23.07.13
 */

class Captcha
{
	public  $width  = 120;
	public  $height = 35;
	public  $font   = 'droid_sans';
	public  $type   = 'jpg';
	
	private $img;
	private $count_line = 0;
	private $count_word = 4;
	private $fonts_dir  = 'fonts';
	private $save_dir   = 'c_img';
	private $str;
	private $link;
	
	private $black;
	private $background;

	/**
	 * @method Входной метод
	 * @return ссылку на каптчю
	 */
	
	public function get_captcha()
	{
		if (($this->type == 'jpg') OR ($this->type == 'jpeg') OR ($this->type == 'png'))
		{
			$this->img = imagecreatetruecolor($this->width, $this->height);
			$this->init();
			
			$name_file  = md5(time().$this->str);									
			$this->link = $this->way_lib('captcha', $this->save_dir.'/'.$name_file.'.'.$this->type);
			
			switch ($this->type)
			{
				case 'jpg' :
					imagejpeg($this->img, $this->link);
					break;
				case 'jpeg':
					imagejpeg($this->img, $this->link);
					break;
				case 'png' :
					imagepng($this->img,  $this->link);
					break;
			}

			# Удаление старых файлов
			$dir = opendir($this->way_lib('captcha', $this->save_dir));
			$img = array();
			while (FALSE !== ($file = readdir($dir)))
			{
				if ($file == 'Thumbs.db' OR $file == '..' OR $file == '.' OR $file == '.DS_Store') continue;							
				$img[] = array('time' => filemtime($this->way_lib('captcha', $this->save_dir.'/'.$file)), 'name' => $file);
			}
			
			# Если файлов больше или равно 20 то мы удаляем первые(старые) 10 файлов
			if (count($img) >= 20)
			{
				sort($img);
				for ($i = 0; $i <= 10; $i++)
				{					
					if (file_exists($this->way_lib('captcha', $this->save_dir.'/'.$img[$i]['name']))) unlink($this->way_lib('captcha', $this->save_dir.'/'.$img[$i]['name']));
				}	
			}	
		}
		$_SESSION['captcha'] = $this->str;
		return $this->link;
	}
		
	private function init()
	{
		$this->set_color();
		$this->render_line();
		$this->generate_str();
		$this->render_word();
		
		$this->save_dir  = trim($this->save_dir, '/');
		$this->fonts_dir = trim($this->fonts_dir, '/');
	}
	
	private function set_color()
	{	
		$this->black      = imagecolorallocate($this->img, 0, 0, 0);
		$this->background = imagecolorallocate($this->img, 255, 255, 255);
		imagefilledrectangle($this->img, 0, 0, $this->width, $this->height, $this->background);		
	}
	
	private function generate_str()
	{
		$str 		= '23456789abcdegikpqsvxyz';
		$str_len    = strlen($str) - 1;
		$str_gen    = '';
		
		for ($i = 0; $i < $this->count_word; $i++)
		{
			$word = mt_rand(0, $str_len);
			$str_gen .= $str[$word];
		}
		$this->str = $str_gen;
	}
	
	private function render_line()
	{
		for ($i = 0; $i < $this->count_line; $i++)
		{
			$x1 = mt_rand(4, $this->width - 10);
			$y1 = mt_rand(4, $this->height - 10);
		
			$x2 = mt_rand(4, $this->width - 10);
			$y2 = mt_rand(4, $this->height - 10);
		
			$border = mt_rand(1, 3);
		
			imagesetthickness($this->img, $border);
			imageline($this->img, $x1, $y1, $x2, $y2, $this->black);
		}	
	}

	private function render_word()
	{
		$x = 10;
		for ($i = 0; $i < $this->count_word; $i++)
		{
			$size  = mt_rand(14, 22);
			$angle = mt_rand(-10, 20);
			$y     = mt_rand(20, 25);			
			$font  = $this->way_lib('captcha', $this->fonts_dir.'/'.$this->font.'.ttf');	
			
			$r     = mt_rand(100, 255);
			$g     = mt_rand(100, 255);
			$b     = mt_rand(100, 255);
			$color_str  = imagecolorallocate($this->img, $r, $g, $b);
		
			imagettftext($this->img, $size, $angle, $x, $y, $color_str, $font, $this->str[$i]);
			$x += $size;
		}	
	}

	private function way_lib($lib, $path = NULL)
	{
		$url = 'mg-plugins/comments/';
		if ($path)
		{
			return $url .= $path;
		} 
		else 
		{
			return $url;
		}
	}
}