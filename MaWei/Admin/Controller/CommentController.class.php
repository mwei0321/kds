<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  评论管理
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-9 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :  http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Admin\Controller;
	use Admin\Controller\PubAdminController;
	use Vendor\Page;
		
	class CommentController extends PubAdminController{
		protected $comment;
		
		function _init(){
			parent::_init();
			
		}
		
		/**
		* 首页
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-12  下午9:58:27
		*/
		function index(){
			$count = $this->Sourc->comment();
			$page = new Page($count, 30);
			$list = $this->Sourc->comment("$page->firstRow,$page->listRows");
			
			$this->assign('list',$list);
			$this->assign('count',$count);
			$this->assign('page',$page->show());
			$this->display();
		}
		
		/**
		* 评论回复
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-12  下午10:15:51
		*/
		function reply(){
			$count = $this->Sourc->reply();
			$page = new Page($count, 20);
			$list = $this->Sourc->reply("$page->firstRow,$page->listRows");
			$this->assign('list',$list);
			$this->assign('page',$page->show());
			$this->assign('count',$count);
			$this->display();
		}
		
		function delreply(){
			$reid = delall($_REQUEST['ids'], 'BookCommentReply');
			if($reid === false){
				echo null;
			}else{
				echo 1;
			}
		}
		
		/**
		* 删除
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-12  下午9:58:17
		*/
		function delete(){
			$reid = delall($_REQUEST['ids'], 'BookComment');
			if($reid === false){
				echo null;
			}else{
				echo 1;
			}
		}
	}