<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*网站设置设置模型
*/
class Yunpay_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_yunpay';
		$this->tbl_key = 'yun_id';
		$this->tbl_ext = '';
	}
}