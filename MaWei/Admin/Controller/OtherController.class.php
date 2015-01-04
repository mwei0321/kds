<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  网站其它设置
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
	
	class OtherController extends PubAdminController{
		protected $other;
		function _init(){
			parent::_init();
			$this->other = new Other();
			
		}
		
		/**
		* 菜单设置
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  上午11:02:48
		*/
		function index(){
			$menulist = $this->other->getHomeMenu();
			$this->assign('list',$menulist);
			$this->display();
		}
		
		/**
		* 友情链接
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  上午11:03:06
		*/
		function friendurl(){
			$list = $this->other->getFriendUrl();
			$this->assign('list',$list);
			$this->assign('count',count($list));
			$this->display();
		}
		
		/**
		* 添加、修改数据写入
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-5  上午10:55:33
		*/
		function add_upadta(){
			$reid = false;
			switch ($_REQUEST['type']) {
				case 'HomeMenu' :
					$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
					$data['pid'] = intval($_REQUEST['pid']);
					$data['name'] = text($_REQUEST['name']);
					$data['key'] = text($_REQUEST['key']);
					$data['position'] = $_REQUEST['position'] ? text($_REQUEST['position']) : 'topnavmenu';
					$data['sort'] = intval($_REQUEST['sort']);
					$data['action'] = text($_REQUEST['action']);
					$data['url'] = $_REQUEST['url'];
					$data['status'] = $_REQUEST['status'] ? intval($_REQUEST['status']) : 1;
					$reid = add_updata($data,'HomeMenu');
					$url = U('Admin/Other/index');
					break;
				case 'friendurl' :
					$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
					$data['name'] = text($_REQUEST['name']);
					$data['url'] = text($_REQUEST['url']);
					$data['sort'] = intval($_REQUEST['sort']);
					$reid = add_updata($data,'FriendUrl');
					$url = U('Admin/Other/friendurl');
					break;
				default :
					exit();
			}
			
			if($reid === false){
				$this->error('添加修改失败！',$url);
			}else{
				$this->success('添加修改成功！',$url);
			}
		}
		
		/**
		 * 添加，修改编辑页
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-9-8  下午1:17:32
		 */
		function edit(){
			$type = $_REQUEST['type'] ? text($_REQUEST['type']) : exit(null);
			$id = intval($_REQUEST['id']);
			$info = $pmenu = array();
			$template = null;
			switch ($type){
				case 'HomeMenu':
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
			$this->assign('type',$type);
			$this->assign('info',$info);
			$this->display($template);
		}
		
	}