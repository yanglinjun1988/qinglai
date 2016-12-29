<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*网站设置设置模型
*/
class Attr_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_type_attr';
		$this->tbl_key = 'attr_id';
		$this->tbl_ext = '';
	}
}