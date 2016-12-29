<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * 
 */
class Category_model extends MY_Model{
	
	public function __construct() {
		parent::__construct();
		$this->tbl = 'ims_ewei_shop_category';
		$this->tbl_key = 'id';
	}
	
	public function LL($sdata)
	{
		$condition = '';
		if (count($sdata) > 0 )
		{
			$condition_t = "where";
			if (!empty($sdata))
			{
				foreach ($sdata as $k => $v)
				{
					if ($v != '')
					{
						if (strpos($v,','))
						{
							$condition_t .= ' c.' . $k . " in (" . $v . ") and ";
						}
						else
						{
							$condition_t .= ' c.' . $k . "='" . $v . "' and ";
						}
					}
					else if ($v == false)
					{
						$condition_t .= $k . " and ";
					}
				}
			}
			if ($condition_t == "where") 
			{
				$condition = "";
			}
			else		
			{
				$condition = substr($condition_t, 0, -4);
			}
		}


		$sql = "SELECT c.id, c.catename, c.parentid, COUNT(s.id) AS children,c.displayorder,c.enabled ".
                'FROM ' . $this->tbl . " AS c ".
                "LEFT JOIN " . $this->tbl . " AS s ON s.parentid=c.id ".
				$condition .
                "GROUP BY c.id ".
                'ORDER BY c.parentid ASC';
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function getCateData($func = '',$search='')
	{
		if (empty($search) || $search == 'all')
			$sdata = array();
		else
			$sdata = $search;
		
		if ($func != '') $sdata['func'] = $func;
		return $this->LL($sdata);
	}


}
