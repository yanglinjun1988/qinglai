<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
/*易创网络科技有限公司
 * 杨林军 QQ：810153135
*/

class Osearch extends MY_Controller {

	public function __construct() {
        parent::__construct();    
		$this->load->model('goods_model', 'goods');
		$this->load->model('userinfo_model', 'u');
		$this->load->model('order_model', 'o');
		$this->load->model('ad_model', 'ad');
    }
	//订单处理页面
	public function ordersearch($order_id=''){
	   //接收处理订单号
	   $order_sn = hifun(urldecode($order_id), 'D');
	   //echo $order_sn;
	   $this->_d['myorder'] = $this->o->getmyorder($order_sn);
	   $this->_d['my_ad'] = $this->ad->O(array('ad_id'=>1));
	   //$ceshi1= $this->o->O(array('order_sn'=>$order_sn), '*', '', 'desc', 'goods_id');
	   $this->load->view($this->_d['dir_qt'] . 'order_info.html',$this->_d);
	}
}