<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  会员的积分，经验，等级操作类
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-9-20 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	namespace Library;
	
	class Grade {
		private $grade,$membergrade,$gradetype;
		
		function __construct(){
			$this->grade = M('Grade');
			$this->membergrade = M('MemberGrade');
			$this->gradetype = M('GradeType');
		}
		
		/**
		* 返回用户的积分，经验
		* @param  int $_uid 用户ID
		* @param  string $_type 为空返回积分、经验。否则返回单个
		* @return array $info
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-20  下午3:15:36
		*/
		function getUserIntegral($_uid,$_type = null){
			$field = $_type ? $_type : 'grade,experience';
			$info = $this->member->field($field)->where(array('id'=>$_uid))->find();
			return $info;
		}
		
		/**
		* 会员等级设置
		* @param  int $_id 
		* @return array $grade
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-20  下午4:45:04
		*/
		function getGrade($_id = null) {
			$where = array();
			$_id && $where['id'] = $_id;
			$grade = $this->grade->where($where)->select();
			return $grade;
		}
		
		/**
		* 会员积分类型
		* @param  int $_id 
		* @return array $list
		* @author MaWei (http://www.phpyrb.com)
		* @date 2014-9-20  下午4:17:53
		*/
		function getGradeType($_id = null){
			$where = array();
			$_id && $where['id'] = $_id;
			$list = $this->gradetype->where($where)->select();
			return $list;
		}
	}