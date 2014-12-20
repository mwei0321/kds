<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  分类、标签类
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-5 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :  http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Library;
	
	class SourcCateTag{
		protected $cate,$tag,$catelevel,$temp;
		function __construct(){
			$this->cate = M('SourcCategory');
			$this->tag = M('SourcTag');
			$this->catelevel = $this->temp = array();
		}
		
		/**
		* 标签列表
		* @param  string $_limit 条数
		* @param  array $_where 附加条件
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-5  下午11:00:22
		*/
		function taglist ($_limit = 'count',$_where = array()){
			if($_limit == 'count'){
				$count = $this->tag->where($_where)->count();
				return $count;
			}elseif ($_limit == 'all'){
				$list = $this->tag->where($_where)->order('id DESC')->select();
			}else{
				$list = $this->tag->where($_where)->order('id DESC')->limit($_limit)->select();
			}
			return fieldtokey($list);
		}
		
		/**
		* 分类列表
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-5  下午11:13:38
		*/
		function catelist($_limit = 'count',$_where = array()){
			$this->temp = $this->level = array();
			if($_limit == 'count'){
				$count = $this->cate->where($_where)->count();
				return $count;
			}elseif ($_limit == 'all'){
				$list = $this->cate->where($_where)->order('id DESC')->select();
			}else{
				$list = $this->cate->where($_where)->order('id DESC')->limit($_limit)->select();
			}
			return $this->temp = fieldtokey($list);
		}
		
		/**
		 * 更新父ID后，把他的子分类设置成顶级分类
		 * @param int $_id
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-15 下午3:11:04
		 */
		function catepid($_id){
			$ids = $this->cate->field('id')->where(array('pid'=>$_id))->find();
			$ids = arr2to1($ids);
			$this->cate->where(array('id'=>array('IN',implode(',', $ids))))->save(array('pid'=>0));
		}
		
		/**
		* 返回分类的子分类
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-6  下午5:07:21
		*/
		function getCateChildren($_cid){
			$reid = $this->cate->field('id')->where('FIND_IN_SET('.$_cid.',path)')->select();
			return arr2to1($reid,'id');
		}
		
		/**
		* 返回分类层级关系
		* @param  int $_pid 父ID
		* @param  int $_level 层级
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-5  下午11:29:35
		*/
		function _level($_pid = 0,$_level = 0){
			$this->temp ? false : $this->catelist('all');
			if($this->catelevel){
				$tmp = array();
				foreach ($this->temp as $k => $v){
					if($v['pid'] == $_pid){
						$tmp[$k] = $v;
						$tmp[$k]['level'] = $_level;
						$tmp[$k]['levelstr'] = '&nbsp;&nbsp;&nbsp;'.str_repeat('|----', $_level);
						unset($this->temp[$k]);
					}
				}
				return $tmp;
			}else {
				foreach ($this->temp as $k => $v){
					if($v['pid'] == $_pid){
						$this->catelevel[$k] = $v;
						$this->catelevel[$k]['level'] = $_level;
						$this->catelevel[$k]['levelstr'] = '';
						unset($this->temp[$k]);
					}
				}
				foreach ($this->catelevel as $k => $v){
					$children = $this->level($v['id'],$_level+1);
					$this->catelevel[$k]['children'] = $children;
				}
			}
			return $this->catelevel;
		}
		
		/**
		* 返回资源类型
		* @param  array $_where 条件
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-19  下午1:55:59
		*/
		function sourctype($_where = array()){
			$m = M('SourcType');
			$list = $m->where($_where)->select();
			return $list;
		}
		
		/**
		* 返回菜单层级
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-16  上午1:08:55
		*/
		function level($_pid = 0,$_level = 0){
			$this->temp ? false : $this->catelist('all');
			foreach ($this->temp as $k => $v){
				if($v['pid'] == $_pid){
					$this->catelevel[$k] = $v;
					$this->catelevel[$k]['level'] = $_level;
					$this->catelevel[$k]['levelstr'] = $_level > 0 ? str_repeat('&nbsp;', $_level * 5).str_repeat('|----', $_level) : str_repeat('|----', $_level);
					unset($this->temp[$k]);
					$this->level($v['id'],$_level+1);
				}
			}
			return $this->catelevel;
		}
	}