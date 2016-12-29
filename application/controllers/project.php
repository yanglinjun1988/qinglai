<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
/*易创网络科技有限公司
 * 杨林军 QQ：810153135
*/

class Project extends MY_Controller {

	public function __construct() {
        parent::__construct();    
		$this->load->model('project_model', 'project');
		$this->load->library('form_validation');
		$this->load->model('Userinfo_model','u');
    }
	//商品页显示
	public function index($goods_id=''){
		$this->_d['func'] = '';
		$this->_d['alias'] = '';
		//var_dump($this->_d['goods_uid']);
		$this->load->view($this->_d['dir_qt'] . 'project.html',$this->_d);
	}
	
	//项目提交
	public function add($func = '',$alias='')
	{

		if ($this->form_validation->run() == false)
		{
			if (validation_errors() != '')
			{
				$retmsg['code'] = '0';
				$retmsg['msg'] = validation_errors();
				exit(json_encode($retmsg));
			}

			$this->_d['act'] = 'add';
			$row = $this->project->INIT();
			$row['func'] = $func;
			$row['alias'] = $alias;
			$this->_d['row'] = $row;
			$this->load->view($this->_d['dir_qt'] . 'project/add', $this->_d);
		}
		else
		{
			$postdata = $this->input->post();
			$postdata['ctime'] = time();
			if ($this->project->A($postdata) > 0)
			{
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
}