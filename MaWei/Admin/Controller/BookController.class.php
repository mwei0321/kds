<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  书集控制类
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-5 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :  http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/
	
	namespace Admin\Controller;
	use Admin\Controller\IniController;
	use Vendor\Page;
		
	class BookController extends IniController{
		function _init(){
			parent::_init();
		}
		
		/**
		* 书籍列表
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午2:46:17
		*/
		function index(){
			$where = array();
			$_REQUEST['bid'] && $where['id'] = $search['bid'] = intval($_REQUEST['bid']);
			$_REQUEST['keyword'] && $where['name'] = $search['keyword'] = text($_REQUEST['keyword']);
			$count = $this->Book->book('count',$where);
			$page = new Page($count, 30);
			$list = $this->Book->book("$page->firstRow,$page->listRows",$where);

			$this->assign('list',$list);
			$this->assign('count',$count);
			$this->display();
		}
		
		/**
		* 删除书籍
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午2:35:18
		*/
		function delect(){
			$reid = delall($_REQUEST['ids'],'Book');
			if($reid === false){
				echo null;
			}else{
				echo 1;
			}
			exit;
		}
		
		/**
		* 状态设置
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午3:00:22
		*/
		function status (){
			$id = intval($_REQUEST['id']);
			$status = intval($_REQUEST['status']) == '1' ? 0 : 1;
			$reid = status('Book', $id,$status);
			if($reid === false){
				$msg['status'] = null;
				$msg['msg'] = $status ? '显示失败！' : '隐藏失败！';
			}else{
				$msg['status'] = 1;
				$msg['msg'] = $status ? '显示' : '隐藏';
			}
			echo json_encode($msg);
			exit;
		}
		
		/**
		* 书籍添加修改页
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-7  下午10:46:46
		*/
		function edit(){
			if($_REQUEST['id']){
				$info = $this->Book->bookinfo(intval($_REQUEST['id']));
				$this->assign('info',$info);
			}
			
			$this->display();
		}
		
		/**
		* 书箱添加修改数据库
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-7  下午10:45:59
		*/
		function add_updata(){
			$data = array();
			$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
			$data['cateid'] = intval($_REQUEST['cateid']);
			$data['price'] = intval($_REQUEST['price']);
			$data['score'] = intval($_REQUEST['score']);
			$data['status'] = intval($_REQUEST['status']);
			$data['title'] = h(text($_REQUEST['title']));
			$data['author'] = h(text($_REQUEST['author']));
			$data['publish'] = h(text($_REQUEST['publish']));
			$data['book_base'] = h(text($_REQUEST['intro']));
			if($_FILES['file']['name']){
				$avatar = uploads(array('path'=>'cover','ImgWidth'=>105,'ImgHeight'=>160),true);
				$data['cover'] = $avatar['thumb'];
			}
			$_REQUEST['id'] ? $data['utime'] = time() : $data['ctime'] = $data['utime'] = time();
			$reid = add_updata($data,'Book');
			if($reid === false){
				$this->error('添加修改失败',U('Admin/Book/edit',array('id'=>intval($_REQUEST['id']))));
			}else{
				$this->success('添加修改成功',U('Admin/Book/index'));
			}
		}
	}

	