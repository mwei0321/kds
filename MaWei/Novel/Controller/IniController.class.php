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

	namespace Novel\Controller;
	use Think\Controller;
	use Library\Book;
	use Library\BookCateTag;
	
	class IniController extends Controller{
		protected $book,$catelist,$taglist,$catetag;
		function _init(){
			//缓存清理
			if($_REQUEST['clear'] == 'mw'){
				S('CateList',null);
				S('CateRecomm',null);
				S('NewChapter',null);
				S('Recommend',null);
			}
			//常用类
			$this->book = new Book();
			$this->catetag = new BookCateTag();
			//带层次的分类列表
			if(S('CateList')){
				$this->catelist = $this->catetag->level();
				$this->catelist = fieldtokey($this->catelist);
				S('CateList',$this->catelist,30000);
			}
			//标签列表
			if(!S('TagList')){
				$this->taglist = $this->catetag->taglist('all');
				$this->taglist = fieldtokey($this->taglist);
				S('TagList',$this->taglist,30000);
			}
			$this->assign('catelist',S('CateList'));
			$this->assign('taglist',S('TagList'));
		}
	}