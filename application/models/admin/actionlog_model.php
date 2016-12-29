<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* ������־��ģ��
*
*/

class Actionlog_model extends MY_Model {

	function __construct()
    {
        parent::__construct();
		$this->tbl		= 'wz_action_log';
		$this->tbl_key	= 'logid';
    }
}
?>