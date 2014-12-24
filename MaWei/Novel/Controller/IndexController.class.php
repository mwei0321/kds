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
		    $reclist = array();
		    foreach ($this->catelist as $k => $v){
		    	if($k < 5){
		    		//最新评分高的小说
		    		$reclist[$v['id']]['left'] = $this->book->book(5,array('cateid'=>$v['id']),'grade DESC,id DESC');
		    		//日点量排行榜
		    		$reclist[$v['id']]['right']['day'] = $this->book->getClickHot(array('cateid'=>$v['id']));
		    		//周点量排行榜
		    		$reclist[$v['id']]['right']['week'] = $this->book->getClickHot(array('cateid'=>$v['id']),'week');
		    		//月点量排行榜
		    		$reclist[$v['id']]['right']['month'] = $this->book->getClickHot(array('cateid'=>$v['id']),'month');
		    	}
		    }
		    
		    //最新更新的小说章节
		    $newchapter = $this->book->getNewChapter();
		    //最新推荐小说
		    $recommend = $this->book->book('16',array('status'=>1),'recommend DESC,id DESC');
		    
		    
			$this->assign('reclist',$reclist);
			$this->assign('recommend',$recommend);
			$this->assign('newchapter',$newchapter);
			$this->display();
		}
		
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
		
		function info(){
			
		}
	}