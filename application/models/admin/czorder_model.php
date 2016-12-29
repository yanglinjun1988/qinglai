<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*后台订单模型控制器
*/
class Czorder_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_czorder';
		$this->tbl_key = 'o_id';
		$this->tbl_ext = '';
	}

}