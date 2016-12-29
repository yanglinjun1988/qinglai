<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
/**
 */
class Project_model extends MY_Model{
	
	public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_project';
		$this->tbl_key = 'projectid';
		$this->tbl_ext = '';
	}

	
}
