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
	use Admin\Controller\PubAdminController;
	use Vendor\Page;
	use Library\Edit;
	use Library\Book;
		
	class BookController extends PubAdminController{
		protected $book;
		function _init(){
			parent::_init();
			$this->assign('recomm',array('未推荐','已推荐'));
			$this->book = new Book();
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
			$count = $this->book->book('count',$where);
			$page = new Page($count, 30);
			$list = $this->book->book("$page->firstRow,$page->listRows",$where);

			$this->assign('list',$list);
			$this->assign('count',$count);
			$this->assign('page',$page->show());
			$this->display();
		}
		
		/**
		 * 小说章节
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-11-25 上午10:38:22
		 */
		function chapter(){
		    $bookid = intval($_REQUEST['bookid']);
		    $count = $this->book->getBookChapter($bookid,'count');
		    $page = new Page($count,20,array(),array('ajax'=>1));
		    $list = $this->book->getBookChapter($bookid,"$page->firstRow,$page->listRows");
		    
		    
		    $this->assign('list',$list);
		    $this->assign('page',$page->show());
		    if($_REQUEST['ajaxPage']){
		        $this->assign('html',1);
		        $data = array();
		        $data['show'] = $page->show();
		        $html = $this->fetch('pagechpater');
		        $data['html'] = $html;
		        echo json_encode($data);
		        exit;
		    }
		    $this->assign('name',$_REQUEST['name']);
		    $this->display();
		}
		
		/**
		* 删除书籍
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午2:35:18
		*/
		function delete(){
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
			$status = intval($_REQUEST['status']) == '1' ? 0 : 1;
			$reid = status('book',$_REQUEST['ids'],$status);
			if($reid === false){
				$msg['status'] = null;
				$msg['msg'] = $status ? '显示设置失败！' : '隐藏设置失败！';
			}else{
				$msg['status'] = 1;
				$msg['msg'] = $status ? '已显示' : '已隐藏';
			}
			echo json_encode($msg);
			exit;
		}
		
		/**
		* 推荐
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-4  下午1:06:39
		*/
		function recommend(){
			$data = array();
			$data['id'] = intval($_REQUEST['id']);
			$data['recommend'] = intval($_REQUEST['recomm']) == 1 ? 0 : 1;
			$reid = add_updata($data,'Book');
			if($reid === false){
				$msg['status'] = null;
				$msg['msg'] = '推荐失败';
			}else{
				$msg['status'] = 1;
				$msg['msg'] = '推荐设置成功';
				$msg['text'] = intval($_REQUEST['recomm']) ==0 ? '已推荐' : '未推荐';
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
				$info = $this->book->bookinfo(intval($_REQUEST['id']));
// 				dump($info);
				$this->assign('info',$info);
			}
			$this->display();
		}
		
		/**
		* 编辑器
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-22  下午11:11:05
		*/
		function editer(){
			$editer = new Edit();
			echo $editer->output();
		}
		
		/**
		* 书箱添加修改数据库
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-7  下午10:45:59
		*/
		function add_updata(){
			for ($i =0 ;$i<50;$i++){
			$data = array();
			$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
			$data['cateid'] = intval($_REQUEST['cateid']);
// 			$data['tagid'] = implode(',', $_REQUEST['tagids']);
			$data['grade'] = intval($_REQUEST['grade']);
			$data['status'] = $_REQUEST['status'] ? intval($_REQUEST['status']) : 1;
			$data['name'] = h(text($_REQUEST['name']));
			$data['author'] = h(text($_REQUEST['author']));
			$data['uptime'] = time();
			$data['recommend'] = intval($_REQUEST['recommend']);
			$data['end_status'] = intval($_REQUEST['end']);
			$data['intro'] = $_REQUEST['$intro'];
			if($_FILES['cover']['name']){
				$avatar = uploads(array('path'=>'cover','ImgWidth'=>105,'ImgHeight'=>160,'filename'=>'cover'),true);
				$data['cover'] = $avatar['thumb'];
			}
			$reid = add_updata($data,'Book');
			if($reid === false){
				$this->error('添加修改失败',U('Admin/Book/edit'));
			}else{
// 				$temp = array();
// 				$temp['book_id'] = $_REQUEST['id'] ? intval($_REQUEST['id']) : $reid;
// 				$temp['intro'] = $intro;
// 				$temp['content'] = $_REQUEST['content'];
// 				$reid = add_updata($temp,'bookContent','book_id');
// // 				if($reid === false){
// // 					$this->error('添加修改失败',U('Admin/book/index'));
// // 				}
				$this->success('添加修改成功',U('Admin/book/index'));
			}
			}
		}
		
		function attach(){
			
		}
	}

	