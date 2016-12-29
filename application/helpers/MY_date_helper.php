<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 格式化时间
* @param string $from 起始时间
* @param string $now 终止时间
* @return string
*/
if ( ! function_exists('dateFormat')){
	function dateFormat($from, $now)
	{
		//fix issue 3#6 by saturn, solution by zycbob

		/** 如果不是同一年 */
		if (idate('Y', $now) != idate('Y', $from)) 
		{
			return date('Y年m月d日', $from);
		}

		/** 以下操作同一年的日期 */
		$seconds = $now - $from;
		$days = idate('z', $now) - idate('z', $from);

		/** 如果是同一天 */
		if ($days == 0) 
		{
			/** 如果是一小时内 */
			if ($seconds < 3600) 
			{
				/** 如果是一分钟内 */
				if ($seconds < 60)
				{
					if (3 > $seconds) 
					{
						return '刚刚';
					} 
					else 
					{
						return sprintf('%d秒前', $seconds);
					}
				}

				return sprintf('%d分钟前', intval($seconds / 60));
			}

			return sprintf('%d小时前', idate('H', $now) - idate('H', $from));
		}

		/** 如果是昨天 */
		if ($days == 1) 
		{
			return sprintf('昨天 %s', date('H:i', $from));
		}

		/** 如果是前天 */
		if ($days == 2) 
		{
			return sprintf('前天 %s', date('H:i', $from));
		}

		/** 如果是7天内 */
		if ($days < 7) 
		{
			return sprintf('%d天前', $days);
		}

		/** 超过一周 */
		return date('n月j日', $from);
	}
}


if ( ! function_exists('getAge')){
	function getAge($birthday)
	{
		if ($birthday == '') return '';
	
		$year = substr($birthday, 0, 4);
		return (int)(date('Y') - $year + 1);		
	}
}

if ( ! function_exists('getArray')){
	function getArray($start,$end)
	{
		$year = array();
		$j = 0;
		for ($i = $start;$i<=$end;$i++)
		{

			$year[$j]['id'] = $i;
			$year[$j]['name'] = $i;

			$j++;
		}
		return $year;		
	}
}


//根据某年的第几周星期几返回具体日期
if ( ! function_exists('getd')){
	function getd($year,$weeknum,$week,$format){
	  $yearstr=$year.'-1-1';
	  $weeknumstr=$weeknum-1;
	  $weekw=date('W',strtotime($yearstr));
	  $weekx=date('w',strtotime($yearstr));
	  $dnum=0;
	  if($weekw!='01'){
		$dnum=7-$weekx;
	  }else{
		$dnum=-$weekx;
	  }
	  $weekstr=$week+$dnum;
	  $nowdate=date($format,strtotime($yearstr."+$weeknumstr week $weekstr days"));
	  return $nowdate;
	}
}
//短信验证码相关
function _url($Date){
	$ch = curl_init();
	$timeout = 50;
	curl_setopt ($ch, CURLOPT_URL, "$Date");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$contents = curl_exec($ch);
	curl_close($ch);
	return $contents;
}


function SendSMS($strMobile,$content){
	$url="http://sms.eloone.com/ylSend.do?uid=jhjwt&pwd=678678&rev=";
	$rurl = $url.$strMobile.',&msg=欢迎来到易融会控，你的验证码为：'.$content.'&sdt=&snd=1011';
	$pageURL = iconv('utf-8','gb2312',$rurl);
	$contents=_url($pageURL);
	//echo $contents;
} 

function generate_code($length = 6) 
{   return rand(pow(10,($length-1)), pow(10,$length)-1); }


/**
 *弹出提示对话框，后跳转
 *@$keywords  提示信息
 *$url 跳转地址
 */

if ( ! function_exists('tiao'))
{
	function tiao($keywords, $url)
	{
		$key = '<script type=\'text/javascript\'>alert(\'' . $keywords . '\');location.replace(\'' . $url . '\');</script>';
		return $key;
	}
}
/**
 *直接跳转
 *$url 跳转地址
 */
if ( ! function_exists('tiaos'))
{
	function tiaos($url)
	{
		$key = '<script type=\'text/javascript\'>location.replace(\'' . $url . '\');</script>';
		return $key;
	}
}
/**
 *弹出提示对话框
 *@$keywords  提示信息
 */
if ( ! function_exists('tiaoh'))
{
	function tiaoh($keywords)
	{
		$key = '<script type=\'text/javascript\'>alert(\'' . $keywords . '\');history.go(-1);</script>';
		return $key;
	}
}

/**
 *加密程序
 *$string  加密字符串
 *$operation  'D'解密  其他字符加密
 */
if ( ! function_exists('hifun'))
{
	function hifun($string, $operation, $key = '')
	{
		$key = md5('cmfun2014');
		$key_length = strlen($key);
		$string = ($operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string);
		$string_length = strlen($string);
		$rndkey = $box = array();
		$result = '';

		for ($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($key[$i % $key_length]);
			$box[$i] = $i;
		}

		for ($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for ($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ $box[($box[$a] + $box[$j]) % 256]);
		}

		if ($operation == 'D') {
			if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
				return substr($result, 8);
			}
			else {
				return '';
			}
		}
		else {
			return str_replace('=', '', base64_encode($result));
		}
	}
}

if ( ! function_exists('slogos'))
{
	function slogos($wz_key,$wz_ym){
		if($wz_key == ''){
			return false;
		}
		else if($wz_key == md5('omoi' . $wz_ym. '99+44xa')){
			return true;
		}else{
			return false;
		}
	}
}

/**
 * 是否是AJAx提交的
 * @return bool
 */
function isAjax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        return true;
    }else{
        return false;
    }
}

/**
 * 是否是GET提交的
 */
function isGet(){
    return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
}

/**
 * 是否是POST提交
 * @return int
 */
function isPost() {
    return ($_SERVER['REQUEST_METHOD'] == 'POST' && checkurlHash($GLOBALS['verify']) && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? 1 : 0;
}