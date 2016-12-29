<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
/**
 */
class Pages_model extends MY_Model{
	
	public function __construct() {
		parent::__construct();
		$this->tbl = 'wz_pages';
		$this->tbl_key = 'pageid';
		$this->tbl_ext = '';
	}

	/**
	* �������з����ǰN����¼��������ҳ���ã�
	*/

	public function getAllBlock($fields='*', $maxlimit='10')
	{
		$fields = str_replace(',',',A1.', 'A1.'.$fields);
		$sql = "SELECT " . $fields ."
				FROM live_pages AS A1
					INNER JOIN (SELECT A.cateid,A.pageid
								FROM live_pages AS A
									 LEFT JOIN live_pages AS B
									 ON A.cateid = B.cateid
										AND A.pageid <= B.pageid
								GROUP BY A.cateid,A.pageid
								HAVING COUNT(B.pageid) <= " . $maxlimit . "
					) AS B1
				ON A1.cateid = B1.cateid
				   AND A1.pageid = B1.pageid
				Where A1.status=1
				ORDER BY A1.cateid,A1.ctime DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
