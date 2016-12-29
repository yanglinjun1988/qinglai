<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
*杨林军
 * 用户前台总控制器
*/

class Main extends MY_Controller {

	public function __construct() {
        parent::__construct();   
//		$this->load->model('goods_model', 'goods');
	
    }
	//后台显示页面
	public function index(){
		echo "hello word";
		//var_dump($this->_d['list']);
//		$this->load->view($this->_d['dir_qt'] . 'index.html',$this->_d);
	}
	

}