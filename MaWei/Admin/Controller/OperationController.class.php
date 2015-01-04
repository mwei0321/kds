<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  基础操作控制器
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-10-5 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Admin\Controller;
	use Admin\Controller\PubAdminController; 
	use Library\Other;
	
	class OperationController extends PubAdminController{
		protected $type,$ids,$other;

		function _init(){
			$this->type = $_REQUEST['type'];
			if(! $this->type){
				$this->error('非法操作');
			}
			$this->ids = $_REQUEST['ids'];
			$this->other = new Other();
			$this->assign('type',$this->type);
		}
		
		/**
		* 编辑
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  下午2:36:19
		*/
		function edit(){
			$id = intval($_REQUEST['id']);
			$info = array();
			switch ($this->type){
				case 'homemenu':
					if($id){
						$info = $this->other->getMenuInfo($id);
					}
					$pmenu = $this->other->getHomeMenu(array('pid'=>1,'status'=>1));
					$this->assign('pmenu',$pmenu);
					$template = 'menuedit';
					break;
				case 'friendurl' :
					if($id){
						$info = $this->other->getFriendUrlInfo($id);
					}
					$template = 'friendedit';
					break;
				default:
					exit();
			}
			$this->assign('info',$info);
			$this->display($template);
		}
		
		/**
		* 添加、修改数据库操作
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  下午3:03:13
		*/
		function add_updata(){
			$data = array();
			$url = $model = null;
			switch ($this->type){
				case 'homemenu' :
					$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
					$data['pid'] = intval($_REQUEST['pid']);
					$data['name'] = text($_REQUEST['name']);
					$data['key'] = text($_REQUEST['key']);
					$data['position'] = $_REQUEST['position'] ? text($_REQUEST['position']) : 'topnavmenu';
					$data['sort'] = intval($_REQUEST['sort']);
					$data['action'] = text($_REQUEST['action']);
					$data['url'] = $_REQUEST['url'];
					$data['status'] = $_REQUEST['status'] ? intval($_REQUEST['status']) : 1;
					$model = 'HomeMenu';
					$url = U('Admin/Other/index');
					break;
				case 'friendurl' :
					$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
					$data['name'] = text($_REQUEST['name']);
					$data['url'] = text($_REQUEST['url']);
					$data['sort'] = intval($_REQUEST['sort']);
					$model = 'FriendUrl';
					$url = U('Admin/Other/friendurl');
					break;
				default :
					exit();
			}
			$reid = add_updata($data,$model);
			if($reid === false){
				$this->error('添加修改失败！',$url);
			}else{
				$this->success('添加修改成功！',$url);
			}
		}
		
		/**
		* 删除操作
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  下午2:36:53
		*/
		function delete(){
			$model = null;
			switch ($this->type){
				case 'friendurl' :
					$model = 'FriendUrl';
					$url = U('Admin/Other/friendurl');
					break;
				case 'homemenu' :
					$model = 'HomeMenu';
					$url = U('Admin/Other/index');
					break;
			}
			$reid = delall($this->ids, $model);
			if($reid === false){
				echo null;
			}else{
				echo 1;
			}
			exit;
		}
		
		function status(){
			
		}
		
		function recommend(){
			
		}
	}