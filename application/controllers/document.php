<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
/*易创网络科技有限公司
 * 杨林军 QQ：810153135
*/

class Document extends MY_Controller {

	public function __construct() {
        parent::__construct();    
		$this->load->model('Document_model','document');
		$this->load->model('Category_model','cate');
		$this->load->model('Actionlog_model', 'aclog');
		$this->_d['admininfo'] = $this->isAdmin();
    }
    public function index($func = '',$alias='')
	{
		

//		var_dump($catepid);
		$this->load->view($this->_d['dir_qt'] . 'document/index.html');
	}
	public function top(){
		
		//var_dump($this->_d['admininfo']);
		$this->load->view($this->_d['dir_qt'] . 'document/top.html',$this->_d);
	}
	public function menu($func = 'document',$alias=''){
		$search = array();
		$menu= array();
		
		if (!empty($alias) && $alias != 0) $search['alias'] = $alias;
		$data = $this->cate->getCateData($func,$search);
//		$this->_d['list'] = cate2list(0, $data);
		$this->_d['list'] = cate2child($data);

		$this->_d['func'] = 'document';
		$this->_d['alias'] = $alias;
		$this->load->view($this->_d['dir_qt'] . 'document/menu2.html',$this->_d);
	}
	public function drag(){
		$this->load->view($this->_d['dir_qt'] . 'document/drag.html');
	}
	public function main($cateid = ''){
		$sdata = array();
		$mdata = array();
		$mchild = array();
		$flag = 0;
		if(!empty($cateid)){
			$sdata['cateid'] = $cateid;
			$this->_d['cateid'] = $cateid;
				
			//顶级标签处理
			$data = $this->cate->O(array('cateid'=>$cateid));
			//检测是否为顶级目录
			if($data['parentid'] == 0)
			{
				//获得子目录
				$mchild = $this->cate->getCateData('document',array('parentid'=>$data['cateid']));
				//var_dump($mchild);
				foreach($mchild as $k1=>$v1){
					$mchild[$k1]['mdocument'] = $this->document->L(array('cateid'=>$v1['cateid']),"documentid,cateid,title,desc,ctime");
					foreach($mchild[$k1]['mdocument'] as $k2=>$v2){
						$mchild[$k1]['mdocument'][$k2]['child'] = $this->aclog->L(array('arcid'=>$v2['documentid']));
					}
				}
				$flag = 1;	
			}
			
		} else{
			$this->_d['cateid'] = '';
			$sdata['cateid'] = '1';
		}
		if($flag == 0){
			$mdata = $this->document->L($sdata);
			foreach($mdata as $k=>$v){
				$mdata[$k]['child'] = $this->aclog->L(array('arcid'=>$v['documentid']));	
			}
			$this->_d['content'] = $mdata;
			//var_dump($this->_d['content']);
			$this->load->view($this->_d['dir_qt'] . 'document/main1.html',$this->_d);
		}else{
			$this->_d['mcontent'] = $mchild;
			//var_dump($mchild);
			$this->load->view($this->_d['dir_qt'] . 'document/catalog.html',$this->_d);
		}
		
	}

	public function listPage()
	{
		$sdata = array();
		$this->_p['pagenumb'] = 45;
		$selectField = "documentid,cateid,title,imgthumb,desc,author,ctime,hits";
		$list_t = $this->document->L($sdata,$selectField, $this->_p['pagenumb'], ($this->_p['pagecur'] == 0) ? 0 : ($this->_p['pagecur']-1) * $this->_p['pagenumb'],'documentid','desc');
		//热门板块获取
		$catedata = $this->cate->getChild2('news');
		$this->_d['catedate'] = $catedata;
		//将文章所属类别加到文章信息中
		if (!empty($list_t))
		{
			foreach ($list_t as $k => $v) $cateIdAry[] = $v['cateid'];
			$cateId = implode(',',$cateIdAry);
			$categoryList_t = $this->cate->L(array('cateid in (' . $cateId . ')' =>''));
			$categoryList = cate2array($categoryList_t, 'cateid');
			foreach ($list_t as $k => $v)
			{
				if (!empty($categoryList[$v['cateid']])) $list_t[$k]['catename'] = $categoryList[$v['cateid']]['catename'];
				
			}
		}
		$this->_p['pagecount'] = $this->input->post('pagecount');
		if (empty($this->_p['pagecount'])) 
		{
			$this->_p['pagecount'] = $this->document->C();
		}
		$this->_d['page'] = eyPage($this->_p,$sdata);
		$this->_d['pagecount'] = $this->_p['pagecount'];
		$this->_d['list'] = $list_t;
		var_dump($this->_d);
		$this->load->view($this->_d['dir_qt'] . 'document/index.html',$this->_d);
		//$this->load->view($this->_d['dir_admin'] . 'page/list', $this->_d);
	}
	
