<?php
/**
 * Loader - UploadImage
 * @package com.atitudeweb
 * @version 1.0
 * @copyright atitudeweb
 * @license http://opensource.org/licenses/gpl-3.0.html GPL General Public License
 */

class UploadImage{
	
	private $filename;
	private $folder;
	private $whitelist_ext;
	private $whitelist_type;
	private $max_size;
	//private $real_path;
	private $hash;
	private $return;
	
	public function UploadImage($filename, $folder = '../uploads/', $whitelist_ext = array('jpeg','jpg'), $max_size = 6000000)
	{
		
		$this->filename = $filename;
		$this->folder = $folder . Config::TEMP_FOLDER_IMG . '/';
		$this->whitelist_ext = $whitelist_ext;		
		$this->whitelist_type = array();
		foreach($whitelist_ext as $key=>$ext){
			$this->whitelist_type[] = 'image/' . $ext;
		}
		$this->max_size = $max_size;
		//$this->real_path = $this->folder . $this->filename . '.jpg';	
		$this->hash = md5($filename . date('YmdHis'));
	}
	
	public function upload($file_field)
	{
		$this->return = array(	'path'	=>	$this->folder,
								'hash'	=>	$this->hash,
								'error'	=>	array());
		
		//Make sure that there is a file
		if((!empty($_FILES[$file_field])) && ($_FILES[$file_field]['error'] == 0)) {
			
			// Get filename
			$file_info = pathinfo($_FILES[$file_field]['name']);
			$name = $file_info['filename'];
			$ext = strtolower($file_info['extension']);
			
			//Check file has the right extension           
			if (!in_array($ext, $this->whitelist_ext)) {
				$this->return['error'][] = "Extensão inválida de arquivo!";
				return $this->return;
			}
			
			//Check that the file is of the right type
			if (!in_array($_FILES[$file_field]["type"], $this->whitelist_type)) {
				$this->return['error'][] = "Tipo de arquivo inválido!";
				return $this->return;
			}
			
			//Check that the file is not too big
			if ($_FILES[$file_field]["size"] > $this->max_size) {
				$this->return['error'][] = "Arquivo é muito grande!";
				return $this->return;
			}
			
			//If $check image is set as true
			if (!getimagesize($_FILES[$file_field]['tmp_name'])) {
				$this->return['error'][] = "Arquivo não é imagem válida!";
				return $this->return;
			}
			
			//echo getcwd();
			//exit;
			
			if (move_uploaded_file($_FILES[$file_field]['tmp_name'], $this->folder . $this->filename . '.jpg')) {
				
				$this->makeThumbnails($this->folder, 'jpg', $this->filename);
				return $this->return;
			} 
			else {
				$this->return['error'][] = "Erro de servidor!";
				return $this->return;
			}
			
		}
		else{
			$this->return['error'][] = "Erro de envio para o servidor!";
			return $this->return;
		}		
	}

	public function check() 
	{
		if (count($this->return['error']) > 0)
			return false;
		else
			return true;
	}
	
	function makeThumbnails($updir, $img, $id)
	{
		$thumbnail_width = 134;
		$thumbnail_height = 189;
		$thumb_beforeword = "thumb";
		$arr_image_details = getimagesize($updir.$id . '.' . "$img"); // pass id to thumb name
		$original_width = $arr_image_details[0];
		$original_height = $arr_image_details[1];
		if ($original_width > $original_height) {
			$new_width = $thumbnail_width;
			$new_height = intval($original_height * $new_width / $original_width);
		} else {
			$new_height = $thumbnail_height;
			$new_width = intval($original_width * $new_height / $original_height);
		}
		if ($arr_image_details[2] == IMAGETYPE_GIF) {
			$imgt = "ImageGIF";
			$imgcreatefrom = "ImageCreateFromGIF";
		}
		if ($arr_image_details[2] == IMAGETYPE_JPEG) {
			$imgt = "ImageJPEG";
			$imgcreatefrom = "ImageCreateFromJPEG";
		}
		if ($arr_image_details[2] == IMAGETYPE_PNG) {
			$imgt = "ImagePNG";
			$imgcreatefrom = "ImageCreateFromPNG";
		}
		if ($imgt) {
			$old_image = $imgcreatefrom($updir . $id . '.' . $img);
			$new_image = imagecreatetruecolor($new_width, $new_height);
			imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
			$imgt($new_image, $updir . $id . '_' . $thumb_beforeword . '.' . $img);
		}
	}
	
	
}
?>