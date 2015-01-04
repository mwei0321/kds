<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain: 
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: MaWei <1123265518@qq.com>
	*	+----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-10-23 下午5:34:32
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Novel\Controller;
	use Novel\Controller\IniController;
	
	class IndexController extends IniController{
		
		function _init(){
			parent::_init();
// 			dump($this->catelist);
		}
		
		function index(){
		    //分类推荐排行榜
// 		    $reclist = array();
// 		    foreach ($this->catelist as $k => $v){
// 		    	if($k < 5){
// 		    		//最新评分高的小说
// 		    		$reclist[$v['id']]['left'] = $this->book->book(5,array('cateid'=>$v['id']),'grade DESC,id DESC');
// 		    		//日点量排行榜
// 		    		$reclist[$v['id']]['right']['day'] = $this->book->getClickHot(array('cateid'=>$v['id']));
// 		    		//周点量排行榜
// 		    		$reclist[$v['id']]['right']['week'] = $this->book->getClickHot(array('cateid'=>$v['id']),'week');
// 		    		//月点量排行榜
// 		    		$reclist[$v['id']]['right']['month'] = $this->book->getClickHot(array('cateid'=>$v['id']),'month');
// 		    	}
// 		    }
		    
		    //最新更新的小说章节
		    if(!S('NewChapter')){
		    	$tmp = $this->book->getNewChapter();
		    	S('NewChapter',$tmp,86400);
		    }
		    $newchapter = S('NewChapter');
		    
		    //最新推荐小说
		    if(!S('Recommend')){
		    	$tmp = $this->book->book('16',array('status'=>1),'recommend DESC,id DESC');
		    	S('Recommend',$tmp,86400);
		    }
		    $recommend = S('Recommend');
		    
		    //分类推荐小说
			if(!S('CateRecomm')){
				$caterecommend = array();
				foreach (S('CateList') as $k => $v){
					//左边推荐
					$caterecommend[$k]['left'] = $this->book->book(6,array('status'=>1,'cateid'=>$k),'recommend DESC,uptime DESC');
					//右边排行
					$caterecommend[$k]['right'] = $this->book->getClickHot(array('cateid'=>$k),'year');
					if($k > 7)  break;
				}
				S('CateRecomm',$caterecommend,172800);
// 				print_r(S('CateRecomm'));
			}

			$this->assign('reclist',$reclist);
			$this->assign('recommend',$recommend);
			$this->assign('newchapter',$newchapter);
			$this->assign('caterecomm',S('CateRecomm'));
			$this->display();
		}
		
		/**
		* 小说章节列表
		* @author MaWei (http://www.phpyrb.com)
		* @date 2015-1-2  下午8:20:00
		*/
		function chapter(){
		    $bookid = intval($_REQUEST['nid']);
		    if($bookid){
		        //小说详情
		        $info = $this->book->bookInfo($bookid);
		        if(empty($info)){
		            $this->error('该小说不存在或已下架……',U('Index/index'));
		        }
		        //返回小说章节列表
		        $list = $this->book->getBookChapter($bookid);
		        
		        $this->assign('list',$list);
		        $this->assign('info',$info);
		    }else{
		        $this->error('该小说不存在或已下架……',U('Index/index'));
		    }
		    
		    $this->display();
		}
		
		function slist(){
			
		}
		
		/**
		* 小说详情页
		* @author MaWei (http://www.phpyrb.com)
		* @date 2015-1-2  下午8:22:05
		*/
		function info(){
			$bookid = intval($_REQUEST['nid']);
			if($bookid){
				//小说详情
				$info = $this->book->bookInfo($bookid);
				if(empty($info)){
					$this->error('该小说不存在或已下架……',U('Index/index'));
				}
				$chapter = $this->book->getBookChapter($bookid,10);
				
				$this->assign('chapter',$chapter);
				$this->assign('info',$info);
			}else{
				$this->error('该小说不存在或已下架……',U('Index/index'));
			}
			
			$this->display();
		}
		
		/**
		* 小说章节内容
		* @author MaWei (http://www.phpyrb.com)
		* @date 2015-1-2  下午8:32:56
		*/
		function content(){
			$bookid = intval($_REQUEST['nid']);
			$chapterid = intval($_REQUEST['tid']);
			if($bookid){
				//小说章节内容
				$info = $this->book->content($bookid,$chapterid);
				
				$this->assign('content',$info);
			}else{
				$this->error('该小说不存在些章节……',U('Index/chapter',array('nid'=>$bookid)));
			}
		}
	}