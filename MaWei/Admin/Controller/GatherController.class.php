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
    use Library\Edit;
		
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
		    $_REQUEST['txtid'] && $txtid = intval($_REQUEST['txtid']);
		    $txtid && $where['txtid'] = $txtid;
		    $this->keyword && $where['title'] = array('LIKE',"%$this->keyword%");
		    $count = $this->gather->getTxtChapter($where);
		    $page = new Page($count,50,array('txtid'=>$txtid,'keyword'=>$this->keyword));
		    $list = $this->gather->getTxtChapter($where,"$page->firstRow,$page->listRows");
			
		    $this->assign('list',$list);
		    $this->assign('page',$page->show());
		    $this->assign('count',$count);
		    $this->assign('txtid',$txtid);
		    $this->display();
		}
		
		function tmpNovel(){
		    $m = M('Book');
		    $data = array();
		    $num = intval($_REQUEST['num']);
		    if($num > 0){
		        for($i = 0;$i < $num;$i++){
		            $data[$i]['cateid'] = rand(1,8);
		            $data[$i]['name'] = randString(array(4,8),4);
		            $data[$i]['words_num'] = rand(100000, 5000000);
		            $data[$i]['comment_num'] = rand(100, 50000);
		            $data[$i]['click_num'] = rand(100, 500000);
		            $data[$i]['uptime'] = rand(1316376049, time());
		            $data[$i]['author'] = randString(array(2,4),4);
		            $data[$i]['grade'] = rand(6,10);
		            $data[$i]['intro'] = randString(array(10,30),4);
		        }
		        $m->addAll($data);
		    }
		}
		
		function tmpClickLog(){
		    $m = M('BookClickLog');
		    $data = array();
		    $num = intval($_REQUEST['num']);
    	    if($num > 0){
    	       for($i = 0;$i < $num;$i++){
        		    $data[$i]['book_id'] = rand(1000,1500 );
        		    $data[$i]['uid'] = rand(1,500);
        		    $data[$i]['uptime'] = rand((time() - 3600 * 20),time());
    	       		$data[$i]['cateid'] = rand(1,8);
    	       }
    	       $m->addAll($data);
    	    }
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
		
		function url(){
		    $this->display();
		}
		
		function webcate(){
		    $list = $this->gather->getWebCate('1000');
		    $url = 'http://www.biquge.la/book/1344/';
		    $filter = array('title'=>array('a','text'));
		    $area = array('#list','dd');
		    $this->assign('list',$list);
		    $this->display();
		}
		
		function webname(){
		    $count = $this->gather->getWebName();
		    $page = new Page($count,50);
		    $list = $this->gather->getWebName("$page->firstRow,$page->listRows");
		    
		    $this->assign('count',$count);
		    $this->assign('list',$list);
		    $this->assign('page',$page->show());
		    $this->display();
		}
		
		function webchapter(){
		    $count = $this->gather->getWebChapter();
		    $page = new Page($count,50);
		    $list = $this->gather->getWebChapter("$page->firstRow,$page->listRows");
		
		    $this->assign('count',$count);
		    $this->assign('list',$list);
		    $this->assign('page',$page->show());
		    $this->display();
		}
		
		/**
		 * 
		 * @param array
		 * @param string 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-10 下午5:03:51
		 */
		function _filter($_reg){
		    $tmp = explode('|', $_reg);
		    $data = array();
		    foreach ($tmp as $k => $v){
		        if($v){
		            $t = explode('$', $v);
		            $data[$t['0']] = explode('-',$t['1']);
		        }
		    }
		    return $data;
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
		    $m = M('TxtChapterTmp');
		    $i = 0;
		    $count = count($list);
		    foreach ($list as $k => $v){
		    	if(text($v['content'])){
		    		$v['txtid'] = $id;
		    		$v['name'] = $info['name'];
		    		$reid = $m->add($v);
// 		        echo $m->getLastSql();exit;
		    		$reid !== false && $i++;
		    	}
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
				        $grep['chapter'] = $_REQUEST['chapter'] ? text($_REQUEST['chapter']) : '/(^第[\d|一|二|三|四|五|六|七|八|九|十|百|千|万]+章{1})(.*)/';
				        $grep['filter'] = text($_REQUEST['filter']);
				        $grep['name'] = $_REQUEST['title'] ? text($_REQUEST['title']) : $name;
				        $grep['author'] = text($_REQUEST['author']);
				        $grep['path'] = $file['path'];
				        $grep['cateid'] = intval($_REQUEST['cateid']);
				        $grep['uptime'] = time();
				        $grep['size'] = $file['size'];
				        $is_ok = add_updata($grep,'TxtFile');
				    }
				    break;
				case 'chapter' :
				    $data = array();
				    $ids = $_REQUEST['ids'];
				    $bookid = intval($_REQUEST['bookid']);
				    $list = $this->gather->getTxtChapter(array('id'=>array('IN',$ids)),'0,10000');
				    $count = count($list);
				    foreach ($list as $k => $v){
				        $data[$k]['chapter'] = $v['chapter'];
				        $data[$k]['content'] = $v['content'];
				        $data[$k]['title'] = $v['title'];
				        $data[$k]['ctime'] = time();
				        $data[$k]['book_id'] = $bookid;
// 				        $reid = add_updata($data,'BookChapterT1');
				    }
				    $m = Book::_getChapterTable($bookid);
				    $reid = $m->addAll($data);
// 				    echo $m->getLastSql();
                    echo '共选择了 '.$count.' 条，其中插入成功的有    '.$reid.'条记录';
                    exit;
				    break;
				case 'novel' :
				    //插入小说基本信息
				    $data = array();
				    $data['cateid'] = intval($_REQUEST['cateid']);
				    $data['name'] = text($_REQUEST['title']);
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
				case 'addinfo' :
					$data = array();
					$info = $this->gather->txtFile(1,array('id'=>intval($_REQUEST['id'])));
					$info = array_shift($info);
					$data['name'] = $info['name'];
					$data['cateid'] = $info['cateid'];
					$data['status'] = 0;
					$data['uptime'] = time();
					$reid = add_updata($data,'Book');
					$attach['book_id'] = $reid;
					$attach['path'] = $info['path'];
					$attach['size'] = $info['size'];
					$attach['uptime'] = time();
					$attach['name'] = $info['name'];
 					$reid = add_updata($attach,'BookAttach');
					if($reid){
						$reid = M('TxtFile')->where(array('id'=>intval($_REQUEST['id'])))->setField(array('status'=>1));
						echo $reid ? 1 : null;exit;
					}
					echo null;
					break;
 				case 'txtchapter' :
					$data = array();
					$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
					$data['name'] = text($_REQUEST['name']);
					$data['chapter'] = text($_REQUEST['chapter']);
					$data['title'] = text($_REQUEST['title']);
					$data['content'] = $_REQUEST['content'];
					$data['uptime'] = time();
					$is_ok = add_updata($data,'TxtChapterTmp');
					break;
 				case 'addwebcate' :
 				    $data = array();
 				    $_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
 				    $data['cateid'] = intval($_REQUEST['cateid']);
 				    $data['cate_url'] = text($_REQUEST['cateurl']);
 				    $data['web_url'] = text($_REQUEST['web_url']);
 				    $data['page_param'] = text($_REQUEST['page']);
 				    $data['name_area'] = text($_REQUEST['name_area']);
 				    $data['chapter_area'] = text($_REQUEST['chapter_area']);
 				    $data['content_area'] = text($_REQUEST['content_area']);
 				    $data['name_filter'] = text($_REQUEST['name_filter']);
 				    $data['chapter_filter'] = text($_REQUEST['chapter_filter']);
 				    $data['content_filter'] = text($_REQUEST['content_filter']);
 				    $data['uptime'] = time();
 				    $data['charset'] = strtoupper(text($_REQUEST['charset']));
 				    $is_ok = add_updata($data,'GatherWebCate');
 				    break;
 				case 'webcate' :
 				    $list = $this->gather->getWebCate('all',array('id'=>array('IN',explode(',', $_REQUEST['ids']))));
 				    foreach ($list as $k => $v){
 				        $data = array();
 				        //小说名采集
 				        $temp = $this->gather->getUrlGather($v['cate_url'], $this->_filter($v['name_filter']), explode('-', $v['name_area']),$v['charset']);
 				        foreach ($temp as $key => $val){
 				            $data[$key]['name'] = $val['name'];
 				            $data[$key]['url'] = strpos('http', $val['url']) !== false ? $val['url'] : $v['web_url'].$val['url'];
 				            $data[$key]['author'] = $val['author'];
 				            $data[$key]['chapter_filter'] = $v['chapter_filter'];
 				            $data[$key]['chapter_area'] = $v['chapter_area'];
 				            $data[$key]['content_area'] = $v['content_area'];
 				            $data[$key]['content_filter'] = $v['content_filter'];
 				            $data[$key]['cateid'] = $v['cateid'];
 				            $data[$key]['charset'] = $v['charset'];
 				        }
 				        M('GatherWebName')->addAll($data);
 				    }
 				    break;
 				case 'webchapter' : 
 				    $list = $this->gather->getWebName('all',array('id'=>array('IN',explode(',', $_REQUEST['ids']))));
 				    foreach ($list as $k => $v){
 				        $chapter = array();
 				        $temp = $this->gather->getUrlGather($v['url'], $this->_filter($v['chapter_filter']), explode('-', $v['chapter_area']),$v['charset']);
 				        foreach ($temp as $key => $val){
 				            if($val['title'] && $val['url']){
 				                $chapter[$key]['title'] = $val['title'];
 				                $chapter[$key]['name'] = $v['name'];
 				                $chapter[$key]['cateid'] = $v['cateid'];
 				                $chapter[$key]['url'] = strpos('http', $val['url']) !== false ? $val['url'] : $v['url'].$val['url'];
 				                $chapter[$key]['filter'] = $v['content_filter'];
 				                $chapter[$key]['charset'] = $v['charset'];
 				            }
 				        }
 				        M('GatherWebChapter')->addAll($chapter);
 				    }
 				    break;
 				case 'webcontent' :
 				    $list = $this->gather->getWebChapter('all',array('id'=>array('IN',explode(',', $_REQUEST['ids']))));
 				    $content = array();
 				    $bad = array();
 				    $good = 0;
 			        $m = M('GatherWebChapter');
 			        foreach ($list as $k => $v){
 			            if(empty($v['content'])){
 			                $tmp = $this->gather->getUrlGather($v['url'], $this->_filter($v['filter']),null,$v['charset']);
 			                empty($tmp['content']) && $bad[] = $v['id'];
 			                $content['content'] = $tmp['content'];
 			                $content['uptime'] = time();
 			                $reid = $m->where('id='.$v['id'])->save($content);
 			                $reid && $good++;
 			            }
 			        }
 			        echo '共采集了'.$good.' 条，其中采集失败的有'.count($bad).'条，ID为'.implode(',', arr2to1($list));
 			        break;
			}
			$this->success('处理成功');
		}
		
		
		/**
		* //编辑页
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-11-22  下午12:09:19
		*/
		function edit(){
		    $method = text($_REQUEST['method']);
		    $id = $_REQUEST['id'];
		    $info = null;
		    switch ($method){
		        case 'txt' :
		            $id && $info = array_shift($this->gather->txtFile('0,1',array('id'=>$id)));
		            break;
		        case 'tmpchapter' :
		        	$id && $info = array_shift($this->gather->getTxtChapter(array('id'=>$id),1));
		        	break;
		        case 'webchapter' :
		            break;
		        case 'wcate' :
		            $id && $info = array_shift($this->gather->getWebCate(1,array('id'=>$id)));
		            break;
		        	
		    }
		    $this->assign('info',$info);
		    $this->assign('method',$method);
		    $this->display($method);
		}
		
		/**
		* 删除
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-11-22  下午12:38:03
		*/
		function delete(){
			$ids = $_REQUEST['ids'];
			$method = text($_REQUEST['method']);
			$model = $m = null;
			switch ($method){
				case 'chapter' :
					$model = 'TxtChapterTmp';
					break;
			}
			if($ids < 0){
				$m = M("$model")->where(1)->delete();
			}else{
				$m = M("$model")->delete($ids);
			}
// 			echo M("$model")->getLastSql();
			if($m === false){
				echo null;
			}else{
				echo 1;
			}
			exit;
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
		
		function _addall($_bid =1){
			$m = Book::_getChapterTable($_bid);
		}
	}