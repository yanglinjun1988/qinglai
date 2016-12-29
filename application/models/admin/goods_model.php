<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*网站设置设置模型
*/
class Goods_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_goods';
		$this->tbl_key = 'goods_id';
		$this->tbl_ext = 'wz_goods_type';
	}
	//前台显示获取某一件产品及用户信息
	public function get_good($goods_id){
		$sql = "select * from " .$this->tbl. " left join wz_user on wz_goods.goods_uid = wz_user.u_id where wz_goods.goods_id = " .$goods_id. " order by wz_goods.add_time desc";
	    $query = $this->db->query($sql);
		return $query->row_array();
	}
}