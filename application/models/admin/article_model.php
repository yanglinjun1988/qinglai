<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* �����Ϣģ��
*
*/

class Article_model extends MY_Model {

	function __construct()
	{
		parent::__construct();
		$this->tbl = 'wz_article';
		$this->tbl_key = 'articleid';
	}


	/**
	* �������з����ǰN����¼��������ҳ���ã�
	*/

	public function getAllBlock($fields='*', $maxlimit='10')
	{
		$fields = str_replace(',',',A1.', 'A1.'.$fields);
		$sql = "SELECT " . $fields ."
				FROM live_article AS A1
					INNER JOIN (SELECT A.cateid,A.articleid
								FROM live_article AS A
									 LEFT JOIN live_article AS B
									 ON A.cateid = B.cateid
										AND A.articleid <= B.articleid
								GROUP BY A.cateid,A.articleid
								HAVING COUNT(B.articleid) <= " . $maxlimit . "
					) AS B1
				ON A1.cateid = B1.cateid
				   AND A1.articleid = B1.articleid
				Where A1.status=1
				ORDER BY A1.articleid DESC";
		$query = $this->db->query($sql);	
		return $query->result_array();
	}

}
?>