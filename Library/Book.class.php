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
	
	class Book{
		static $tree;
		protected $treecate;
		function __construct(){
			self::$tree = array();
			$this->treecate = array();
		}
		
		/**
		* 书集列表
		* @param  array $_where 条件
		* @param  string $_limit 条数
		* @param  string $_order　排序
		* @return array $book
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-7-30  下午10:48:05
		*/
		function book($_limit = 'count',$_where = array(),$_order = 'id DESC'){
			$m = M('Book');
			if($_limit == 'count'){
				$count = $m->where($_where)->count();
				return $count;
			}
			$book = $m->where($_where)->order($_order)->limit($_limit)->select();
			$m->getlastsql();
			return $book;
		}
		
		/**
		* 书籍详细信息
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午1:28:33
		*/
		function bookinfo($_bookid){
			$m = M('Book');
			$info = $m->where(array('id'=>$_bookid))->find();
			return $info;
		}
		
		/**
		 * 分类列表
		 * @param int $_pid 父ID
		 * @param array $_where 附加条件  
		 * @return array 分类列表
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-7-30 下午5:33:07
		 */
		function category($_pid = NULL,$_where = array()){
			$m = M('BookCategory');
			$where = array();
			$where['_string'] = 'FIND_IN_SET('.$_pid.',path)';
			$where = empty($_where) ? array('status'=>1) : array_merge($where,$_where);
			$cate = $m->where($where)->order('count DESC')->select();
			echo $m->getlastsql();
			return $cate;
		}
		
		/**
		* 返回分类层次
		* @param  array $_cate　分类列表
		* @param  int $_pid 父ID
		* @param  int $_level 层次
		* @return array self::$tree
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-7-30  下午8:46:24
		*/
		function level($_cate,$_pid = '0',$_level = 1){
			foreach ($_cate as $k => $v){
				if($v['pid'] == $_pid){
					$this->treecate[$k] = $v;
					$this->treecate[$k]['level'] = $_level;
					$pid = $v['id'];
					unset($_cate[$k]);
// 					dump(self::$tree);
					$this->level($_cate, $pid,$_level+1);
				}
			}
			return $this->treecate;
		}
		
		/**
		 * 标签列表
		 * @param int $_cateid 分类ID
		 * @param array $_where 附加条件  
		 * @return array 标签列表
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-7-30 下午5:37:09
		 */
		function tags($_cateid = null,$_where = array()){
			$m = M('BookTag');
			$where = array();
			$_cateid && $where['cateid'] = $_cateid;
			$where = empty($_where) ? array('status'=>1) : array_merge($where,$_where);
			$tags = $m->where($where)->order('count DESC')->select();
			return $tags;
		}
		
		/**
		 * 用户的收藏书集
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
		 * 收藏该书的用户
		 * @param int $_uid 用户ID
		 * @param string $_limit 条数
		 * @param string $_order 排序
		 * @param array $_where 附加条件
		 * @return array 用户的收藏书集
		 * @date 2014-7-30 下午6:09:15
		 */
		function collectBookUser($_bookid,$_limit = 'count',$_order = 'point DESC',$_where = array()){
			$m = M('NovelCollect');
			$where = array();
			$where['book_id'] = $_bookid;
			$where = empty($_where) ? array('status'=>1) : array_merge($where,$_where);
			$colloect = $m->where($where)->order('count DESC')->select();
			return $colloect;
		}
		
	}