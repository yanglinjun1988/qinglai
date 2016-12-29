<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**=====================================================================
*/

/**
 */
class Page extends MY_Controller {

	public function __construct() {
        parent::__construct();
		$this->load->model('Category_model','cate');
		$this->load->model('Pages_model','pg');
    }
   //推广列表页面
   public function index()
	{
		$this->listPage();
	}

	public function listPage()
	{
		$sdata = array();
		$this->_p['pagenumb'] = 45;
		$selectField = "pageid,cateid,title,keywords,author,ctime,hits";
		$list_t = $this->pg->L($sdata,$selectField, $this->_p['pagenumb'], ($this->_p['pagecur'] == 0) ? 0 : ($this->_p['pagecur']-1) * $this->_p['pagenumb'],'pageid','desc');
		//热门板块获取
		$catedata = $this->cate->getChild2('tui');
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
			$this->_p['pagecount'] = $this->pg->C();
		}
		$this->_d['page'] = eyPage($this->_p,$sdata);
		$this->_d['pagecount'] = $this->_p['pagecount'];
		$this->_d['list'] = $list_t;
		//var_dump($this->_d);
		//$this->load->view($this->_d['dir_qt'] . 'tui.html',$this->_d);
		$this->load->view($this->_d['dir_qt'] . 'page/list', $this->_d);
	}

	// 
	public function cont($pageid='')
	{
		$this->_d['pageid'] = $pageid;
		$pg = $this->pg->O(array('pageid'=>$pageid));
		$cateinfo = $this->cate->O(array('cateid'=>$pg['cateid']));
		$catedata = $this->cate->getChild2('tui');
		$this->_d['catedate'] = $catedata;
		$this->_d['cateinfo'] = $cateinfo;
		$this->_d['pg'] = $pg;
		$this->_d['cateid'] = $pg['cateid'];
		$pagecate = cate2array($this->pg->L(array('cateid'=>$pg['cateid'],'status'=>'1'),'pageid,title,content',0,0,'sort'),'pageid');
		$this->_d['pagecate'] = $pagecate;
		$this->_d['firstpage'] = $this->pg->O(array('cateid'=>$pg['cateid'],'status'=>'1'),'pageid,title,content','sort','asc');
		//var_dump($cateinfo);
		//var_dump($this->_d);
		$this->load->view($this->_d['dir_qt'] . 'page/page_cont',$this->_d);
	}
}

