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
	use Library\Grade;
	use Vendor\Page;
		
	class MemberController extends  IniController{
		protected $member,$grade;
		function _init(){
			parent::_init();
			$this->member = new Member();
			$this->grade = new Grade();
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
		
		function grade(){
			$list = $this->grade->getGrade();
			$gradetype = $this->grade->getGradeType();
			$this->assign('gradetype',fieldtokey($gradetype,'id'));
			$this->assign('list',$list);
			$this->display();
		}
		
		/**
		* 积分类型列表
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-20  下午5:09:52
		*/
		function gradeType(){
			$list = $this->grade->getGradeType();
			$this->assign('list',$list);
			$this->assign('count',count($list));
			$this->display();
		}
		
		/**
		* 积分等级编辑
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-20  下午4:33:19
		*/
		function gradeEdit(){
			$id = intval($_REQUEST['id']);
			if($id){
				$info = $this->grade->getGrade();
				$this->assign($info);
			}
			$gradetype = $this->grade->getGradeType();
			$this->assign('list',$gradetype);
			$this->display();
		}
		
		/**
		* 会员积分类型编辑
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-20  下午4:32:48
		*/
		function gradeTypeEdit(){
			$id = intval($_REQUEST['id']);
			if($id){
				$info = $this->grade->getGradeType($id);
				$this->assign('info',$info[0]);
			}
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
			$table = null;
			$type = $_REQUEST['type'];
			switch ($type){
				case 'member' :
					$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
					$data['uname'] = text($_REQUEST['name']);
					$data['email'] = text($_REQUEST['email']);
					$_REQUEST['passwd'] && $data['passwd'] = sha1_encrypt(text($_REQUEST['passwd']));
					$data['home'] = text($_REQUEST['home']);
					$data['status'] = intval($_REQUEST['status']);
					$data['qq'] = intval($_REQUEST['qq']);
					$data['sex'] = intval($_REQUEST['sex']);
					$data['grade'] = intval($_REQUEST['grade']);
					if($_FILES['file']['name']){
						$avatar = uploads(array('path'=>'avatar','ImgWidth'=>150,'ImgHeight'=>150),true);
						$data['avatar'] = $avatar['thumb'];
					}
					$data['lasttime'] = time();
					empty($_REQUEST['uid']) && $data['registertime'] = time();
					$data['lastip'] = get_client_ip();
					$table = 'Member';
					$jumpUrl = U('Admin/Member/index');
					break;
				case 'grade' :
					if(intval($_REQUEST['is_muti'])){
						$name = $_REQUEST['name'];
						$grade = intval($_REQUEST['grade']);
						$type = intval($_REQUEST['gradetype']);
						if(strpos($name,',') !== false){
							$name = explode(',', $name);
							$data['type'] = $type;
							$i = 1;
							$reid = null;
							foreach ($name as $k => $v){
								$data['name'] = text($v);
								$data['level'] = $i;
								$data['grade'] = $i * $grade;
								$reid = add_updata($data,'Grade');
								$i++;
							}
							if($reid === false){
								$this->error('保存失败！',U('Admin/Member/grade'));
							}else{
								$this->success('保存成功！',U('Admin/Member/grade'));
							}
							exit();
						}else{
							$data['name'] = text($name);
							$data['level'] = 1;
							$data['grade'] = $grade;
						}
					}else{
						$data['name'] = text($_REQUEST['name']);
						$data['level'] = intval($_REQUEST['level']);
						$data['grade'] = intval($_REQUEST['grade']);
						$data['type'] = intval($_REQUEST['gradetype']);
					}
					$table = 'Grade';
					$jumpUrl = U('Admin/Member/grade');
 					break;
				case 'gradeType':
					$data['name'] = text($_REQUEST['name']);
					$data['alias'] = text($_REQUEST['alias']);
					$table = 'GradeType';
					$jumpUrl = U('Admin/Member/gradeType');
					break;
				default:
					$this->error('非法操作！',U('Admin/Member/index'));
					exit;
			}
			$reid = add_updata($data,$table);
			if($reid === false){
				$this->error('保存失败！',$jumpUrl);
			}else{
				$this->success('保存成功！',$jumpUrl);
			}
		}
		
		/**
		 * 禁用、启用用户
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-8-5 下午1:17:29
		 */
		function desable(){
			$status = intval($_REQUEST['status']) == '1' ? 0 : 1;
			$reid = $this->member->setUserStatus($_REQUEST['ids'],$status);
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
		function delete(){
			$ids = $_REQUEST['ids'];
			$m = null;
			$type = $_REQUEST['type'];
			switch ($type) {
				case 'member'://会员删除
					$m = M('Member');
					break;
				case 'grade'://会员积分等级删除
					$m = M('Grade');
					break;
				case 'gradeType'://会员积分类型删除
					$m = M('GradeType');
					break;
				default:
					echo null;
					exit;
			}
			$reid = $m->delete("$ids");
			if($reid === false){
				echo null;
			}else{
				echo 1;
			}
			exit();
		}
		
		
	}