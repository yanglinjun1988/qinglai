<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*网站设置设置模型
*/
class Goodsclass_model extends MY_Model{
		public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_goods_type';
		$this->tbl_key = 'type_id';
		$this->tbl_ext = '';
	}
		
	/**
	 * @access public
	 * @param $pid 节点的id
	 * @param array 返回该节点的所有后代节点
	 */
	public function list_cate($pid = 0){
		#获取所有的记录
		$query = $this->db->get($this->tbl);
		$cates = $query->result_array();
		#对类别进行重组，并返回
		return $this->_tree($cates,$pid);
	}



	/**
	 *@access private
	 *@param $arr array 要遍历的数组
	 *@param $pid 节点的pid，默认为0，表示从顶级节点开始
	 *@param $level int 表示层级 默认为0
	 *@param array 排好序的所有后代节点
	 */
	private function _tree($arr,$pid = 0,$level = 0){
		static $tree = array(); #用于保存重组的结果,注意使用静态变量
		foreach ($arr as $v) {
			if ($v['parent_id'] == $pid){
				//说明找到了以$pid为父节点的子节点,将其保存
				$v['level'] = $level;
				$tree[] = $v;
				//然后以当前节点为父节点，继续找其后代节点
				$this->_tree($arr,$v['type_id'],$level + 1);
			}
		}

		return $tree;
	}
}