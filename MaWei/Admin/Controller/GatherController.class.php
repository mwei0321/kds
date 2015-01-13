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
		    $page = new Page($count,10);
		    $list = $this->gather->getWebName("$page->firstRow,$page->listRows");
		    
		    $this->assign('count',$count);
		    $this->assign('list',$list);
		    $this->assign('page',$page->show());
		    $this->display();
		}
		
		function webchapter(){
		    $pagenum = $_REQUEST['pagenum'] ? intval($_REQUEST['pagenum']) : 100;
		    $keyword = text($_REQUEST['keyword']);
		    $where = array();
		    $_REQUEST['nid'] && $where['book_id'] = intval($_REQUEST['nid']);
		    $keyword && $where['title'] = array('LIKE',"%$keyword%");
		    
		    $count = $this->gather->getWebChapter('count',$where);
		    $page = new Page($count,$pagenum,array('keyword'=>$keyword,'nid'=>$_REQUEST['nid'],'pagenum'=>$pagenum));
		    $list = $this->gather->getWebChapter("$page->firstRow,$page->listRows",$where,'id ASC');
		
		    $this->assign('count',$count);
		    $this->assign('list',$list);
		    $this->assign('nid',$_REQUEST['nid']);
		    $this->assign('pagenum',$pagenum);
		    $this->assign('keyword',$keyword);
		    $this->assign('page',$page->show());
		    $this->display();
		}
		
		/**
		 * 解析采集规则
		 * @param string $_reg 采集规则
		 * @return array $data
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
 				        $temp = getUrlGather($v['cate_url'], $this->_filter($v['name_filter']), explode('-', $v['name_area']),$v['charset']);
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
 				        $temp = getUrlGather($v['url'], $this->_filter($v['chapter_filter']), explode('-', $v['chapter_area']),$v['charset']);
 				        foreach ($temp as $key => $val){
 				            //检查章节是否已采集
 				            if(!$this->gather->checkChpater($v['id'],$val['title'])){
 				                if($val['title'] && $val['url']){
 				                    $chapter[$key]['title'] = $val['title'];
 				                    $chapter[$key]['book_id'] = $v['id'];
 				                    $chapter[$key]['name'] = $v['name'];
 				                    $chapter[$key]['url'] = strpos('http', $val['url']) !== false ? $val['url'] : $v['url'].$val['url'];
 				                    $chapter[$key]['filter'] = $v['content_filter'];
 				                    $chapter[$key]['charset'] = $v['charset'];
 				                }
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
 			                $tmp = getUrlGather($v['url'], $this->_filter($v['filter']),null,$v['charset']);
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
		}
		
		/**
		* qs采集列表
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2015-1-10  上午11:52:35
		*/
		function qisuulist(){
		    $count = $this->gather->getQisuu();
		    $page = new Page($count,50);
		    $list = $this->gather->getQisuu(1,"$page->firstRow,$page->listRows",'id DESC');
// 		    dump($list);
		    $this->assign('list',$list);
		    $this->assign('page',$page->show());
		    $this->display();
		}
		
		/**
		 * qisuu
		 * @param array
		 * @param string 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2015-1-4 下午4:07:25
		 */
		function qisuu(){
		    $method = $_REQUEST['method'];
// 		    $gpage = $_REQUEST['gpage'] ? intval($_REQUEST['gpage']) : 100;
// 		    $cateid = $_REQUEST['cateid'] ? intval($_REQUEST['cateid']) : 1;
		    switch ($method){
		        case 'gather' :
		            $m = M('QisuuGather');
		            $config = explode('|',file_get_contents('gpage.txt'));
		            $gpage = intval($config['0']);
		            $cateid = intval($config['1']);
		            $url = text($config[2]).'index_'.$gpage.'.html';
		            if($gpage > 2){
		                $log = fopen('./log/gathername'.$cateid.'.txt', 'a');
		                fwrite($log, "\r\n".'------------ page'.$gpage.'------------'."\r\n\r\n");
		                $filter = 'name$.mainSoftName>a-text|url$.mainSoftName>a-href';
		                $list = getUrlGather($url, $this->_filter($filter),array('#listbox','.mainListInfo'),'gb2312');
		                foreach ($list as $k => $v){
		                    $data = array();
		                    $data['name'] = preg_filter('/《|》|全集|-|_/', '', $v['name']);
		                    $data['url'] = 'http://www.qisuu.com'.$v['url'];
		                    $data['cateid'] = $cateid;
		                    if($m->add($data)){
		                        fwrite($log, $data['name'].' --  '.$data['url'].' --  '.date('Y-m-d H:m:s')."\t\n");
		                    }
		                }
		                fclose($log);
		                file_put_contents('gpage.txt', ($gpage-1)."|$cateid|".text($config[2]));
		            }
                    echo $url;
                    echo $gpage;
		            break;
		        case 'file' :
		        	$id = intval($_REQUEST['id']);
		        	$where = $id ? array('id'=>$id) : array('is_dispose'=>0);
		            $data = $temp = array();
		            $tmp = $this->gather->getQisuu($where,1);
                    //采集
		            $filter = array('cover'=>array('#downInfoArea>div>a>img','src'),'intro'=>array('#clickeye_content','html'),'author'=>array('#downInfoArea>.downInfoRowL>a','text'));
		            $gather = getUrlGather($tmp['url'], $filter,'','GB2312');
// 		            dump($gather);exit;
                    //
                    $data['name'] = $tmp['name'];
                    $data['author'] = $temp['author'] = $gather['author'];
                    $data['intro'] = $temp['intro'] = $gather['intro'];
                    $data['uptime'] = $data['ctime'] = time();
                    $data['cateid'] = $tmp['cateid'];
		            //下载封面
		            $data['cover'] = downFile('http://www.qisuu.com'.$gather['cover'],UPLOAD_PATH.'Cover/'.date('Ym').'/');
                    //添加到小说主表
		            $bookid = M('Book')->add($data);
                    $fpath = NOVEL_PATH.date('Y').'/'.$bookid.'/';
                    createDir($fpath);
		            //下载文件
                    $html = file_get_contents($tmp['url']);
		            preg_match('/.*thunderResTitle=\'(.*)\' thunderType=/',$html ,$matches);
                    $file = downFile($matches['1'],'Tmp/',null,'0777');
                    //解压下载文件
                    $ofile = rar_open($file);
                    $f_list = rar_list($ofile);
                    $filepath = null;
                    foreach ($f_list as $k => $v){
                        if(getFileExeName($v->getName()) == 'txt' && $v->getUnpackedSize() > 2000){
                            $v->extract($fpath);
                            $filepath = $fpath.$v->getName();
                            chmod($filepath, '0755');
                        }
                    }
                    rar_close($ofile);
                    unlink($file);
                    //写日志
                    writeFile($tmp['id'].'  ----  '.$tmp['name'].'  -----  '.$filepath.' ----- '.date('Y-m-d H:m:s')."\n" , 'log/download.txt',1);
                        
		            //添加章节
		            //处理文件章节
		            $list = $this->gather->readfile($filepath);
		            
		            $chapter = getChapterTable($bookid);
		            $chapter = M($chapter);
		            //写入章节到数据库
		            if(count($list) > 50){
		                //更新小说文件
		                unlink($v['filepath']);
		                writeFile($list['file'],$fpath.$v['name'].'.txt');
		                unset($list['file']);
		                //提取章节
		                foreach($list as $k => $v){
		                    $tmp = array();
		                    $tmp['title'] = $v['title'];
		                    $tmp['book_id'] = $bookid;
		                    $tmp['uptime'] = time();
		                    $chapter->add($tmp);
		                    writeFile($v['content'], $fpath.$v['title'].'.txt');
		                }
		                $temp['is_dispose'] = 2;
		            }else {
		                $temp['is_dispose'] = 1;
		            }
		            //更新qisuu表
		            $temp['filepath'] = $filepath;
		            $temp['book_id'] = $bookid;
		            M('QisuuGather')->where('id='.$tmp['id'])->save($temp);
		            echo M('QisuuGather')->getlastsql();
		            break;
		        case 'img':
		            $tmp = $this->gather->getQisuu(array('cover'=>0),1);
		            $filter = array('cover'=>array('#downInfoArea>div>a>img','src'));
		            $gather = getUrlGather($tmp['url'], $filter,'','GB2312');
		            //下载封面
		            $data['cover'] = downFile('http://www.qisuu.com'.$gather['cover'],'Cover');
		            M('QisuuGather')->where('id='.$tmp['id'])->save($data);
		            exit;
		            break;
		        case 'view':
		            $id = intval($_REQUEST['id']);
		            $info = M('QisuuGather')->field('id,filepath,filter_preg,filter_keyword')->where('id='.$id)->find();
		            $f = fopen($info['filepath'], 'r');
		            $str = fread($f, 1000);
		            $content = autoCharset($str);
		            fclose($f);
		            
		            $this->assign('id',$id);
		            $this->assign('info',$info);
		            $this->assign('content',$content);
		            $this->display('fileview');
		            break;
		        case 'filter' :
		            if($_REQUEST['preg']){
		                $data = array();
		                $id = intval($_REQUEST['id']);
		                $data['filter_preg'] = $_REQUEST['preg'];
		                $data['filter_keyword'] = $_REQUEST['keyword'];
		                $reid = M('QisuuGather')->where(array('id'=>$id))->save($data);
                        echo '添加正则成功!';
// 		                echo M('QisuuGather')->getLastsql();
		            }else{
		                echo '过滤归则不能为空';
		            }
		            break;
		        case 'del':
		            $where = $_REQUEST['ids'] < 0 ? 1 : array('id'=>array('IN',$_REQUEST['ids']));
		            $list = $this->gather->getQisuu($where,'all');
		            $m = M('QisuuGather');
		            foreach ($list as $k => $v){
		                unlink($v['filepath']);
		                $reid = $m->where('id='.$v['id'])->delete();
		            }
		            echo $reid;
		            break;
		        case 'send':
		        	$ids = $_REQUEST['ids'];
		            $list = $this->gather->getQisuu(array('id'=>array('IN',$ids),'is_dispose'=>1,),'all');
		            $m = M('Book');
		            $i = 0;
		            foreach ($list as $k => $v){
		                if($v['book_id']){
		                   $data = array();
		                   $data['name'] = $v['name'];
		                   $data['cover'] = $v['cover'];
		                   $data['intro'] = $v['intro'];
		                   $data['end_status'] = 1;
		                   $data['author'] = $v['author'];
		                   $data['cateid'] = $v['cateid'];
		                   $data['uptime'] = $data['ctime'] = time();
		                   $reid = $m->add($data);
		                   if($reid == false){
		                       $reid = $m->field('id')->where(array('name'=>$v['name']))->find();
		                       $reid = $reid['id'];
		                   }
		                }
		                //章节存放路径
                        $path = NOVEL_PATH.date('Y',$data['ctime']).'/'.$reid.'/';
                        createDir($path);
                        //读取文件
                        $list = $this->gather->readfile($v['filepath'],$v['filter_preg'],$v['filter_keyword']);
                        unlink($v['filepath']);
                        writeFile($list['file'],$path.$v['name'].'.txt');
                        unset($list['file']);
                        $chapter = getChapterTable($reid);
                        $chapter = M($chapter);
                        $i = 1;
                        foreach ($list as $k => $v){
                       	   if($v['content']){
	                       	   	$tmp = array();
	                       	   	$tmp['title'] = $v['title'];
	                       	   	$tmp['book_id'] = $reid;
	                       	   	$tmp['uptime'] = time();
                                $chapter->add($tmp);
	                       	   	writeFile($v['content'], $path.$i.'.txt');
	                       	   	$i++;
                       	   }
                        }
                        $i++;
                        //更新发布状态
                        M('QisuuGather')->where('id='.$v['id'])->setField('is_dispose',2);
		            }
		            echo '共发布成功'.$i.'章节';
		            exit;
		            break;
		        default :
		            exit;
		    }
		}
		
		/**
		 * 发布小说
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-12-19 下午4:59:56
		 */
		function send (){
		    $ids = $_REQUEST['ids'];
		    switch ($_REQUEST['method']){
		        case 'novel' :
		            $list = $this->gather->getWebName('all',array('id'=>array('IN',$ids)));
		            foreach ($list as $k => $v){
		                $bookid = $v['book_id'];
		                if($v['book_id'] == 0){
		                    $data = array();
		                    $data['cateid'] = $v['cateid'];
		                    $data['name'] = $v['name'];
		                    $data['author'] = $v['author'];
		                    $data['uptime'] = time();
		                    $data['status'] = 1;
		                    $bookid = add_updata($data,'Book');
		                    //是否发布成功到小说主表，
		                    if(intval($bookid) > 0){
		                        //更新发布小说ID
		                        $tmp = array();
		                        $tmp['id'] = $v['id'];
		                        $tmp['book_id'] = $bookid;
		                        $reid = add_updata($tmp,'GatherWebName');
		                    }
		                }
		                
                        //发布章节
                        //取出小说章节
                        $chapter = $this->gather->getWebChapter('all',array('book_id'=>$v['id'],'is_send'=>0),'id ASC');
                        $chaptertable = getChapterTable($v['book_id']);
                        $m = M("$chaptertable");
                        $t = M('GatherWebChapter');
                        foreach ($chapter as $key => $val){
                            if($val['content']){
                            	$data = array();
                            	$data['book_id'] = $bookid;
                                $data['title'] = $val['title'];
                                $data['content'] = $val['content'];
                                $data['uptime'] = time();
                                $reid = $m->add($data);
                                if(intval($reid) > 0){
                                	$t->save(array('id'=>$val['id'],'is_send'=>1));
                                }
                            }
                        }
		            }
		            echo 'success';
		            break;
		        case 'chapter' :
		            $ids = $_REQUEST['ids'];
		            $chapter = $this->gather->getIdsChapter($ids);
		            $data = array();
		            foreach ($chapter as $k => $v){
		                $data[$k]['title'] = $v['title'];
		                $data[$k]['content'] = $v['content'];
		            }
		            
		    }
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
			echo M("$model")->getLastSql();
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
