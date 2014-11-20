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
    use Vendor\Page;
		
	class GatherController extends PubAdminController{
		protected $gather,$keyword;
		function _init(){
			parent::_init();
			$this->gather = new Gather();
			
			//关键字
			$this->keyword = text($_REQUEST['keyword']);
			$this->assign('keyword',$this->keyword);
			$this->assign('status',array('已隐藏','已显示'));
		}
		
		/**
		 * 临时章节列表
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-11-20 下午3:28:01
		 */
		function index(){
		    $where = array();
		    $count = $this->gather->getNovelList($where);
		    $page = new Page($count,50);
		    $list = $this->gather->getNovelList($where,"$page->firstRow,$page->listRows");
			
		    $this->assign('list',$list);
		    $this->assign('page',$page->show());
		    $this->assign('count',$count);
		    $this->display();
		}
		
		/**
		 * txtfile列表
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-11-19 下午2:21:04
		 */
		function txtfile(){
		    //搜索条件处理
		    $id = $_REQUEST['id'];
		    $where = array();
		    $id && $where['id'] = $id;
		    $this->keyword && $where['name'] = array('LIKE',"%$this->keyword%");
		    //列表数据处理
		    $count = $this->gather->txtFile('count',$where);
		    $page = new Page($count, 50,array('keyword'=>$this->keyword));
		    $list = $this->gather->txtFile("$page->firstRow,$page->listRows",$where);
		    
		    $this->assign('id',$id);
		    $this->assign('list',$list);
		    $this->assign('count',$count);
		    $this->display();
		}
		
		/**
		 * 生成章节
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-11-19 下午3:28:57
		 */
		function chapter(){
		    $id = $_REQUEST['id'];
		    $info = $this->gather->txtFile(1,array('id'=>$id));
		    $info = array_shift($info);
		    $list = $this->gather->readfile($info['path'],$info['chapter'],$info['filter']);
		    $m = M('BookChapterTmp');
		    $i = 0;
		    $count = count($list);
		    foreach ($list as $k => $v){
		        $v['txtid'] = $id;
		        $v['name'] = $info['name'];
		        $reid = $m->add($v);
// 		        echo $m->getLastSql();exit;
		        $reid !== false && $i++;
		    }
		    echo '小说 《'.$info['name'].'》共导入 '. $count. ' 章,其中导入成功有  '.$i.' 章';
		}
		
		/**
		 * 文件导入处理
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-11-19 下午2:21:46
		 */
		function dispose(){
			$method = text($_REQUEST['method']);
			$bookid = intval($_REQUEST['id']);
			switch ($method) {
				case 'txt' : //txt文件导入处理
				    $_FILES['file']['name'] && $file = fileUpload('Novel');
				    $name = substr($file['name'],0,strrpos($file['name'],'.'));
				    if($file['savename']){
				        //保存正则
				        $grep = array();
				        $grep['chapter'] = $_REQUEST['chapter'] ? text($_REQUEST['chapter']) : '//(第.*章{1})(.*)/';
				        $grep['filter'] = text($_REQUEST['filter']);
				        $grep['name'] = $_REQUEST['title'] ? text($_REQUEST['title']) : $name;
				        $grep['path'] = $file['path'];
				        $grep['cateid'] = intval($_REQUEST['cateid']);
				        $grep['uptime'] = time();
				        $is_ok = add_updata($grep,'TxtFile');
				    }
				    break;
				case 'novel' :
				    //插入小说基本信息
				    $data = array();
				    $data['cateid'] = intval($_REQUEST['cateid']);
				    $data['title'] = text($_REQUEST['title']);
				    $data['author'] = text($_REQUEST['author']);
				    $data['status'] = 0;
				    $data['end_status'] = intval($_REQUEST['end_status']);
				    $data['uptime'] = time();
				    $data['title'] = $_REQUEST['title'] ? text($_REQUEST['title']) : $name;
				    $reid = add_updata($data,'Book');
				    //插入到附件，及全本文件
				    $attach = array();
				    $attach['book_id'] = $reid;
				    $attach['size'] = $file['size'] * 0.001;
				    $attach['name'] = $_REQUEST['title'] ? text($_REQUEST['title']) : $name;
				    $attach['path'] = $file['path'];
				    $attach['uptime'] = time();
				    $is_ok = add_updata($attach,'BookAttach');
				    break;
			}
			$this->success('处理成功');
		}
		
		//编辑页
		function edit(){
		    $method = text($_REQUEST['method']);
		    $id = intval($_REQUEST['id']);
		    $info = null;
		    switch ($method){
		        case 'txt' :
		            $id && $info = $this->gather->txtFile('0,1',array('id'=>$id));
		            break;
		    }
		    $this->assign('info',$info);
		    $this->assign('method',$method);
		    $this->display($method);
		}
		
		function _addall($_bid =1){
			$m = Book::_getChapterTable($_bid);
		}
	}