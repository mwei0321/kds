<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  书集控制类
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
	use Library\Edit;
		
	class SourcController extends IniController{
		function _init(){
			parent::_init();
			$this->assign('recomm',array('未推荐','已推荐'));
		}
		
		/**
		* 书籍列表
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午2:46:17
		*/
		function index(){
			$where = array();
			$_REQUEST['bid'] && $where['id'] = $search['bid'] = intval($_REQUEST['bid']);
			$_REQUEST['keyword'] && $where['name'] = $search['keyword'] = text($_REQUEST['keyword']);
			$count = $this->Sourc->sourc('count',$where);
			$page = new Page($count, 30);
			$list = $this->Sourc->sourc("$page->firstRow,$page->listRows",$where);
			$list = $this->Sourc->tagtoarrray($list);

			$this->assign('list',$list);
			$this->assign('count',$count);
			$this->assign('page',$page->show());
			$this->display();
		}
		
		/**
		* 删除书籍
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午2:35:18
		*/
		function delete(){
			$reid = delall($_REQUEST['ids'],'Sourc');
			if($reid === false){
				echo null;
			}else{
				echo 1;
			}
			exit;
		}
		
		/**
		* 状态设置
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-9  下午3:00:22
		*/
		function status (){
			$status = intval($_REQUEST['status']) == '1' ? 0 : 1;
			$reid = status('Sourc',$_REQUEST['ids'],$status);
			if($reid === false){
				$msg['status'] = null;
				$msg['msg'] = $status ? '显示设置失败！' : '隐藏设置失败！';
			}else{
				$msg['status'] = 1;
				$msg['msg'] = $status ? '已显示' : '已隐藏';
			}
			echo json_encode($msg);
			exit;
		}
		
		/**
		* 推荐
		* @return array 
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-4  下午1:06:39
		*/
		function recommend(){
			$data = array();
			$data['id'] = intval($_REQUEST['id']);
			$data['recommend'] = intval($_REQUEST['recomm']) == 1 ? 0 : 1;
			$reid = add_updata($data,'Sourc');
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
		* 书籍添加修改页
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-7  下午10:46:46
		*/
		function edit(){
			if($_REQUEST['id']){
				$info = $this->Sourc->Sourcinfo(intval($_REQUEST['id']));
// 				dump($info);
				$this->assign('info',$info[0]);
			}
			$this->display();
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
		
		/**
		* 书箱添加修改数据库
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-8-7  下午10:45:59
		*/
		function add_updata(){
			for ($i =0 ;$i<50;$i++){
			$data = array();
			$_REQUEST['id'] && $data['id'] = intval($_REQUEST['id']);
			$data['cateid'] = intval($_REQUEST['cateid']);
			$data['tagid'] = implode(',', $_REQUEST['tagids']);
			$data['grade'] = intval($_REQUEST['grade']);
			$data['status'] = $_REQUEST['status'] ? intval($_REQUEST['status']) : 1;
			$data['title'] = h(text($_REQUEST['title']));
			$data['uid'] = $this->uid;
			$data['uptime'] = time();
			$data['recommend'] = intval($_REQUEST['recommend']);
			$intro = text($_REQUEST['intro']);
			$data['keyword'] = text($_REQUEST['keyword']);
			$data['discription'] = $_REQUEST['discription'] ? $_REQUEST['discription'] : mb_substr($intro,0,150);
			if($_FILES['cover']['name']){
				$avatar = uploads(array('path'=>'cover','ImgWidth'=>105,'ImgHeight'=>160,'filename'=>'cover'),true);
				$data['cover'] = $avatar['thumb'];
			}
			$reid = add_updata($data,'Sourc');
			if($reid === false){
				$this->error('添加修改失败',U('Admin/Sourc/edit'));
			}else{
				$temp = array();
				$temp['sourc_id'] = $_REQUEST['id'] ? intval($_REQUEST['id']) : $reid;
				$temp['intro'] = $intro;
				$temp['content'] = $_REQUEST['content'];
				$reid = add_updata($temp,'SourcContent','sourc_id');
// 				if($reid === false){
// 					$this->error('添加修改失败',U('Admin/Sourc/index'));
// 				}
// 				$this->success('添加修改成功',U('Admin/Sourc/index'));
			}
			}
		}
		
		function attach(){
			
		}
	}

	