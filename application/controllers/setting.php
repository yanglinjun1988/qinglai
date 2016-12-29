<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ============================================================================
*杨林军
 * 用户后台登录控制器
*/

class Setting extends MY_Controller {

	public function __construct() {
        parent::__construct(); 
		$this->load->model('Userinfo_model', 'u');   
    }
	#展示登录页面
	public function login(){
		//$this->load->view('');
		$this->load->view('kaihujiaoyi/shipanzhuce/index.html', $this->_d);
		//var_dump($this->_d);
	}
	
	#用户注册
	public function reg(){
		$postdata = $this->input->post();
		$postdata['user_regtime'] = time();
		$postdata['user_ip'] = ip2long($this->input->ip_address());
		if ($this->u->A($postdata) > 0)
		{
//			$retmsg['code'] = '1';
//			$retmsg['msg'] = $this->lang->line('reg_user_success');
//			exit(json_encode($retmsg));
	        $data['url'] = 'http://www.yr184.com/';
			echo tiao('注册成功，后台正在维护，维护完成可直接通过手机号登录', $data['url']);
			exit();
		}
		else
		{
//			$retmsg['code'] = '0';
//			$retmsg['msg'] = $this->lang->line('reg_user_fail');
//			exit(json_encode($retmsg));
	        $data['url'] = 'http://www.yr184.com/kaihujiaoyi/reg/index.html';
			echo tiao('注册失败，请从新注册', $data['url']);
			exit();
		}
	}
	#获取短信验证码
	public function get_yzm(){
		$code=generate_code();	
		$phone = $this->input->post('tel');
		$data = array(
		    'status' => "0",
			'code' =>$code
		);
		//判断过来的用户名和手机是否已经注册
		if($this->u->C(array('user_tel'=>$phone))>0){
			$data['status']='2';
		}else{
		   $data['status']='1';
		   $this->session->set_userdata('dx_yzm', $code);
		   //SendSMS($phone,$code);
		}
      // $data['status']='1';
		echo json_encode($data);

	}
	#检测输入验证码 是否正确
	public function check_yzm(){
	   $gt_yzm = $this->input->post('verify');
	   if($gt_yzm == $this->session->userdata('dx_yzm')){
	   	echo '1';
	   }else{
	   	echo '2';
	   }
	}
}