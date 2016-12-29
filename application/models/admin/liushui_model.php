<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*后台订单模型控制器
*/
class Liushui_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_liushui';
		$this->tbl_key = 'l_id';
		$this->tbl_ext = '';
	}
		
	/**
	 * 获取会员各项积分明细总数
	 */
	 public function totaljfmx($userid){
	 	$sql = "SELECT
				wz_liushui.l_type AS type,
				SUM(l_jf) AS totaljf
				FROM 
				".$this->tbl." 
				WHERE
				wz_liushui.l_uid = ".$userid." 
				GROUP BY
				wz_liushui.l_type";
		$query = $this->db->query($sql);
		return $query->result_array();
	 }
}