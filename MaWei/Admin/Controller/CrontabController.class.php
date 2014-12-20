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
	
	
	class CrontabController extends PubAdminController{
		private 
		
		function _init(){
			
		}
		
		/**
		* 采集任务
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-12-20  下午5:48:53
		*/
		function gather(){
			//获取web采集列表
			$list = $this->gather->getWebName('all',array('status'=>1));
			foreach ($list as $k => $v){
				$chapter = array();
				$temp = getUrlGather($v['url'], $this->_filter($v['chapter_filter']), explode('-', $v['chapter_area']),$v['charset']);
				foreach ($temp as $key => $val){
					//检查章节是否已采集
					if(!$this->gather->checkChpater($v['id'],$val['title'])){
						if($val['title'] && $val['url']){
							$chapter[$key]['title'] = $val['title'];
							$chapter[$key]['book_id'] = $v['id'];
							$chapter[$key]['name'] = $v['name'];
							$chapter[$key]['url'] = strpos('http', $val['url']) !== false ? $val['url'] : $v['url'].$val['url'];
							$chapter[$key]['filter'] = $v['content_filter'];
							$chapter[$key]['charset'] = $v['charset'];
							$content = getUrlGather($val['url'], $this->_filter($v['filter']),null,$v['charset']);
						}
					}
				}
				M('GatherWebChapter')->addAll($chapter);
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

	