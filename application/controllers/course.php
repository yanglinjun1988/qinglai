<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
/*易创网络科技有限公司
 * 杨林军 QQ：810153135
*/

class Course extends MY_Controller {

	public function __construct() {
        parent::__construct();    
		$this->load->model('goods_model', 'goods');
		$this->load->model('tags_model', 'tags');
    }
	//商品页显示
	public function index($type_id = 'ss',$order = 'add_time'){
		$selectField = "goods_id,goods_name,goods_price,goods_thumb,goods_img,click_count,down_count";
		$this->_d['goods_tj'] = $this->goods->L(array('is_best'=>1,'goods_zt'=>1),$selectField,$limit = 3, $offset = 0,'add_time','desc','type_id');
		$this->_d['goods_hot'] = $this->goods->L(array('is_hot'=>1,'goods_zt'=>1),$selectField,$limit = 3, $offset = 0,'add_time','desc','type_id');
		$this->_d['tags_hot'] = $this->tags->L(array(),'*',$limit = 20, $offset = 0,'tags_num','desc','type_id');
		
		#获取所有商品列表
		$sdata = array();
		$this->_d['tags_count'] = $this->tags->C($sdata);
		$this->_d['f_menu'] = 'ss';
		$this->_d['f_title'] = "全部课程";
		if($type_id != 'ss'){
			$sdata['type_id'] = $type_id;
			$this->_d['f_menu'] = $type_id;
			$this->_d['f_title'] = $this->_d['menu'][$type_id-1]['type_name'];
		}
		#热门标签
		if($this->input->get('tags',TRUE)!=''){
			$key = $this->input->get('tags');
			$sdata['tags like '.'\'%' . $key . '%\''] = '';
		}
		#排序标记
		if($order == 'add_time'){$this->_d['p_paixu'] = 0;}
		if($order == 'down_count'){$this->_d['p_paixu'] = 1;}
		if($order == 'click_count'){$this->_d['p_paixu'] = 2;}
		if($order == 'goods_price'){$this->_d['p_paixu'] = 3;}
		$key = '';
		$search_bjf = '';
		$search_ejf = '';
		$postdata = $this->input->post();
		if (!empty($postdata['search_key']))
		{
			$key = $postdata['search_key'];
			$sdata['goods_name like '.'\'%' . $key . '%\''] = '';
		}
		if (!empty($postdata['search_bjf']))
		{
			$search_bjf = $postdata['search_bjf'];
			$sdata['goods_price >='.$search_bjf] = '';
		}
		if (!empty($postdata['search_ejf']))
		{
			$search_ejf = $postdata['search_ejf'];
			$sdata['goods_price <='.$search_ejf] = '';
		}
		$sdata['goods_zt'] = 1;
		$this->_d['list'] = $this->goods->L($sdata,$selectField,$this->_p['pagenumb'], ($this->_p['pagecur'] == 0) ? 0 : ($this->_p['pagecur']-1) * $this->_p['pagenumb'],$order,'desc','type_id');
		$this->_p['pagecount'] = $this->input->post('pagecount');
		if (empty($this->_p['pagecount'])) 
		{
			$this->_p['pagecount'] = $this->goods->C($sdata);
		}
		$this->_d['page'] = eyPage($this->_p,$sdata);
		$this->_d['pagecount'] = $this->_p['pagecount'];
		//var_dump($this->_d);
		$this->load->view($this->_d['dir_qt'] . 'course/list.html',$this->_d);
	}
}