<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*网站设置设置模型
*/
class System_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_system';
		$this->tbl_key = 's_id';
		$this->tbl_ext = '';
	}

}