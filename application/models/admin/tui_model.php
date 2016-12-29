<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*后台订单模型控制器
*/
class Tui_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_tui';
		$this->tbl_key = 'tui_id';
		$this->tbl_ext = '';
	}
}