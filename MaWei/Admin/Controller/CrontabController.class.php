<?php
	/**
	*  +---------------------------------------------------------------------------------+
	*   | Explain:  计划任务
	*  +---------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +---------------------------------------------------------------------------------+
	*   | Creater Time : 2014-12-20 	
	*  +---------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +---------------------------------------------------------------------------------+
	**/

	namespace Admin\Controller;
	use Admin\Controller\PubAdminController;
	use Library\Gather;
use Vendor\Page;
		
	
	class CrontabController extends PubAdminController{
		private $num,$gather;
		
		function _init(){
			$this->gather = new Gather();
		}
		
		function index(){
		    echo 11111;
		}
		
		/**
		* 采集任务
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-12-20  下午5:48:53
		*/
		function chapter(){
			//获取web采集列表
			$list = $this->gather->getWebName(2,array('status'=>1));
			if($list){
			    foreach ($list as $k => $v){
			        $chapter = array();
			        $temp = getUrlGather($v['url'], $this->_filter($v['chapter_filter']), explode('-', $v['chapter_area']),$v['charset']);
			        foreach ($temp as $key => $val){
			            if($val['title'] && $val['url']){
			                $chapter[$key]['title'] = $val['title'];
			                $chapter[$key]['book_id'] = $v['id'];
			                $chapter[$key]['name'] = $v['name'];
			                $chapter[$key]['url'] = strpos('http', $val['url']) !== false ? $val['url'] : $v['url'].$val['url'];
			                $chapter[$key]['filter'] = $v['content_filter'];
			                $chapter[$key]['charset'] = $v['charset'];
			                $chapter[$key]['content'] = 0;
			            }
			        }
			        M('GatherWebChapter')->addAll($chapter);
			        M('GatherWebName')->where('id='.$v['id'])->setField('status',0);
			        sleep(1);
			    }
			}
		}
		
		/**
		 * 采集章节内容
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-22 下午3:17:09
		 */
		function content(){
		    $m = M('GatherWebChapter');
	        $list = $this->gather->getWebChapter(30,array('uptime'=>0));
	        $log = '';
	        if($list){
	            $chapter = array();
	            foreach ($list as $k => $v){
	                $content = $this->_content($v['url'], $v['filter'], $v['charset']);
	                $chapter['content'] = $content['content'];
	                $chapter['id'] = $v['id'];
	                $chapter['uptime'] = time();
	                $m->save($chapter);
	                $log .= date('Y-m-d H:m:s')." --- ".$v['book_id']." -> ".$v['id']." \r\n";
	            }
	            $this->_log($log);
	        }else{
	            exit;
	        }
		}
		
		/**
		 * 记录日志
		 * @param string $_string 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-24 下午6:30:38
		 */
		function _log($_string){
		    $f = fopen('/home/web/novel/crontablog.txt','a');
		    fwrite($f, $_string.'------------'."\r\n");
		    fclose($f);
		}
		
		/**
		 * 章节内容采集
		 * @param string $_url 采集地址
		 * @param string $_filter 采集规则
		 * @param string $_chartset 采集页面字符编码
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-22 上午9:53:43
		 */
		private function _content ($_url,$_filter,$_chartset){
		    $content = getUrlGather($_url, $this->_filter($_filter),null,$_chartset);
		    if($content || $this->num > 2){
                return $content;
		    }else{
		        $this->num++;
		        $this->_content($_url, $_filter, $_chartset);
		    }
		}
		
		/**
		 * 解析采集规则
		 * @param string $_reg 采集规则
		 * @return array $data
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-10 下午5:03:51
		 */
		private function _filter($_reg){
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
	}

	