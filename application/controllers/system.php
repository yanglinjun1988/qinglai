<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
/*易创网络科技有限公司
 * 杨林军 QQ：810153135
*/

class System extends MY_Controller {

	public function __construct() {
        parent::__construct();    
		$this->load->model('Category_model','cate');	
		$this->load->model('tui_model', 'tui'); 
		$this->load->model('system_model', 'sys');
		$this->load->model('Userinfo_model','u');
    }
	//商品页显示
	public function index(){
		#定义数组变量	这一部分主要实现商品查找搜索功能
		$selectField = "s_id,s_title,s_money,s_codetype,s_img,s_codename,s_lang,s_sql,s_keyword,s_des,s_url,s_click,s_down";
		$sdata = array();
		#焦点图片获得
		$this->_d['jdt'] = $this->tui->O(array('tui_id'=>2));
		#接收搜索查询数组
		$postdata = $this->input->post();
		if (!empty($postdata['search_type2']))
		{
			if($postdata['search_type2'] == 1){
				$key = $postdata['search_key'];
			   $sdata['goods_name like '.'\'%' . $key . '%\''] = '';	
			}
			if($postdata['search_type2'] == 2){
				$key = $postdata['search_key'];
				$sdata['goods_uid'] = $key;
			}
		}
		if (!empty($postdata['switch_type']))
		{
			$key = $postdata['switch_type'];
			$sdata['type_id'] = $key;
		}
		if (!empty($postdata['sh_zt']))
		{
			$key = $postdata['sh_zt']-1;
			$sdata['goods_zt'] = $key;		
		}
		$list = $this->sys->L($sdata,$selectField,$this->_p['pagenumb'], ($this->_p['pagecur'] == 0) ? 0 : ($this->_p['pagecur']-1) * $this->_p['pagenumb'],'add_time','desc');
		$this->_p['pagecount'] = $this->input->post('pagecount');
		if (empty($this->_p['pagecount'])) 
		{
			$this->_p['pagecount'] = $this->sys->C($sdata);
		}
		$this->_d['page'] = eyPage($this->_p,$sdata);
		$this->_d['list'] = $list;
		$this->_d['pagecount'] = $this->_p['pagecount'];
		$this->load->view($this->_d['dir_qt'] . 'code/code_system',$this->_d);
	}
	
	//商品页显示
	public function cont($s_id=''){
		if($s_id != ''){
			if($this->sys->C(array('s_id'=>$s_id)) >0){
				$this->_d['system'] = $this->sys->O(array('s_id'=>$s_id),'*','add_time','desc');
				//打开点击次数加1
//				$this->_d['system']['click_count'] = $this->_d['goods']['click_count']+1;
//				$this->goods->M($this->_d['goods'],array('goods_id'=>$goods_id));
				#获取本系统的交易评价
//				$sdata = array();
//				$sdata['goods_id'] = $goods_id;
//				$osField = "order_sn,user_tel,order_time,u_pj";
//				$this->_d['order'] = $this->order->L($sdata,$osField,$this->_p['pagenumb'], ($this->_p['pagecur'] == 0) ? 0 : ($this->_p['pagecur']-1) * $this->_p['pagenumb'],'order_time','desc');
//				$this->_p['pagecount'] = $this->input->post('pagecount');
//				if (empty($this->_p['pagecount'])) 
//				{
//					$this->_p['pagecount'] = $this->order->C($sdata);
//				}
//				$this->_d['page'] = eyPage($this->_p,$sdata);
//				$this->_d['pagecount'] = $this->_p['pagecount'];
			}
			else{
				$data['url'] = site_url('/');
				echo tiao('这个系统已经下架！', $data['url']);
				exit();	
			}
		}else{
			echo tiaos('/');
			die();
		}
		$this->load->view($this->_d['dir_qt'] . 'code/cont_system',$this->_d);
	}
}