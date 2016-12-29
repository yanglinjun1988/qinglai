<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
/*易创网络科技有限公司
 * 杨林军 QQ：810153135
*/

class Helper extends MY_Controller {

	public function __construct() {
        parent::__construct();    
		$this->load->model('actclass_model', 'act');
		$this->load->model('act_model', 'a');
    }
	//帮助页主页显示
	public function index($act_id='1'){
		#先获取文章所有分类
		$menu= array();
		$menu = $this->act->L(); 
		#获取文章分类下的所有文章，组建新的数组
		foreach($menu as $k => $v){
			$menu[$k]['child'] = $this->a->L(array('aclass_id'=>$v['aclass_id']),'act_id,act_title');
		}
		$this->_d['hmenu'] = $menu;
		
		#获取文章内容
		$this->_d['act'] = $this->a->O(array('act_id'=>$act_id));
		
		//var_dump($this->_d);
		$this->load->view($this->_d['dir_qt'] . 'help_info.html',$this->_d);
	}
}