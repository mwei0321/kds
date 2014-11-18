<?php
	/**
	*  +---------------------------------------------------------------------------------+
	*   | Explain:  采集处理类
	*  +---------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +---------------------------------------------------------------------------------+
	*   | Creater Time : 2014-11-16 	
	*  +---------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +---------------------------------------------------------------------------------+
	**/

	namespace Library;
	
	class Gather {
		
		function __construct(){
			
		}
		
		/**
		* 文件处理
		* @param  string $_file 文件路径
		* @param  array $_filter 过滤归则
		* @param  array|string 替换字符
		* @param  string $_chapter 分章正则
		* @return array $content
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-11-16  下午9:04:55
		*/
		function readfile($_file,$_chapter = null,$_filter = array(),$_replace = ''){
		    $data = file($_file);
			$content = array();
			$i = $init = 0;
			!$_chapter && $_chapter = '/(第.*章)(.*)/';
			foreach ($data as $k => $v){
				$v = trim($v);
				if(preg_match_all($_chapter, $v , $title)){
					$init && $i ++;
					$content[$i]['title'] = $title[2][0];
					$content[$i]['chapter'] = $title[1][0];
					$content[$i]['uptime'] = time();
					$init = 1;
				}else{
					$txt = $_filter ? preg_replace($_filter, $_replace, $v) : $v;
					$content[$i]['content'] .= '<p>'.$txt.'</p>';
				}
			}
			return $content;
		}
		
		/**
		 * 根据小说ID返回章节列表
		 * @param int $_bid novel id
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-11-18 下午2:42:23
		 */
		function getNovelList($_bid = null){
		    $m = M('BookGatherTmp');
		    $where = $_bid ? array('book_id'=>$_bid) : array();
		    $list = $m->where()->order('id ASC')->select();
		    return $list;
		}
	}