	public function add($cateid = '',$func = 'document')
	{

		if ($this->form_validation->run('document/edit') == false)
		{
			if (validation_errors() != '')
			{
				$retmsg['code'] = '0';
				$retmsg['msg'] = validation_errors();
				exit(json_encode($retmsg));
			}
			if($cateid != ''){
				$this->_d['cate'] = $this->cate->O(array('cateid'=>$cateid));
				$this->_d['cateid'] = $cateid;
			}else{$this->_d['cate'] = array();$this->_d['cateid'] = '';}
			//var_dump($this->_d['cate']);
			$this->_d['action'] = 'add';
			$row = $this->document->INIT();
			$adminifo = $this->session->userdata('adminfo');
			$row['func'] = $func;
			$row['author'] = $adminifo['admin_name'];
			$this->_d['row'] = $row;
			$catedata =  cate2list(0, $this->cate->getCateData('document'));		
			$this->_d['parentcate'] = array2option($catedata, '', 1);
			$this->load->view($this->_d['dir_qt'] . 'document/arc_add', $this->_d);
		}
		else
		{
			$postdata = $this->input->post();
			$postdata['ctime'] = time();
			$documentid = $this->document->A($postdata);
			if ( $documentid > 0)
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
	
	public function editdocument($documentid = 1){
		if ($this->form_validation->run('document/edit') == false)
		{
			if (validation_errors() != '')
			{
				$retmsg['code'] = '0';
				$retmsg['msg'] = validation_errors();
				exit(json_encode($retmsg));
			}
			$this->_d['action'] ="editdocument";
			$sdata['documentid'] = $documentid;
			$this->_d['row'] = $this->document->O($sdata);
			//var_dump($this->_d);
			$this->load->view($this->_d['dir_qt'] . 'document/edit.html', $this->_d);
		}
		else
		{
			$sdata['documentid'] =  $this->input->post('id');
			
			$postdata = $this->input->post();
			$cateid = $postdata['cateid'];
			$postdata['mtime'] = time();
			if ($this->document->M($postdata,$sdata) >0)
			{
				if($postdata['ischange']){
				$admininfo = $this->isAdmin();
				$this->action_log('1',$admininfo['user_id'],"修改文档",$postdata['title'],$postdata['adminname'],$postdata['comment'],$sdata['documentid']);
				}
				$data['url'] = site_url('document/main').'/'.$cateid;
				echo tiao('修改成功', $data['url']);
				exit();
			}
			else
			{
				$data['url'] = site_url('document/main').'/'.$cateid;
				echo tiao('修改失败', $data['url']);
				exit();	
			}
		}
		
		
//		$actid = $this->input->get('arcid');
//		$actcont = $this->document->O(array('documentid'=>$actid));	
//		$html = '';
////		$html .= "<link rel='stylesheet' href='".base_url('themes/common')."/kindeditor/themes/default/default.css' />";
////		$html .= "<script charset='utf-8' src='".base_url('themes/common')."/kindeditor/kindeditor-min.js'></script>";
////		$html .= "<script charset='utf-8' src='".base_url('themes/common')."/kindeditor/lang/zh_CN.js'></script>";
////		$html .= "<script>var editor;KindEditor.ready(function(K) {editor = K.create('textarea[name='";
////		$html .= "content']', {resizeType : 1,allowPreviewEmoticons : false,allowImageUpload : false,items : [
////						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
////						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
////						'insertunorderedlist', '|', 'emoticons', 'image', 'link']});});</script>";
//		$html .= "<form id='form1' name='form1' action='' method='post'>";
//		$html .= "<INPUT TYPE='hidden' NAME='documentid' value='".$actcont['documentid']."'>";
//		$html .= "<div class='form2'>";
//		$html .= "<dl class='lineD'>";
//		$html .= "<dt>文档标题：</dt>";
//		$html .= "<dd><input name='title' id='title' type='text' style='width: 600px;' value='".$actcont['title']."'><span>*</span></dl>";
//		$html .= "<dl class='lineD'><dt>详细内容：</dt><dd><textarea name='content' id='content' style='width: 800px; height: 250px;'>";
//		$html .= $actcont['content']."</textarea></dl>";
//		$html .= "<dl class='lineD'><dt style='float:left'>文档作者：</dt><dd><input type='text' name='author' value='".$actcont['author']."'/></dl>";
//		$html .= "<dl class='lineD'><dt>排序：</dt><dd><input name='sort' id='sort' type='text' value='".$actcont['sort']."'/></dl>";
//		$html .= " <input name='status' type='hidden' value='1'  checked >";
//		$html .= "<div class='page_btm'><input type='button' class='btn_b' value='确定' ONCLICK='editdocument()' /></div></div></form>";
//		echo $html;
	}
	
	public function deldocument($documentid)
	{
		$sdata['documentid']=$documentid;
		$adinfo = $this->document->O($sdata);
		$this->document->D($sdata);
		$admininfo = $this->isAdmin();
		$this->action_log('1',$admininfo['user_id'],"删除文章",$adinfo['title']);
		$retmsg['code'] = '1';
		$retmsg['msg'] = $this->lang->line('success');
		exit(json_encode($retmsg));
	}
	
	public function cont($documentid='')
	{
		$this->_d['documentid'] = $documentid;
		$pg = $this->document->O(array('documentid'=>$documentid));
		$cateinfo = $this->cate->O(array('cateid'=>$pg['cateid']));
		$catedata = $this->cate->getChild2('news');
		$this->_d['catedate'] = $catedata;
		$this->_d['cateinfo'] = $cateinfo;
		$this->_d['pg'] = $pg;
		$this->_d['cateid'] = $pg['cateid'];
		$pagecate = cate2array($this->document->L(array('cateid'=>$pg['cateid'],'status'=>'1'),'documentid,title,content',0,0,'sort'),'documentid');
		$this->_d['pagecate'] = $pagecate;
		$this->_d['firstpage'] = $this->document->O(array('cateid'=>$pg['cateid'],'status'=>'1'),'documentid,title,content','sort','asc');
		//var_dump($cateinfo);
		//var_dump($this->_d);
		$this->load->view($this->_d['dir_qt'] . 'page/page_cont',$this->_d);
	}
    
}