<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
*杨林军
 * 支付控制器
*/

class Pay extends MY_Controller {
	//支付配置
	public $shan_config = array();	

	public function __construct() {
        parent::__construct();  
		if(!$this->islogin){
			echo tiao('系统未检测到你的登录信息，请先登录哦。','/');
			die();
		} 
		$this->load->model('userinfo_model', 'u');
		$this->load->model('yunpay_model', 'yun');
		$this->load->model('czorder_model', 'czo');
		$this->load->model('liushui_model', 'ls');
		$this->load->helper('shanpayfunction');
	   
	   $mconfig = $this->yun->O(array('yun_id'=>1));
		//合作身份者PID，签约账号，由16位纯数字组成的字符串，请登录商户后台查看
		$this->shan_config['partner']		= $mconfig['yun_pid'];;	
		// MD5密钥，安全检验码，由数字和字母组成的32位字符串，请登录商户后台查看
		$this->shan_config['key']			= $mconfig['yun_key'];;
		//商户号（6位数字）
		$this->shan_config['user_seller'] = $mconfig['yun_name'];
		// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
		$this->shan_config['notify_url'] = site_url('pay/notify_url');
		// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
		$this->shan_config['return_url'] = site_url('pay/return_url');;
    }
	//后台显示页面
	public function index(){
		var_dump($this->shan_config);
		$this->load->view('payapi/index',$this->_d);
	}
	
	#支付函数
	public function shanpay(){
		
		/**************************请求参数**************************/
        //商户订单号
        if($this->input->post()){
         $out_order_no = date("Ymdhis",time()).rand(1000,9999);
         $subject = $this->input->post('WIDsubject');
		 $total_fee = $this->input->post('WIDtotal_fee');
		 $body = $this->input->post('WIDbody');
		 
		 #完成充值订单的添加
		  $czdata = array(
		  'o_sn'=>$out_order_no,
		  'o_name'=>$subject,
		  'o_price'=>$total_fee,
		  'o_desc'=>$body,
		  'o_uid'=>$this->_d['userinfo']['u_id'],
		  'o_uname'=>$this->_d['userinfo']['u_truename'],
		  'o_utel'=>$this->_d['userinfo']['u_tel'],
		  'o_zt'=>0,
		  'o_time'=>time()
		  );
		  $insert_czorder = $this->czo->A($czdata);
		
		/************************************************************/
		//构造要请求的参数数组，无需改动
		if( $insert_czorder>0){
			$parameter = array(
					"partner" => $this->shan_config['partner'],
			         "user_seller"  => $this->shan_config['user_seller'],
					"out_order_no"	=> $out_order_no,
					"subject"	=> $subject,
					"total_fee"	=> $total_fee,
					"body"	=> $body,
					"notify_url"	=> $this->shan_config['notify_url'],
					"return_url"	=> $this->shan_config['return_url']
			);
			//建立请求
		
			$html_text = buildRequestFormShan($parameter, $this->shan_config['key']);
			echo $html_text;
		}
		else{
			$data['url'] = site_url('user/order/czlist');
			echo tiao('支付支付订单提交失败！', $data['url']);
			exit();
		}
		//var_dump($this->shan_config);
		//$this->load->view('payapi/shanpay',$this->_d);
		}
	}
	
	#同步回调函数
	public function return_url(){
		//计算得出通知验证结果
		$shanNotify = md5VerifyShan($_REQUEST['out_order_no'],$_REQUEST['total_fee'],$_REQUEST['trade_status'],$_REQUEST['sign'],$this->shan_config['key'],$this->shan_config['partner']);
		if($shanNotify) {//验证成功
			if($_REQUEST['trade_status']=='TRADE_SUCCESS'){
				    /*
					加入您的入库及判断代码;
					判断返回金额与实金额是否想同;
					判断订单当前状态;
					完成以上才视为支付成功
					*/
					//商户订单号
					$out_trade_no = $_REQUEST['out_order_no'];
					//云通付交易号
					$trade_no = $_REQUEST['trade_no'];
					//价格
					$price=$_REQUEST['total_fee'];
					$get_order = $this->czo->O(array('o_sn'=>$out_trade_no));
					if(($get_order['o_id'] != '') && ($get_order['o_price'] == $price)){
						$czdata = array('o_zt'=>1,'o_paytime'=>time(),'trade_no'=>$trade_no);
						$this->czo->M($czdata,array('o_sn'=>$out_trade_no));
						#兑换成相应的积分
						 $udata = $this->u->O(array('u_id'=>$this->_d['userinfo']['u_id']));
						 $udata['u_jifen'] = $udata['u_jifen'] + $price*10;
						 $this->setAuthor($udata);
						 $this->u->M($udata,array('u_id'=>$this->_d['userinfo']['u_id']));
						  #添加一条积分充值流水
						  $liushui = array('l_type'=>"积分充值",'l_time'=>time(),'l_jf'=>$price*10,'l_name'=>'用户积分充值','l_class'=>6,'l_uid'=>$udata['u_id']);
						  $this->ls->A($liushui);
					}
					//var_dump($_REQUEST);
					//echo "支付成功";
					$data['url'] = site_url('user/order/czlist');
					echo tiao('充值成功！', $data['url']);
				}else{
					//echo "支付失败";
					$data['url'] = site_url('user/order/czlist');
					echo tiao('充值失败！', $data['url']);
				}
		
		}
		else {
		    //验证失败
		    echo "验证失败";
		}
		//$this->load->view('payapi/return_url',$this->_d);
	}
	
	#异步回调函数
	public function notify_url(){
		//计算得出通知验证结果
		$shanNotify = md5VerifyShan($_REQUEST['out_order_no'],$_REQUEST['total_fee'],$_REQUEST['trade_status'],$_REQUEST['sign'],$this->shan_config['key'],$this->shan_config['partner']);
		if($shanNotify) {//验证成功
			if($_REQUEST['trade_status']=='TRADE_SUCCESS'){
				    /*
					加入您的入库及判断代码;
					判断返回金额与实金额是否想同;
					判断订单当前状态;
					完成以上才视为支付成功
					*/
					//商户订单号
					$out_trade_no = $_REQUEST['out_order_no'];
					//云通付交易号
					$trade_no = $_REQUEST['trade_no'];
					//价格
					$price=$_REQUEST['total_fee'];
					// var_dump($_REQUEST);
					$get_order = $this->czo->O(array('o_sn'=>$out_trade_no));
					if(($get_order['o_id'] != '') && ($get_order['o_price'] == $price)){
						$czdata = array('o_zt'=>1,'o_paytime'=>time(),'trade_no'=>$trade_no);
						$this->czo->M($czdata,array('o_sn'=>$out_trade_no));
						#兑换成相应的积分
						 $udata = $this->u->O(array('u_id'=>$this->_d['userinfo']['u_id']));
						 $udata['u_jifen'] = $udata['u_jifen'] + $price*10;
						 $this->setAuthor($udata);
						 $this->u->M($udata,array('u_id'=>$this->_d['userinfo']['u_id']));
						 #添加一条积分充值流水
						  $liushui = array('l_type'=>"积分充值",'l_time'=>time(),'l_jf'=>$price*10,'l_name'=>'用户积分充值','l_class'=>6,'l_uid'=>$udata['u_id']);
						  $this->ls->A($liushui);
					}
				}
				echo 'success';
		
		}else {
		   //验证失败
		    echo "fail";//请不要修改或删除
		}
		//$this->load->view('payapi/notify_url',$this->_d);
	}
	

}