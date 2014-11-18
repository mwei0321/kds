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
			switch ($method) {
				case 'txt' :
				    $_FILES['file']['name'] && $file = fileUpload('Novel');
				    $name = substr($file['name'],0,strrpos($file['name'],'.'));
				    if($file['savename']){
// 				        //插入小说基本信息
// 				        $data = array();
// 				        $data['cateid'] = intval($_REQUEST['cateid']);
// 				        $data['title'] = text($_REQUEST['title']);
// 				        $data['author'] = text($_REQUEST['author']);
// 				        $data['status'] = 0;
// 				        $data['end_status'] = intval($_REQUEST['end_status']);
// 				        $data['uptime'] = time();
// 				        $data['title'] = $_REQUEST['title'] ? text($_REQUEST['title']) : $name;
// 				        $reid = add_updata($data,'Book');
// 				        //插入到附件，及全本文件
// 				        $attach = array();
// 				        $attach['book_id'] = $reid;
// 				        $attach['size'] = $file['size'] * 0.001;
// 				        $attach['name'] = $_REQUEST['title'] ? text($_REQUEST['title']) : $name;
// 				        $attach['path'] = $file['path'];
// 				        $attach['uptime'] = time();
// 				        $is_ok = add_updata($attach,'BookAttach');
// 				        //保存正则
// 				        $grep = array();
// 				        $grep['chapter'] = $_REQUEST['chapter'] ? text($_REQUEST['chapter']) : '/(第[一|二|三|四|五|六|七|八|九|十|百|千|万]+[章|节]{1})(.*)/';
// 				        $grep['filter'] = text($_REQUEST['filter']);
// 				        $grep['book_id'] = $reid;
// 				        $is_ok = add_updata($grep,'TxtFilter');
				        //生成章节，
				        $filter = text($_REQUEST['filter']);
				        $chapter = '/(第.*节{1})(.*)/';
				        $list = $this->gather->readfile($file['path'],$chapter,$filter);
				        $m = M('BookGatherTmp');
				        foreach ($list as $k => $v){
				        	$v['book_id'] = 1;
				        	$reid = $m->add($v);
				        }
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