<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*
*/

class Admin_model extends MY_Model {

	function __construct()
    {
        parent::__construct();
		$this->tbl		= 'wz_admin';
		$this->tbl_key	= 'admin_id';
    }

}
?>