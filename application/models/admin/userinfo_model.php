<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*
*/

class Userinfo_model extends MY_Model {

	function __construct()
    {
        parent::__construct();
		$this->tbl		= 'wz_user';
		$this->tbl_key	= 'u_id';
    }

}
?>