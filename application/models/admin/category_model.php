<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * 
 */
class Category_model extends MY_Model{
	
	public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_category';
		$this->tb2 = 'wz_advertising';
		$this->tbl_key = 'cateid';
		$this->tbl_ext = '';
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


		$sql = "SELECT c.cateid, c.catename, c.parentid, c.sort, c.func,c.alias,c.status, COUNT(s.cateid) AS children,c.iscommend,c.showtype ".
                'FROM ' . $this->tbl . " AS c ".
                "LEFT JOIN " . $this->tbl . " AS s ON s.parentid=c.cateid ".
				$condition .
                "GROUP BY c.cateid ".
                'ORDER BY c.parentid, c.sort ASC';
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

	public function getChild($parentid = '')
	{
		$sql = "SELECT b.* from " . $this->tbl . " As a INNER JOIN " . $this->tb2 . " As b on a.cateid = b.cateid and  a.parentid = " . $parentid . " and b.status = 1 and a.status = 1 order by b.sort";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	//获取子集
	public function getChild2($alias = '')
	{
		$sql = "SELECT * from " . $this->tbl . " As a where  a.alias = " . '\''  . $alias . '\'' . " and a.status = 1 order by a.sort";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function countcate($cateid,$action)
	{
		if ($action == 'add')
		{
			$sql = "update " .$this->tbl. " set total = total + 1 where cateid = " . $cateid;
			return $this->db->query($sql);
		}
		if ($action == 'del')
		{
			$query = $this->db->query( "select total from " .$this->tbl. " where cateid = " . $cateid);
			$total = $query->row_array();
			if ($total['total'] > 0)
			{
				$sql = "update " .$this->tbl. " set total = total - 1 where cateid = " . $cateid;
				return $this->db->query($sql);
			}
			else
			{
				return true;
			}
		}
					
	}


	public function getIndustry()
	{
		return $this->L(array('parentid'=>'0','func'=>'industry'), 'cateid as id, catename as name, parentid', '50');
	}

	public function getIndustrysub($industryid)
	{
		return $this->L(array('parentid'=>$industryid,'func'=>'industry'), 'cateid as id, catename as name , parentid', '50');
	}

}
