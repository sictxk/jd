<?php

define('UPLOADFILEMAXSIZE', 1024*100);//100M
define('ACCEPTOVERWRITE', true);

class FileManage{
	// uploading file's result dir
	var $upload_path;

	// uploading file max size (bytes)
	var $file_size_max;

	// agreed covered or not
	var $accept_overwrite;

	// uploading file
	var $uploadfile;

	var $file_ext;
	
	// error messages
	var $errormsg;

	/**
	 * constructor
	 * @param $file_info  $_FILE['']
	 * @param 
	 */
	
	function FileManage( /* array */ $file_info = array(), /* string */ $upload_path= './'){

		$this->uploadfile  = $file_info;

		$this->upload_path = $upload_path;
		$this->upload_path 		.='/'.date('Ym');
		//$this->upload_path = ereg_replace("\\\\", "/", $this->upload_path);
		$this->upload_path = rtrim( preg_replace( array("/\\\\/", "/\/{2,}/"), "/", $this->upload_path ), "/" );
		
		if ( substr($this->upload_path, -1,1) !== '/' ) {
			$this->upload_path .= '/';
		}
		$this->file_size_max    = UPLOADFILEMAXSIZE;
		$this->accept_overwrite = ACCEPTOVERWRITE;
	}
	
	/**
	 * check before upload
	 * @param null
	 * @return bollen true or fase 
	 */
	
	function check(){
		if ( !$this->uploadfile ) return false;
		// get file information
		$uploadfile_tmp_name = $this->uploadfile['tmp_name'];
		$uploadfile_name     = $this->uploadfile['name'];
		$uploadfile_type     = $this->uploadfile['type'];
		$uploadfile_size     = $this->uploadfile['size'];
		
		$this->set_file_ext($uploadfile_name);
		
		// judging file size
		if ($uploadfile_size/1024  > $this->file_size_max){
			$this->errormsg = "※ ".'Sorry,the uploading file is too big, bigger than '. UPLOADFILEMAXSIZE/1024 . 'M';
			return false;
		}
		
		// file exists or not
		if (file_exists($uploadfile_tmp_name.$uploadfile_name) && !$this->accept_overwrite) {
			$this->errormsg = "※ ". 'There is the same file name already.';
			return false;
		}
		
		// upload_path exists or not, make dir when no exist
		if(!is_dir($this->upload_path)){
			mkdir($this->upload_path, 0777, true);
		}
		
		//check forbidden
		if ( !is_writeable ( $this->upload_path ) ){
			$this->errormsg = "※ ".'write forbidden.';
			return false;
		}
		return true;
	}
	
	
	/**
	 * upload 
	 * @param @rename
	 * @return bollen true or fase 
	 */
	function upload( $rename = '' ){
		if ( !$this->check() ) return false;
		
		//get file information
		$uploadfile_tmp_name = $this->uploadfile['tmp_name'];
		$uploadfile_name     = $this->set_name($rename);

		$this->save_path = $this->upload_path. $uploadfile_name;
		
		//echo $uploadfile_tmp_name;
		if (!move_uploaded_file($uploadfile_tmp_name, $this->save_path)) {
			$this->errormsg    = 'Upload file to  path error.';
			return false;
		}
		chmod($this->save_path, 0777);
		return true;
	}

	/**
	 * delete file  
	 * @param null
	 * @return bollen 
	 */
	function delete( $path ){
		if ( !$path || !file_exists ($path) ) {
			return false;
		}
		@unlink($path);
		return true;
	}
	
	/**
	 * download file  
	 * @param null
	 * @return bollen 
	 */
	function download( /* string */ $path, $rename = true){

		// file exists or not
		if (!file_exists($path)){
			$this->errormsg = 'downing load file is not exist';
			return false;
		}else{
			$this->set_file_ext($path);
			if ( $rename ) { 
				$file_name = date("YmdHis").uniqid(). '.'. $this->file_ext;
			} else {
				$file_name = basename($path);
			}
			//https cache control
			header("Cache-Control: public");
			header("Pragma: public");
			
			header("Content-type: application/octet-stream\n");
			header("Content-Disposition: attachment; filename=$file_name\n");
			header("Content-length: ".(string)(filesize($path)));
			readfile($path);
			return true;
		}
	}
	
	/**
	 * set the  file extends name
	 * 
	 * @param string $file_name
	 * @return string 
	 */
	function set_file_ext( $file_name ) {
		if ( !$file_name ) return false;
		$position = strripos($file_name,".");
		$this->file_ext = substr($file_name, $position + 1);
	}
	
	
	/**
	 * get the  file extends name
	 * 
	 * @return string 
	 */
	function get_file_ext() {
		return $this->file_ext;
	}
	
	/**
	 * get the  file path
	 * 
	 * @return string 
	 */
	function get_file_path() {
		return $this->save_path;
	}
	
	/**
	 * get the  file size
	 * 
	 * @return string 
	 */
	function get_file_size() {
		return $this->uploadfile['size']/1024; //KB
	}
	
	/**
	 * set the name of upload file
	 * 
	 * @param string $prefix
	 * @param string $rename
	 * @return string $save_name
	 */
	function set_name(  $rename = '', $prefix = '') {
		if ( empty($rename) ) {
			$save_name = $prefix . date("YmdHis").uniqid(). '.'. $this->file_ext;
		} else {
			if ( strripos($rename, ".") ) {
				$save_name = $rename;
			} else {
				$save_name = $rename. '.'. $this->file_ext;
			}
		}
		return $save_name;
	}


	/**
	 * get error message
	 * 
	 * @return error message
	 */
	function copy_error(){
		return $this->errormsg;
	}
}
?>