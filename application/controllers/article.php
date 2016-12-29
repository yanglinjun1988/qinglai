<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 header("Content-Type: text/html; charset=UTF-8"); 
/**
 * ============================================================================
/*易创网络科技有限公司
 * 杨林军 QQ：810153135
*/

class Article extends MY_Controller {

	public function __construct() {
        parent::__construct();    

    }
    public function index()
	{
			echo "nihao";
		//$this->listPage();
	}

	public function listPage()
	{
		
	}
	
	public function cont()
	{
		//$retmsg['code'] = 'success';
		//exit(json_encode($retmsg));
		echo "nihao";
	}
    
}