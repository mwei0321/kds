<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain: 小说项目初始化
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: MaWei <1123265518@qq.com>
	*	+----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-10-23 下午2:33:45
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Book\Controller;
	use Think\Controller;
	use Library\Book;
	use Library\BookCateTag;
	
	class IniController extends Controller{
		
		function _init(){
			//
			$this->Book = new Book();
			$this->catetag = new BookCateTag();
			$this->assign('status',array(1=>'显示',0=>'隐藏'));
			//带层次的分类列表
			if(S('CateList')){
				$catelist = $this->catetag->level();
				S('CateList',$catelist,30000);
			}
			//标签列表
			if(!S('TagList')){
				$taglist = $this->catetag->taglist('all');
				S('TagList',$taglist,30000);
			}
			$this->assign('catelist',S('CateList'));
			$this->assign('taglist',S('TagList'));
		}
	}