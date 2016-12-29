<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*
*/

class Utuiorder_model extends MY_Model {

	function __construct()
    {
        parent::__construct();
		$this->tbl		= 'wz_usertuiorder';
		$this->tbl_key	= 'ot_id';
    }
	
	#用户中心首页获取用户的部分积分信息
	public function getordernum($u_id){
		$sql = "select SUM(ot_jf) as total from wz_usertuiorder where wz_usertuiorder.ot_uid=".$u_id;
		$query = $this->db->query($sql);
		return  $query->row_array();
	}

}
?>