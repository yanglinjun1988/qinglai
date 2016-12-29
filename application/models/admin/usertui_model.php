<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*
*/

class Usertui_model extends MY_Model {

	function __construct()
    {
        parent::__construct();
		$this->tbl		= 'wz_usertui';
		$this->tbl_key	= 'ut_id';
    }

}
?>