<?php
	/**
	*  +---------------------------------------------------------------------------------+
	*   | Explain:  
	*  +---------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +---------------------------------------------------------------------------------+
	*   | Creater Time : 2014-12-20 	
	*  +---------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +---------------------------------------------------------------------------------+
	**/
	
// 	if($_REQUEST['key'] != 'mw') exit;

	//根路径
	define('ROOT_PATH', str_replace('\\','/',dirname(__FILE__)));
	
	// 引入ThinkPHP入口文件
	require ROOT_PATH.'/ThinkPHP/ThinkPHP.php';
	
	gather();
	
	function gather(){
		$gather = new Gather();
		$novel = $gather->getWebNovel();
		dump($novel);
	}
	
	/**
	 *
	 * @param array
	 * @param string
	 * @return array
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-12-10 下午5:03:51
	 */
	function filter($_reg){
		$tmp = explode('|', $_reg);
		$data = array();
		foreach ($tmp as $k => $v){
			if($v){
				$t = explode('$', $v);
				$data[$t['0']] = explode('-',$t['1']);
			}
		}
		return $data;
	}
	
	/**
	 * 采集
	 * @param string $_url 网址
	 * @param array $_filter 采集过滤规则   filed$DOMEle-type|
	 * @param string $_area 采集区域 '#area－mulitiele',区域－多个DOM
	 * @return array $data
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-12-11 上午10:39:33
	 */
	function getUrlGather($_url,$_filter,$_area = null,$_charset = null){
		require_once ROOT_PATH.'/Library/phpQuery.php';
		$html = file_get_contents($_url);
		$charset = $_charset ? $_charset : mb_detect_encoding($html, array('ASCII', 'GB2312', 'GBK', 'UTF-8'));
		$phpquery = phpQuery::newDocumentHTML("$html",$charset);
		$data = array();
		if($_area){
			$area = is_array($_area) ? pq($_area[0])->find($_area[1]) : pq($_area);
			foreach ($area as $k => $v){
				while (!!list($key,$value) = each($_filter)){
					switch ($value[1]){
						case 'text' :
							$data[$k][$key] = trim(pq($v)->find($value[0])->text());
							break;
						case 'html' :
							$data[$k][$key] = pq($v)->find($value[0])->html();
							break;
						default:
							$data[$k][$key] = pq($v)->find($value[0])->attr($value[1]);
							break;
					}
				}
				reset($_filter);
			}
		}else{
			while (!!list($key,$value) = each($_filter)){
				switch ($value[1]){
					case 'text' :
						$data[$key] = trim(pq('body')->find($value[0])->text());
						break;
					case 'html' :
						$data[$key] = pq('body')->find($value[0])->html();
						eval('$data[$key] = '.iconv($charset, 'UTF-8'.'//IGNORE', var_export($data[$key],TRUE)).';');
						break;
					default:
						$data[$key] = pq('body')->find($value[0])->attr($value[1]);
						break;
				}
			}
			reset($_filter);
		}
		return $data;
	}
	
	/**
	* 采集类
	* @author MaWei (http://www.phpyrb.com)
	* @date 2014-12-20  下午2:57:50
	*/
	class Gather{
		
		function __construct(){
			
		}
		
		/**
		* web采集小说名列表
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-12-20  下午2:58:56
		*/
		function getWebNovel(){
			$m = M('GatherWebName');
			$list = $m->where(array('status'=>1))->select();
			return $list;
		}
		
		/**
		* web采集小说章节
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-12-20  下午3:00:19
		*/
		function getWebChapter(){
			$m = M("GatherWebChapter");
			$list = $m->where(array('is_send'=>0))->select();
			return $list;
		}
	}