<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * classname:	MY_Loader
 * scope:		Public
 */

class MY_Loader extends CI_Loader {

	private $theme_path = "";
	private $theme 		= "";	
	
	public function __construct() {
		parent::__construct();
		
		$this->set_theme($this->theme);
	}	
	
	public function set_theme($theme = "") {
		$path			= "";
		$this->theme 	= $theme;

		if ($this->theme) {
			$this->theme_path 	= "_themes";
			$path 				= "views/{$this->theme_path}/{$this->theme}/";

			$this->_ci_view_paths = array(APPPATH . $path => TRUE);	
			$this->_ci_cached_vars = array("theme_path" => $path);
		}
		
		return $this;
	}		

}

/* End of file MY_Loader.php */
/* Location: ./application/core/MY_Loader.php */
