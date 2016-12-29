<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
/*易创网络科技有限公司
 * 杨林军 QQ：810153135
*/

class Goods extends MY_Controller {

	public function __construct() {
        parent::__construct();    
		$this->load->model('goods_model', 'goods');
    }
	//商品页显示
	public function index(){
	
	
		$data = $this->goods->L();
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	

	}
}