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
	use Admin\Controller\PubAdminController;
	use Vendor\Page;
	use Library\CateTag;
			
	class CateTagController extends PubAdminController{
		protected $type;
		function _init(){
			parent::_init();
			$name = array('cate'=>'分类','tag'=>'标签','type'=>'类型');
			$this->assign('name',$name);
			
			$this->type = $_REQUEST['type'];
			$this->assign('type',$this->type);
			$this->leftmenu();
			$this->assign('recomm',array('未推荐','已推荐'));
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
			$tp = empty($_REQUEST['type']) ? 'cate' : $_REQUEST['type'];
			switch ($tp){
				case 'cate' :
					$list = $this->CateTag->level();
					$count = count($list);
					break;
				case 'tag' :
					$where = array();
					$_REQUEST['tid'] && $where['id'] = intval($_REQUEST['tid']);
					$_REQUEST['keyword'] && $where['name'] = array('LIKE','%'.text($_REQUEST['keyword']).'%');
					$count = $this->CateTag->taglist('count',$where);
					$page = new Page($count,50);
					$list = $this->CateTag->taglist("$page->firstRow,$page->listRows",$where);
					$this->assign('page',$page->show());
					break;
				case 'type' :
					$list = $this->CateTag->sourctype();
					$count = count($list);
					break;
				default:
					exit;
			}
			$this->assign('search',array('tid'=>$_REQUEST['tid'],'keyword'=>$_REQUEST['keyword']));
			$this->assign('list',$list);
			$this->assign('tp',$tp);
			$this->assign('count',$count);
			$this->assign('action',$tp  ? $tp : 'cate');
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
			switch ($this->type){
				case 'cate' :
					$info = $this->CateTag->catelist('0,1',array('id'=>intval($_REQUEST['id'])));
					break;
				case 'tag'  :
					$info = $this->CateTag->taglist('0,1',array('id'=>intval($_REQUEST['id'])));
					break;
				case 'type' :
					$info = $this->CateTag->sourctype(array('id'=>intval($_REQUEST['id'])));
					break;
				default:
					exit;
			}
			$this->assign('info',array_shift($info));
			$this->assign('catelist',S('CateList'));
			$this->assign('tp',$this->type);
			$this->display();
		}
		
		/**
		* 分类、标签数据更新、添加
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-10  下午3:52:53
		*/
		function addupdata(){
			$data = array();
			$model = null;
			
			$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
			$data['status'] = intval($_REQUEST['status']);
			$data['name'] = text($_REQUEST['name']);
			switch ($this->type){
				case 'cate' :
					if($_REQUEST['pid'] == 0){
						$data['path'] = 0;
					}else{
						$path = M('SourcCategory')->where(array('id'=>intval($_REQUEST['pid'])))->getField('path');
						$data['path'] = $path.','.$_REQUEST['pid'];
					}
					$data['pid'] = intval($_REQUEST['pid']);
					$data['icon'] = text($_REQUEST['icon']);
					$model = 'SourcCategory';
					break;
				case 'tag' :
					$model = 'SourcTag';
					break;
				case 'type':
					$model = 'SourcType';
					break;
				default:
					break;
			}
			
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
			switch ($this->type){
				case 'cate' :
					$reid = status('SourcCategory', $_REQUEST['ids'],$status);
				case 'tag'  :
					$reid = status('SourcTag', $_REQUEST['ids'],$status);
				case 'type' :
					$reid = status('SourcType', $_REQUEST['ids'],$status);
				default:
					exit;
			}
			//状态更新返回
			if($reid === false){
				$msg['status'] = null;
				$msg['msg'] = $status ? '显示失败！' : '隐藏失败！';
			}else{
				$msg['status'] = $status ? 1 : 2;
				$msg['msg'] = $status ? '已显示' : '已隐藏';
			}
			echo json_encode($msg);
			exit;
		}
		
		/**
		* 推荐
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-4  下午10:39:37
		*/
		function recommend(){
			$recomm = intval($_REQUEST['recomm']) == 1 ? 0 : 1;
			$reid = recommend('SourcCategory',$_REQUEST['ids'],$recomm);
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