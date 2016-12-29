<?php
class MY_Controller extends CI_Controller
{
	// 判断是否登录 
	public $islogin = false;

	public $_d = array();

	// 每页条数
	public $perpages = 20;
	
	// 分页初始化
	public $_p = array();
	
	public function __construct()
    {
        parent::__construct();
		$this->_d['confdisallowed'] = $this->config->item('confdisallowed');
		$this->_d['dir_user'] = 'themes/user/';
		$this->_d['dir_admin'] = 'themes/admin001/';
		$this->_d['dir_qt'] = 'themes/default/';
		$this->_d['tuirul'] = '';
		$this->initComm();
		$this->isLogin();
		
		$this->_p = array('pagenumb'=>$this->perpages,
							'pagecur'=>($this->input->post('pagecur') == 1) ? 0 : $this->input->post('pagecur'),
							'pagecount'=>$this->input->post('pagecount'));
    }
 
    public function isLogin()
    {
    	#从session中获取用户数据，判断用户是否登录，如果userinfo为空，表示没有登录
        $user = $this->session->userdata('userinfo');
		$this->_d['userinfo'] = $user;
       // $this->islogin = ($user == false) ? false : true;
		if($user == false){
			 $this->islogin = false;
		}else{
			$this->islogin = true;
		}
    }

	public function setAuthor($userdata)
	{
		$this->session->set_userdata('userinfo', $userdata);
		return $userdata;
	}



	public function initComm()
	{

	}

	public function logout()
	{
		$this->session->unset_userdata('userinfo');
	}

	public function setAdminAuth($userdata)
	{
		$this->session->set_userdata('adminfo', $userdata);		
	}

	public function isAdmin()
	{
		return $this->session->userdata('adminfo');
	}

	public function adminLogout()
	{
		$this->session->unset_userdata('adminfo');
	}

		/**
 * 判断管理员对某一个操作是否有权限。
 *
 * 根据当前对应的action_code，然后再和用户session里面的action_list做匹配，以此来决定是否可以继续执行。
 * @param     string    $priv_str    操作对应的priv_str
 * @param     string    $msg_type       返回的类型
 * @return true/false
 */
public function admin_priv($priv_str)
{
	$adminfo = $this->isAdmin();	
	return CheckPriv($adminfo, $priv_str);
}


public function action_log($isadmin,$action_userid,$action,$key='')
	{
		$this->load->model('Actionlog_model', 'aclog');
		$adata= array();
		$adata['userid'] = $action_userid;
		$adata['ctime'] = time();
		$adata['info'] = $action;
		$adata['ip'] = $this->input->ip_address();
		$adata['isadmin'] = $isadmin;
		if ($key != '')
		{
			$adata['info'] = $adata['info'] . "：". $key;
		}
		
		if ($this->aclog->A($adata))
		{
			return true;
		}
		else
		{
			return false;
		}


	}


//取得附件
public function getAttach($id='',$action='')
{
	$this->load->model('Attachedcate_model', 'ac');
	$this->load->model('Attacheddetail_model', 'attach');
	if (is_array($id))
	{
		$id = implode(",",$id);
	}

	$attachcateid = $this->ac->L(array('detailid in (' . $id . ')' =>'','action'=>$action),'*',0,0);

	if (!empty($attachcateid))
	{
		foreach ($attachcateid as $k => $v) $cateid[] = $v['attachcateid'];
		return $this->attach->L(array('attachcateid in (' . implode(",",$cateid) . ')' =>''),'*',0,0);
	}
	else
	{
		return false;
	}
	
}
}

