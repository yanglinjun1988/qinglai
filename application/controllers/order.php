<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
/*易创网络科技有限公司
 * 杨林军 QQ：810153135
*/

class Order extends MY_Controller {

	public function __construct() {
        parent::__construct();    
		$this->load->model('goods_model', 'goods');
		$this->load->model('userinfo_model', 'u');
		$this->load->model('order_model', 'o');
		$this->load->model('liushui_model', 'ls'); 
    }
	//订单处理页面
	public function myorder($o_id=''){
		#判断用户是否登录，是否有产品订单单号
		if($o_id != '' && $this->islogin){
		  $goods = $this->goods->O(array('goods_id'=>$o_id),'*','','','type_id');
		  if($goods['goods_id'] == ''){
		  	echo tiao('你的操作有误', '/');
			exit();
		  }
		  if($this->_d['userinfo']['u_jifen'] < $goods['goods_price'])
		  {
		  	//$data['url'] = site_url('goods/index').'/'.$goods['goods_id'];
			echo tiao('你的积分不足！', '/');
			exit();
		  }
		  #判断用户是否已经兑换过此产品
		  if($this->o->C(array('goods_id'=>$o_id,'user_id'=>$this->_d['userinfo']['u_id'])) >0)
		  {
		  	 $myorder = $this->o->O(array('goods_id'=>$o_id));
		  	 $data['url'] = site_url('osearch/ordersearch').'/'.urlencode(hifun($myorder['order_sn'], 's'));
			  echo tiao('您已经兑换过本项目，直接进入取货界面!', $data['url']);
			  exit();
		  }
		  #判断这件产品的上传者
		  $goodsf_price = '';
		  if($goods['goods_uid'] != 0 || $goods['goods_uid'] != ''){
		  	$goods_uid = $goods['goods_uid'];
			$goodsf_price = floor($goods['goods_price'] * $this->_d['cfg']['all_scjf']);
		  }else{
		  	$goods_uid = 0;
		  }
		  #判断兑换这个产品者是否有推荐者
		  $tuiprice = '';
		  if($this->_d['userinfo']['u_whotui'] != 0 || $this->_d['userinfo']['u_whotui'] != ''){
		  	$whotui = $this->_d['userinfo']['u_whotui'];
			$tuiprice = floor($goods['goods_price'] * $this->_d['cfg']['all_sxj']);
		  }else{
		  	$whotui = 0;
		  }
		  
		  $myorder_sn = date("Ymdhis",time()).rand(1000,9999);
		  #完成订单的添加
		  $odata = array(
		  'user_id'=>$this->_d['userinfo']['u_id'],
		  'order_sn'=>$myorder_sn,
		  'user_tel'=>$this->_d['userinfo']['u_tel'],
		  'user_name'=>$this->_d['userinfo']['u_truename'],
		  'goods_id'=>$goods['goods_id'],
		  'goods_title'=>$goods['goods_name'],
		  'order_price'=>$goods['goods_price'],
		  'order_time'=>time(),
		  'order_zt'=>1,
		  'o_goods_uid'=>$goods_uid,
		  'o_goodsf_price'=>$goodsf_price,
		  'o_tuiuid'=>$whotui,
		  'o_tuiprice'=>$tuiprice
		  );
		  $insert_order = $this->o->A($odata);
		  if( $insert_order>0){
		  	 #完成用户积分的扣除，以及发货内容的显示
			  $udata = $this->u->O(array('u_id'=>$this->_d['userinfo']['u_id']));
			  $udata['u_jifen'] = $udata['u_jifen'] - $goods['goods_price'];
			  $this->setAuthor($udata);
			  $this->u->M($udata,array('u_id'=>$this->_d['userinfo']['u_id']));
			  #添加一条兑换流水
			  $liushui = array('l_type'=>"消费兑换",'l_time'=>time(),'l_jf'=>$goods['goods_price'],'l_name'=>$goods['goods_name'],'l_class'=>5,'l_uid'=>$udata['u_id']);
			  $this->ls->A($liushui);
			  #产品上传者获得相应的积分
			  if($goods_uid != 0){
			  	$scdata = $this->u->O(array('u_id'=>$goods_uid));
				$scdata['u_jifen'] = $scdata['u_jifen'] + $goodsf_price;
				$this->u->M($scdata,array('u_id'=>$goods_uid));
				#添加一条上传奖励流水
			  	$liushui = array('l_type'=>"上传课程兑换",'l_time'=>time(),'l_jf'=>$goodsf_price,'l_name'=>$goods['goods_name'],'l_class'=>3,'l_uid'=>$scdata['u_id']);
			  	$this->ls->A($liushui);
			  }
			  #推广这个用户的人获得相应的奖励积分
			  if($whotui != 0){
			  	$tuidata = $this->u->O(array('u_id'=>$whotui));
				$tuidata['u_jifen'] = $tuidata['u_jifen'] + $tuiprice;
				$this->u->M($tuidata,array('u_id'=>$whotui));
				#添加一条推广会员奖励流水
			  	$liushui = array('l_type'=>"推广会员兑换",'l_time'=>time(),'l_jf'=>$tuiprice,'l_name'=>$goods['goods_name'],'l_class'=>4,'l_uid'=>$tuidata['u_id']);
			  	$this->ls->A($liushui);
			  }
			  #产品下载次数加1
			  $goods['down_count'] = $goods['down_count']+1;
			  $this->goods->M($goods,array('goods_id'=>$o_id));
			  $data['url'] = site_url('osearch/ordersearch').'/'.urlencode(hifun($myorder_sn, 's'));
			  echo tiao('兑换成功，现在取货!', $data['url']);
			  exit();
		  }
		 
		}
		else{
			$data['url'] = site_url('/');
			echo tiao('系统未检测到您登录信息，登录以后方可进行课程兑换！', $data['url']);
			exit();
		}
	}
}