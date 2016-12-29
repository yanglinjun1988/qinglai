<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*后台订单模型控制器
*/
class Order_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_order';
		$this->tbl_key = 'order_id';
		$this->tbl_ext = '';
	}
	#获取用户购买的订单和商品信息
	public function getmyorder($order_sn=''){
		$sql = "select * from wz_order left join wz_goods on wz_order.goods_id= wz_goods.goods_id where wz_order.order_sn =".$order_sn;
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	/**
	 * 获取会员发布分总数
	 */
	 public function DDDfb($userid){
	 	$sql = "SELECT
				SUM(o_goodsf_price) AS fb 
				FROM ".$this->tbl." WHERE wz_order.o_goods_uid = ".$userid;
		$query = $this->db->query($sql);
		return $query->row_array();
	 }
	 
	 /**
	 * 获取推广会员兑换我获得奖励积分总数
	 */
	 public function DDDtuiuser($userid){
	 	$sql = "SELECT
				SUM(o_tuiprice) AS tui 
				FROM ".$this->tbl." WHERE wz_order.o_tuiuid = ".$userid;
		$query = $this->db->query($sql);
		return $query->row_array();
	 }
}