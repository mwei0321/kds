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
		protected $gather;
		function _init(){
			parent::_init();
			$this->gather = new Gather();
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
			$list = $this->gather->readfile('D:/Web/Novel/Uploads/Novel/2014-11/www.kandianshu.com2014111810110950cd7.txt');
			dump($list);
			switch ($method) {
				case 'txt' :
				    $_FILES['file']['name'] && $file = fileUpload('Novel');
				    $name = substr($file['name'],0,strrpos($file,'.'));
				    if($file['savename']){
// 				        //插入小说基本信息
// 				        $data = array();
// 				        $data['cateid'] = intval($_REQUEST['cateid']);
// 				        $data['title'] = t($_REQUEST['title']);
// 				        $data['author'] = t($_REQUEST['author']);
// 				        $data['status'] = 0;
// 				        $data['end_status'] = intval($_REQUEST['end_status']);
// 				        $data['uptime'] = time();
// 				        $data['title'] = $_REQUEST['title'] ? t($_REQUEST['title']) : $name;
// 				        $reid = add_updata($data,'Book');
// 				        //插入到附件，及全本文件
// 				        $attach = array();
// 				        $attach['book_id'] = $reid;
// 				        $attach['size'] = $file['size'] * 0.001;
// 				        $attach['name'] = $_REQUEST['title'] ? t($_REQUEST['title']) : $name;
// 				        $attach['path'] = $file['path'];
// 				        $attach['uptime'] = time();
// 				        $is_ok = add_updata($attach,'BookAttach');
// 				        //保存正则
// 				        $grep = array();
// 				        $grep['chapter'] = t($_REQUEST['chapter']);
// 				        $grep['filter'] = t($_REQUEST['filter']);
// 				        $grep['book_id'] = $reid;
// 				        $is_ok = add_updata($grep,'TxtFilter');
				        //生成章节，
				        $filter = t($_REQUEST['filter']);
				        $chapter = t($_REQUEST['chapter']);
				        $list = $this->gather->readfile('D:/Web/Novel/Uploads/Novel/2014-11/www.kandianshu.com2014111810110950cd7.txt','/^(\d*)(.*)/');
				        dump($list);
				    }
			}
		}
		
		function edit(){
		    $this->display();
		}
		
		function _addall($_bid =1){
			$m = Book::_getChapterTable($_bid);
		}
	}