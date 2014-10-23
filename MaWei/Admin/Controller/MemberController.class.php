<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  用户管理中心
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-3 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :  http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Admin\Controller;
	use Admin\Controller\IniController;
	use Library\Member;
	use Vendor\Page;
		
	class MemberController extends  IniController{
		protected $member;
		function _init(){
			parent::_init();
			$this->member = new Member();
			$this->assign('status',array('已禁用','已启用'));
		}
		
		/**
		 * 会员列表显示
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-4 下午3:24:03
		 */
		function index(){
			$where = array();
			$_REQUEST['uid'] && $seach['uid'] = $where['id'] = intval($_REQUEST['uid']);
			$_REQUEST['keyword'] && $where['uname|email'] = array('LIKE','%'.text($_REQUEST['keyword']).'%');
			$_REQUEST['keyword'] && $seach['keyword'] = text($_REQUEST['keyword']);
// 			$where['email'] = array('LIKE','%'.text($_REQUEST['keyword']).'%');
			$countuser = $this->member->getMemberList('count',$where);
			$page = new Page($countuser,4);
			$userlist = $this->member->getMemberList("$page->firstRow,$page->listRows",$where);
			$this->assign('ulist',$userlist);
			$this->assign('page',$page->show());
			$this->assign('sex',array('男','女'));
			$this->assign('count',$countuser);
			$this->assign('action','index');
			$this->assign('seach',$seach);
			$this->display();
		}
		
		/**
		 * 会员添加修改编辑页
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-4 下午3:23:07
		 */
		function edit(){
			$uid = intval($_REQUEST['uid']);
			if($uid){
				$info = $this->member->getMemberInfo($uid);
				$this->assign('info',$info);
			}
			$this->assign('action','edit');
			$this->display();
		}
				
		/**
		 * 会员添加修改数据库操作
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-4 下午3:23:27
		 */
		function addupdata(){
			$data = array();
			$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
			$data['uname'] = text($_REQUEST['name']);
			$data['email'] = text($_REQUEST['email']);
			$_REQUEST['passwd'] && $data['passwd'] = sha1_encrypt(text($_REQUEST['passwd']));
			$data['home'] = text($_REQUEST['home']);
			$data['status'] = intval($_REQUEST['status']);
			$data['qq'] = intval($_REQUEST['qq']);
			$data['sex'] = intval($_REQUEST['sex']);
			if($_FILES['file']['name']){
				$avatar = uploads(array('path'=>'avatar','ImgWidth'=>150,'ImgHeight'=>150),true);
				$data['avatar'] = $avatar['thumb'];
			}
			$data['lasttime'] = time();
			empty($_REQUEST['uid']) && $data['registertime'] = time();
			$data['lastip'] = get_client_ip();
			$reid = add_updata($data,'Member');
			if($reid === false){
				$this->error('保存失败！',U('Admin/Member/index'));
			}else{
				$this->success('保存成功！',U('Admin/Member/index'));
			}
		}
		
		/**
		 * 禁用、启用用户
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-5 下午1:17:29
		 */
		function desable(){
			$uid = intval($_REQUEST['uid']);
			$status = intval($_REQUEST['status']) == '1' ? 0 : 1;
			$reid = $this->member->setUserStatus($uid,$status);
			if($reid === false){
				$msg['status'] = null;
				$msg['msg'] = $status ? '启用失败！' : '禁用失败！';
			}else{
				$msg['status'] = 1;
				$msg['msg'] = $status ? '已启用' : '已禁用';
			}
			echo json_encode($msg);
			exit;
		}
		
		/**
		* 删除用户
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-6  下午9:55:13
		*/
		function deluser(){
			$ids = $_REQUEST['ids'];
			$m = M('Member');
			$reid = $m->delete("$ids");
			if($reid === false){
				echo null;
			}else{
				echo 1;
			}
			exti;
		}
	}