<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  临时测试数据
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-10-6 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Admin\Controller;
	
	class TempDateController extends IniController{
		
		function _init(){
			parent::_init();
		}
		
		/**
		 * 资源测试数据
		 * @author MaWei (http://www.phpyrb.com)
		 * @date 2014-10-3  下午10:07:18
		 */
		function sourc(){
			foreach (S('CateList') as $k => $v){
				$a = rand(30,50);
				for($i = 0;$i < $a; $i++){
					$data = array();
					$data['cateid'] = $v['id'];
					$data['tagid'] = rand(1,4);
					$data['grade'] = rand(1, 10);
					$data['cover'] = 'Uploads/Thumb/cover/2014-10/phpyrb.com-20141003201058d7b.png';
					$data['status'] = 1;
					$data['comment_num'] = rand(1,1000);
					$data['down_num'] = rand(100,10000);
					$intro = $data['keyword'] = $data['discription'] = $data['title'] = 'python手册'.rand(100,2000);
					$data['uid'] = $this->uid;
					$data['uptime'] = time();
					$reid = add_updata($data,'Sourc');
					$temp = array();
					$temp['sourc_id'] = $reid;
					$temp['intro'] = $intro;
					$temp['content'] = ' django的url是你网站访问的路径。配置好你的URL是建好一个网站的重要核心。下面将详细讲解 Django url配置过程'.rand(2000,20000000);
					$reid = add_updata($temp,'SourcContent','');
				}
			}
		}
		
		/**
		* 下载日志
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-10-6  下午8:52:52
		*/
		function downloadlog(){
			for($i = 0 ; $i < 1000 ; $i++){
				$data = array();
				$data['sourc_id'] = rand(1000, 5000);
				$data['title'] = 'python手册'.$data['sourc_id'];
				$data['uid'] = rand(1, 500);
				$data['uname'] = 'phpyrb'.$data['uid'];
				$data['ctime'] = rand(1381495906,time());
				add_updata($data,'DownloadLog');
			}
		}
	}