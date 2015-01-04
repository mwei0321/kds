<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  登入后台
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-1 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :	http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/
	
	namespace Admin\Controller;
	use Think\Controller;
	use Library\AdminUser;

	class LoginController extends Controller{
		protected $adminuser;
		function _init(){
			$this->adminuser = new AdminUser();
		}
		
		/**
		 * 管理员登入
		 * @return array
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午3:29:45
		 */
		function Login(){
			if($_REQUEST['is_login']){
				$name = text($_REQUEST['admin_name']);
				$passwd = text($_REQUEST['admin_paddwd']);
				$info = $this->adminuser->login($name, $passwd);
				if($info){
					if($info == -1){
						echo json_encode(array('status'=>-1,'msg'=>'密码错误'));
					}else{
						$_SESSION['AdminID'] = $info['id'];
						echo json_encode(array('status'=>1,'msg'=>'登入成功'));
					}
				}else{
					echo json_encode(array('status'=>null,'msg'=>'用户名不存在'));
				}
			}
			$this->display();
		}
		
		/**
		 * 管理员注册修改
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午4:27:53
		 */
		function register(){
			$data = array();
			$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
			$data['name'] = text($_REQUEST['name']);
			$data['passwd'] = $this->adminuser->encrypt($_REQUEST['passwd']);
			$data['role'] = $_REQUEST['role'] ? intval($_REQUEST) : 1;
			$data['status'] = $_REQUEST['status'] ? intval($_REQUEST['status']) : 1;
			$data['uptime'] = time();
			$reid = add_updata($data,'AdminUser');
		}
		
		/**
		 * 管理退出
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-8 下午4:30:39
		 */
		function logout(){
			unset($_SESSION['AdminID']);
			echo json_encode(array('status'=>null,'msg'=>'退出成功！'));
		}
	}