<?php
	/**
	*  +---------------------------------------------------------------------------------+
	*   | Explain:  采集处理
	*  +---------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +---------------------------------------------------------------------------------+
	*   | Creater Time : 2014-11-16 	
	*  +---------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +---------------------------------------------------------------------------------+
	**/

	namespace Admin\Controller;
	use Admin\Controller\PubAdminController;
	use Library\Book;
	use Library\Gather;
	
	class GatherController extends PubAdminController{
		
		function _init(){
			parent::_init();
		}
		
		function index(){
			$file = UPLOAD_PATH.'novel/201.txt';
// 			$_filter = array('/\|經.*Ｔ\|/','/【.*】/');
			$content = Gather::readfile($file);
			$m = M('BookChapterT1');
			foreach ($content as $k => $v){
				if($v['content'] != '<p></p>'){
					$reid = $m->add($v);
				}
			}
			
			$this->display();
		}
		
		function dispose(){
			$method = $_REQUEST['method'];
			switch ($method) {
				case 'txt' :
					$data = array();
					$data['cateid'] = intval($_REQUEST['cateid']);
					$data['title'] = t($_REQUEST['title']);
					$data['author'] = t($_REQUEST['author']);
					$data['point'] = intval($_REQUEST['grade']);
					$data['intro'] = t($_REQUEST['discription']);
					$data['status'] = 1;
					$data['uptime'] = time();
			}
		}
		
		function _addall($_bid =1){
			$m = Book::_getChapterTable($_bid);
		}
	}