<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* �����Ϣģ��
*
*/

class Document_model extends MY_Model {

	function __construct()
	{
		parent::__construct();
		$this->tbl = 'wz_document';
		$this->tbl_key = 'documentid';
	}




}
?>