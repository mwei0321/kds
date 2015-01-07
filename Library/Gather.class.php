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
		function getTxtChapter($_where = array(),$_limit = 'count'){
		    $m = M('TxtChapterTmp');
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
		
		/**
		 * 
		 * @param array
		 * @param string 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-10 下午3:53:18
		 */
		function getWebCate($_limit = 'count',$_where = array()){
		    $m = M('GatherWebCate');
		    if($_limit == 'count'){
		        $count = $m->where($_where)->count();
		        return $count;
		    }elseif($_limit == 'all'){
		        $list = $m->where($_where)->order('id DESC')->select();
		        return $list;
		    }
		    $list = $m->where($_where)->order('id DESC')->limit($_limit)->select();
// 		    echo $m->getlastsql();
		    return $list;
		}
		
		/**
		 * 返回采集的小说列表
		 * @param string $_limit 条数
		 * @param array $_where 条件
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-10 下午4:13:05
		 */
		function getWebName($_limit = 'count',$_where = 1){
		    $m = M('GatherWebName');
		    if($_limit == 'count'){
		        $count = $m->where($_where)->count();
		        return $count;
		    }elseif($_limit == 'all'){
		        $list = $m->where($_where)->order('id DESC')->select();
		    }
		    $list = $m->where($_where)->order('id DESC')->limit($_limit)->select();
// 		    echo $m->getlastsql();
		    return $list;
		}
		
		/**
		 * 检查章节是否已经 采集，返回ID
		 * @param int $_book_id 小说ID
		 * @param string $_chapter 章节标题
		 * @return boolean 
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-19 下午5:16:52
		 */
		function checkChpater($_book_id,$_chapter,$_model = 'GatherWebChapter'){
		    $m = M($_model);
		    $where = array();
		    $where['book_id'] = $_book_id;
		    $where['title'] = $_chapter;
		    $reid = $m->field('id')->where($where)->find();
// 		    echo $m->getlastsql();
		    return $reid;
		}
		
		/**
		 * 
		 * @param array
		 * @param string 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-10 下午4:13:09
		 */
	    function getWebChapter($_limit = 'count',$_where = 1,$_order = 'id DESC'){
		    $m = M('GatherWebChapter');
		    if($_limit == 'count'){
		        $count = $m->where($_where)->count();
		        return $count;
		    }elseif($_limit == 'all'){
		        $list = $m->where($_where)->order($_order)->select();
		    }
		    $list = $m->where($_where)->order($_order)->limit($_limit)->select();
// 		    echo $m->getlastsql();
		    return $list;
		}
		
		/**
		 * 奇书采集列表
		 * @param array
		 * @param string 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2015-1-7 下午3:53:37
		 */
		function getQisuu($_where = 1,$_limit = 'count',$_order = 'id DESC'){
		    $m = M('QisuuGather');
		    if($_limit == 1){
		        $list = $m->where($_where)->order($_order)->find();
		        return $list;
		    }elseif($_limit == 'count'){
		        $count = $m->where($_where)->count();
		        return $count;
		    }elseif($_limit == 'all'){
		        $list = $m->where($_where)->order($_order)->select();
		        return $list;
		    }
		    $list = $m->where($_where)->order($_order)->limit($_limit)->select();
		    return $list;
		}
		
		/**
		 * 根据ids返回章节
		 * @param string $_ids
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-25 上午10:30:35
		 */
		function getIdsChapter($_ids){
		    $m = M('GatherWebChapter');
		    $list = $m->where(array('id'=>array('IN',$_ids)))->select();
		    return $list;
		}
		
	}