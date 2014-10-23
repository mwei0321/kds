<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  分类、标签管理
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
	use Library\CateTag;
			
	class CateTagController extends IniController{
		protected $catetag;
		function _init(){
			parent::_init();
			$type = array('cate'=>'分类','tag'=>'标签');
			$this->assign('type',$type);
			$this->leftmenu();
		}
		
		/**
		* 分类标签列表
		* @param  array 
		* @param  string 
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-12  下午11:02:42
		*/
		function index(){
			$tp = $_REQUEST['type'] == 'cate' || empty($_REQUEST['type']) ? 'cate' : 'tag';
			switch ($tp){
				case 'cate' :
					$list = $this->catetag->level();
					break;
				case 'tag' :
					$where = array();
					$_REQUEST['tid'] && $where['id'] = intval($_REQUEST['tid']);
					$_REQUEST['keyword'] && $where['name'] = array('LIKE','%'.text($_REQUEST['keyword']).'%');
					$count = $this->catetag->taglist('count',$where);
					$page = new Page($count,50);
					$list = $this->catetag->taglist("$page->firstRow,$page->listRows",$where);
					$this->assign('page',$page->show());
					break;
				default:break;
			}
			$this->assign('search',array('tid'=>$_REQUEST['tid'],'keyword'=>$_REQUEST['keyword']));
			$this->assign('list',$list);
			$this->assign('tp',$tp);
			$this->assign('count',$count);
			$this->assign('action',$tp == 'cate' ? 'cate' : 'tag');
			$this->display();
		}
		
		/**
		 * 左则菜单
		 * @param array
		 * @param string 
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-13 下午3:19:24
		 */
		function leftmenu(){
			$CateTag = array(
					array('分类管理',U('Admin/CateTag/index',array('type'=>'cate')),'icate'),
					array('分类添加',U('Admin/CateTag/edit',array('type'=>'cate')),'ecate'),
					array('标签管理',U('Admin/CateTag/index',array('type'=>'tag')),'itag'),
					array('标签添加',U('Admin/CateTag/edit',array('type'=>'tag')),'etag'),
			);
			$this->assign('lmenu',$CateTag);
		}
		
		/**
		* 编辑页面
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-7  上午12:10:18
		*/
		function edit(){
			$type = $_REQUEST['type'];
			if($_REQUEST['id']){
				$info = $type == 'cate' ? $this->catetag->catelist('0,1',array('id'=>intval($_REQUEST['id']))) : $this->catetag->taglist('0,1',array('id'=>intval($_REQUEST['id'])));
				$this->assign('info',array_shift($info));
			}
			$this->assign('catelist',S('CateList'));
			$this->assign('tp',$type);
			$this->assign('action',$type == 'cate' ? 'ecate' : 'etag');
			$this->display();
		}
		
		/**
		* 分类、标签数据更新、添加
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-10  下午3:52:53
		*/
		function addupdata(){
			$data = array();
			if($_REQUEST['type'] == 'cate'){
				if($_REQUEST['pid'] == 0){
					$data['path'] = 0;
				}else{
					$path = M('SourcCategory')->where(array('id'=>intval($_REQUEST['pid'])))->getField('path');
					$data['path'] = $path.','.$_REQUEST['pid'];
				}
			}
			$data['name'] = h(text($_REQUEST['name']));
			$data['status'] = $_REQUEST['status'] ? intval($_REQUEST['status']) : 1;
			$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
			$_REQUEST['type'] == 'cate' && $data['pid'] = intval($_REQUEST['pid']);
			
			$model = $_REQUEST['type'] == 'cate' ? 'SourcCategory' : 'SourcTag';
			$reid = add_updata($data,"$model");
			if($reid === false){
				$this->error('添加修改失败',U('Admin/CateTag/edit',array('type'=>$_REQUEST['type'],'id'=>intval($_REQUEST['id']))));
			}else{
				$this->success('添加修改成功',U('Admin/CateTag/index',array('type'=>$_REQUEST['type'])));
			}
		}
		
		/**
		* 设置状态
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-6  下午11:32:01
		*/
		function status(){
			$status = intval($_REQUEST['status']) == '1' ? 0 : 1;
			if($_REQUEST['type'] == 'cate'){
				$reid = status('SourcCategory', intval($_REQUEST['id']),$status);
			}else{
				$reid = status('SourcTag', intval($_REQUEST['id']),$status);
			}
			if($reid === false){
				$msg['status'] = null;
				$msg['msg'] = $status ? '显示失败！' : '隐藏失败！';
			}else{
				$msg['status'] = $status ? 1 : 2;
				$msg['msg'] = $status ? '显示' : '隐藏';
			}
			echo json_encode($msg);
			exit;
		}
		
		/**
		* 删除标签、分类
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-10  下午5:16:04
		*/
		function delect(){
			$model = $_REQUEST['type'] == 'cate' ? 'SourcCategory' : 'SourcTag';
			$reid = delall($_REQUEST['ids'],"$model");
			if($reid === false){
				echo null;
			}else{
				echo 1;
			}
			S('TagList',null);
			S('CateList',null);
			exit;
		}
	}