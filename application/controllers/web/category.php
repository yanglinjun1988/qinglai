<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*/
/**
* 分类管理功能
*/

class Category extends MY_Controller{

	
	var $_data = array();



	public function __construct() {
		parent::__construct();
		$this->load->model('Category_model','c');		
		$this->load->library('form_validation');
		//if ($this->isAdmin() == false) redirect("admin/login");
	}
	
	public function index($func = '')
	{
		$this->tlist($func = '');
	}



	public function tlist($func = '',$alias='')
	{
	
		$search = array();
		if (!empty($alias) && $alias != 0) $search['alias'] = $alias;
		$data = $this->c->getCateData($func,$search);
		//$this->_d['list'] = cate2list(0, $data);
		$this->_d['func'] = $func;
		$this->_d['alias'] = $alias;
		

		//var_dump($data);
		//判断是否为GET请求
		if(isGet()){
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
		}
		//var_dump($this->_d);
//		$admininfo = $this->isAdmin();	
//		var_dump($admininfo);
		//$this->load->view($this->_d['dir_admin']  . 'category/list', $this->_d);
	}

	public function searchCate()
	{
		$postdata = $this->input->post();
		$data = $this->c->getCateData('',$postdata);
		//print_r($list);
		//echo $this->db->last_query();
		$this->_d['list'] = cate2list(0, $data);
		$this->load->view($this->_d['dir_admin'] . 'category/list', $this->_d);
	}

	public function add($func = '',$alias='')
	{
		$postdata = $this->input->post();
		if ($this->c->A($postdata) > 0)
		{
			$retmsg['code'] = 'success';
			exit(json_encode($retmsg));
		}else{
			$retmsg['code'] = 'fail';
			exit(json_encode($retmsg));
		}
		
	}

	public function modi($cateid, $func='',$alias='')
	{
//		if (!$this->admin_priv($func))
//		{
//			show_error("您没有权限进行此操作！");
//		}
		if ($this->form_validation->run('category/add') == false)
		{
			if (validation_errors() != '')
			{
				$retmsg['code'] = '0';
				$retmsg['msg'] = validation_errors();
				exit(json_encode($retmsg));
			}

			$row = $this->c->O(array('cateid'=>$cateid));
			$this->_d['act'] = 'modi';
			$this->_d['row'] = $row;
			if (!empty($alias)) $search['alias'] = $alias;
			else $search= array();
			$catedata =  cate2list(0, $this->c->getCateData($func,$search));		
			$this->_d['parentcate'] = array2option($catedata, $row['parentid'], 1);			
			$this->_d['func'] = $func;
			$this->load->view($this->_d['dir_admin']  . 'category/editCategory', $this->_d);
		}
		else
		{
			$postdata = $this->input->post();
			$postdata['mtime'] = time();
			if ($this->c->M($postdata, array('cateid'=>$cateid)) > 0)
			{
				// modi by dgt 2014-1-11
				//if ($postdata['func'] == 'menu') $this->updateMenu('menu');
				if ($postdata['func'] == 'menu') $this->delCache('menu');

//				$admininfo = $this->isAdmin();
//				$this->action_log('1',$admininfo['user_id'],"编辑分类",$postdata['catename']);
				$retmsg['code'] = '1';
				$retmsg['msg'] = $this->lang->line('comm_sucess_tip');
				exit(json_encode($retmsg));
			}
			else
			{
				$retmsg['code'] = '0';
				$retmsg['msg'] = $this->lang->line('comm_fail_tip');
				exit(json_encode($retmsg));
			}
		}

	}

	public function del($cateid,$func='')
	{
//		if (!$this->admin_priv($func))
//		{
//			show_error("您没有权限进行此操作！");
//		}
		if ($cateid == '') exit($this->lang->line('access_error'));
		if ($this->hasChild($cateid))
		{
				$retmsg['code'] = '0';
				$retmsg['msg'] = $this->lang->line('comm_fail_tip');
				exit(json_encode($retmsg));
		}
		else
		{
			$sdata['cateid'] = $cateid;
			$adinfo = $this->c->O($sdata);			
			$this->c->D($sdata);
			$admininfo = $this->isAdmin();
//			$this->action_log('1',$admininfo['user_id'],"删除分类",$adinfo['catename']);

			if ($func == 'menu') $this->delCache('menu');

			$retmsg['code'] = '1';
			$retmsg['msg'] = $this->lang->line('comm_sucess_tip');
			exit(json_encode($retmsg));
		}
	}

	public function delMore($func='')
	{
		if (!$this->admin_priv($func))
		{
			show_error("您没有权限进行此操作！");
		}
		$postdata = $this->input->post('cateid');
		if(!empty($postdata))
		{
			if(is_array($postdata))
			{
				$cateid = implode(',',$postdata);
			}
			$adinfo = $this->c->L(array('cateid in (' . $cateid . ')' => ''));
			foreach ($adinfo as $k => $v)
			{
				$adname[] = $v['catename'];
			}
			$this->c->D(array('cateid in (' . $cateid . ')' => ''));
//			$admininfo = $this->isAdmin();
//			$this->action_log('1',$admininfo['user_id'],"批量删除分类",implode(',',$adname));
			$retmsg['code'] = '1';
			$retmsg['msg'] = $this->lang->line('success');
			exit(json_encode($retmsg));
		}
		else
		{
			$retmsg['code'] = '0';
			$retmsg['msg'] = $this->lang->line('fail');
			exit(json_encode($retmsg));
		}
	}

	private function hasChild($cateid)
	{
		if ($this->c->O(array('parentid'=>$cateid)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function check_cateid($parentid)
	{
		if (($parentid == $this->input->post('cateid')) && ($this->input->post('cateid') != ''))
		{
			$this->form_validation->set_message('check_cateid', $this->lang->line('admin_checkcate_error'));
			return false;
		}else{
			return true;
		}
		
	}

	// 更新远程缓存
	private function updateMenu($menutype)
	{
		return file_get_contents(site_url("weixin/setMenu") . "/" . $menutype);
	}

	private function delCache($str)
	{
		@unlink(FCPATH . APPPATH . 'cache/' . $str);
	}
}

?>
