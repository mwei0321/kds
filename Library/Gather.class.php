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
	use phpQuery;
	
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
		 * 
		 * @param array
		 * @param string 
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
		 * 
		 * @param array
		 * @param string 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-10 下午4:13:09
		 */
	    function getWebChapter($_limit = 'count',$_where = 1){
		    $m = M('GatherWebChapter');
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
		 * 采集
		 * @param string $_url 网址
		 * @param array $_filter 采集过滤规则   array('title'=>'li','content'=>'.content');
		 * @param string $_filter 采集区域 '#area'
		 * @return array $data
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-11 上午10:39:33
		 */
		function getUrlGather($_url,$_filter,$_area = null,$_charset = null){
		    require_once './phpQuery.php';
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
		                    break;
		                default:
		                    $data[$key] = pq('body')->find($value[0])->attr($value[1]);
		                    break;
		            }
		        }
		        reset($_filter);
		    }
		    eval('$data = '.iconv($charset, 'UTF-8'.'//IGNORE', var_export($data,TRUE)).';');
		    return $data;
		}
	}