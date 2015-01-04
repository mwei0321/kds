<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain: 书集基础类
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: MaWei <1123265518@qq.com>
	*	+----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-7-30 下午5:04:40
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Library;
	
	class Sourc{
		static $tree;
		protected $treecate,$Sourc,$comment;
		function __construct(){
			self::$tree = array();
			$this->treecate = array();
			$this->Sourc = M('Sourc');
			$this->comment = M('SourcComment');
		}
		
		/**
		* 资源列表
		* @param  array $_where 条件
		* @param  string $_limit 条数
		* @param  string $_order　排序
		* @return array $book
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-7-30  下午10:48:05
		*/
		function sourc($_limit = 'count',$_where = array(),$_order = 'id DESC'){
			if($_limit == 'count'){
				$count = $this->Sourc->where($_where)->count();
				return $count;
			}
			$sourc = $this->Sourc->where($_where)->order($_order)->limit($_limit)->select();
// 			echo $this->Sourc->getlastsql();
			return $sourc;
		}
		
		/**
		* 书籍详细信息
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午1:28:33
		*/
		function Sourcinfo($_sourcid){
			$m = M();
			$sql = 'SELECT *,s.id id FROM `mw_sourc` s LEFT JOIN `mw_sourc_content` c ON s.id=c.sourc_id WHERE s.id='.$_sourcid;
			$info = $m->query($sql);
// 			echo $m->getlastsql();
			$info = $this->tagtoarrray($info);
			return array_shift($info);
		}
		
		/**
		* 返回资源附件
		* @param  int $_sid 资源id 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-12  下午9:24:05
		*/
		function getSourcAttch($_sid){
			$m = M('SourcAttach');
			$attch = $m->where(array('sourc_id'=>$_sid))->select();
			return $attch;
		}
		
		/**
		* 把列表里面标签数组化
		* @param  array $_list	
		* @return array $_list
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-3  下午6:59:27
		*/
		function tagtoarrray($_list){
			foreach ($_list as $k => $v){
				$_list[$k]['tagid'] = explode(',', $v['tagid']);
			}
			return $_list;
		}
		
		/**
		 * 用户的收藏
		 * @param int $_uid 用户ID
		 * @param string $_limit 条数
		 * @param string $_order 排序
		 * @param array $_where 附加条件 
		 * @return array 用户的收藏书集
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-7-30 下午6:00:53
		 */
		function userCollect($_uid,$_limit = 'count',$_order = 'point DESC',$_where = array()){
			$m = M('NovelCollect');
			$where = array();
			$where = empty($_where) ? array('status'=>1) : array_merge($where,$_where);
			if($_limit == 'count'){
				$count = $m->field('id')->where($where)->count();
			}
			$colloect = $m->where($where)->order('count DESC')->select();
			return $colloect;
		}
		
		/**
		* 下载日志
		* @param  array $_where　条件
		* @param  string $_limit 条数
		* @param  string $_order　排序
		* @return array $list
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-6  下午6:10:50
		*/
		function downloadlog($_where = array(),$_limit = 'count',$_order = 'id DESC'){
			$m = M('DownloadLog');
			if($_limit == 'count'){
				$count = $m->where($_where)->count();
				return $count;
			}
			$list = $m->where($_where)->order($_order)->limit($_limit)->select();
			return $list;
		}
		
		/**
		* 下载排行榜
		* @param  int $_time 时间 
		* @param  string $_limit 条数
		* @return array $list 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-6  下午9:13:09
		*/
		function gethotdownload($_time,$_limit = '0,15'){
			$m = M('DownloadLog');
			$where = array();
			$where['ctime'] = array('GT',$_time);
			$list = $m->field('sourc_id,COUNT(*) count')->where($where)->group('sourc_id')->order('count DESC')->limit($_limit)->select();
// 			echo $m->getlastsql();
			$list = $this->sourc($_limit,array('id'=>array('IN',implode(',', arr2to1($list,'sourc_id')))));
			return $list;
		}
		
		/**
		 * 收藏该资源的用户
		 * @param int $_uid 用户ID
		 * @param string $_limit 条数
		 * @param string $_order 排序
		 * @param array $_where 附加条件
		 * @return array 用户的收藏书集
		 * @date 2014-7-30 下午6:09:15
		 */
		function collectSourcUser($_bookid,$_limit = 'count',$_order = 'point DESC',$_where = array()){
			$m = M('NovelCollect');
			$where = array();
			$where['book_id'] = $_bookid;
			$where = empty($_where) ? array('status'=>1) : array_merge($where,$_where);
			$colloect = $m->where($where)->order('count DESC')->select();
			return $colloect;
		}
		
		/**
		 * 返回评论
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-9  下午4:22:35
		 */
		function comment($_limit = 'count',$_where = array(),$_order = 'id DESC'){
			if($_limit == 'count'){
				$count = $this->comment->where($_where)->count();
				return $count;
			}
			$list = $this->comment->where($_where)->order($_order)->limit($_limit)->select();
			//把下标换成ID
			$list = fieldtokey($list);
			//评论下的回复
			$cids = arr2to1($list);
			$reply = $this->_commentReply($cids);
			foreach ($reply as $k => $v){
				$list[$v['reply_id']]['reply'][$v['id']] = $v;
			}
			return $list;
		}
		
		/**
		 * 返回评论下的回复
		 * @param array $_cids 评论ID
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-15 下午3:02:24
		 */
		function _commentReply($_cids){
			$reply = $this->comment->where(array('reply_id'=>array('IN',implode(',', $_cids))))->select();
			return $reply;
		}
	}