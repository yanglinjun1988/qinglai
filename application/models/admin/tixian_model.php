<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*后台订单模型控制器
*/
class Tixian_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_tx';
		$this->tbl_key = 'tx_id';
		$this->tbl_ext = '';
	}
}