<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*网站设置设置模型
*/
class Goods_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'ims_ewei_shop_goods';
		$this->tbl_key = 'id';
	}

}