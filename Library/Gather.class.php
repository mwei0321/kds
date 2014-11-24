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
		    ini_set('memory', '200M');
		    $data = file($_file,FILE_SKIP_EMPTY_LINES);
			$content = array();
			$i = $init = 0;
			!$_chapter && $_chapter = '/(第[\d|一|二|三|四|五|六|七|八|九|十|百|千|万]+章{1})(.*)/';
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
				unset($data[$k]);
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
		function getNovelList($_where = array(),$_limit = 'count'){
		    $m = M('BookChapterTmp');
		    if($_limit == 'count'){
		        $count = $m->where($_where)->count();
		        return $count;
		    }
		    $list = $m->where($_where)->order('id ASC')->limit($_limit)->select();
//             echo $m->getLastSql();
		    return $list;
		}
		
		/**
		 * 返回txtfile文件列表
		 * @param String $_limit 条数
		 * @param array $_where 条件
		 * @return array $list
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-11-19 下午2:00:39
		 */
		function txtFile($_limit = 'count',$_where = array()){
		    $m = M('TxtFile');
		    if($_limit == 'count'){
		        $count = $m->where($_where)->count();
		        return $count;
		    }
		    $list = $m->where($_where)->order('id DESC')->select();
// 		    echo $m->getlastsql();
		    return $list;
		}
	}