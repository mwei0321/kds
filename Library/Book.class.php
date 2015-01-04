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
		protected $treecate,$chapter;
		function __construct(){
			self::$tree = array();
			$this->treecate = array();
			$this->chapter = null;
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
		function book($_limit = 'count',$_where = array(),$_order = 'id DESC',$_field = '*'){
			$m = M('Book');
			if($_limit == 'count'){
				$count = $m->where($_where)->count();
				return $count;
			}
			$book = $m->where($_where)->order($_order)->limit($_limit)->select();
// 			echo $m->getlastsql();
			$book = $this->_tagToArray($book);
			return $book;
		}
		
		/**
		 * 点击排行榜
		 * @param string $_where 条件
		 * @param string $_time 时间内
		 * @param int $_limit 条数
		 * @return array $list
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-11-28 上午11:30:49
		 */
		function getClickHot($_where = null,$_time = 'day',$_limit = 10){
		    $m = M('BookClickLog');
		    if($_time == 'day'){
		        $_where['uptime'] = array('GT',time() - 3600 * 36);
		    }elseif ($_time == 'week'){
		        $_where['uptime'] = array('GT',time() - 3600 * 24 * 7);
		    }elseif ($_time == 'month'){
		        $_where['uptime'] = array('GT',time() - 3600 * 24 * 30);
		    }elseif ($_time == 'year'){
		        $_where['uptime'] = array('GT',time() - 3600 * 24 * 365);
		    }
		    //取出小说ID
		    $bookids = $m->field('book_id')->where($_where)->select();
		    //统计每篇小说的点击量
		    $bookids = array_count_values(arr2to1($bookids,'book_id',null,false));
		    //排序
		    arsort($bookids);
		    //取出最前ID
		    $bookids = array_slice(array_keys($bookids),0,$_limit);
		    //取详细信息
		    $m = M('Book');
		    $where = array();
		    $where['id'] = array('IN',implode(',', $bookids));
		    $where['status'] = 1;
		    $tmp = $m->field('id,name,cover,author,intro')->where($where)->select();
		    $tmp = fieldtokey($tmp);
		    $list = array();
		    //排序
		    foreach ($bookids as $k => $v){
		        $list[$v] = $tmp[$v];
		    }
		    return $list;
		}
		
		/**
		 * 返回最新更新的小说章节
		 * @param string $_limit 条数
		 * @param int $_cateid 分类ID
		 * @return array $_list 最新更新的小说章节
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-19 下午4:23:57
		 */
		function getNewChapter($_limit = 10,$_cateid = null){
			$m = M('Book');
			$where = $_cateid ? array('cateid'=>$_cateid) : 1;
			$list = $m->where($where)->order('uptime DESC')->limit($_limit)->select();
// 			echo $m->getlastsql();
// 			dump($list);
			foreach ($list as $k => $v){
			    $model = M(getChapterTable($v['id']));
			    $list[$k]['chapter'] = $model->where('book_id='.$v['id'])->order('uptime DESC')->limit(1)->find();
			}
			return $list;
		}
		
		/**
		 * 返回评论
		 * @param array
		 * @param string 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-11-28 下午1:59:36
		 */
		function getComment($_bid,$_limit = 'count',$_where = array()){
		    $m = M('BookComment');
            $where = array();
            $where = $_where;
            $where['book_id'] = $_bid;
            if($_limit == 'count'){
                $count = $m->where($where)->count();
                return $count;
            }
            $list = $m->where($where)->order('uptime DESC')->limit($_limit)->select();
            return $list;
		}

		
		/**
		* 书籍详细信息
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午1:28:33
		*/
		function bookInfo($_bookid){
// 			$m = M();
// 			$sql = "SELECT * FROM `mw_book` b LEFT JOIN `mw_book_extend_info` e ON b.id = e.book_id LEFT JOIN mw_book_extend_field f ON f.id = e.extend_id ";
            $m = M('book');
            $info = $m->where(array('id'=>$_bookid))->find();
			return $info;
		}
		
		/**
		* 返回小说章节
		* @param  int $_id 小说ID 
		* @param  string $_limit 条数
		* @return array $list
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-11-1  下午7:55:15
		*/
		function getBookChapter($_id,$_limit = 'all',$_field = 'id,book_id,title'){
			$m = M(getChapterTable($_id));
			if($_limit == 'all'){
			    $list = $m->field($_field)->where(array('book_id'=>$_id))->order('id ASC')->select();
// 			    echo $m->getlastsql();
			    return $list;
			}elseif($_limit == 'count'){
				$count = $m->where(array('book_id'=>$_id))->count();
				return $count;
			}
			$list = $m->field($_field)->where(array('book_id'=>$_id))->order('id DESC')->limit($_limit)->select();
			return $list;
		}
		
		/**
		* 返回章节内容
		* @param  int $_bid 小说ID
		* @param  int $_tid 章节ID
		* @return array $info
		* @author MaWei (http://www.phpyrb.com)
		* @date 2015-1-2  下午8:32:00
		*/
		function content($_bid,$_tid){
			$m = M(getChapterTable($_bid));
			$info = $m->where(array('id'=>$_tid))->find();
			return $info;
		}
		
		/**
		* 把小说的标签字符变数组
		* @param  array $_list
		* @return array $_list
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-11-1  下午7:08:32
		*/
		function _tagToArray($_list = array()){
			foreach ($_list as $k => $v){
				$_list[$k]['tagid'] = explode(',', $v);
			}
			return $_list;
		}
		
	}