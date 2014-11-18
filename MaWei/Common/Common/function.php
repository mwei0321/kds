<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain:  公共函数
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: ONLY <1123265518@qq.com>
	*  +----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-8-24 	
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

	/**
	 * 返回数组的某个值的集合
	* @param array $_data
	* @param string $_field
	* @return array $onearray
	* @author MaWei ( http://www.phpyrb.com )
	* @date 2014-4-17 下午1:50:15
	*/
	function arr2to1($_data = array(),$_key = 'id',$_field = null){
		$onearray = array();
		foreach ($_data as $k => $v){
			if($_field && is_array($v["$_field"])){
				foreach ($v["$_field"] as $key => $val){
					$onearray[] = $val["$_key"];
				}
			}
			$onearray[] = $v["$_key"];
		}
		return array_unique($onearray);
	}
	
	/**
	 * 把处理过的HTML还原
	 * @param array $_data
	 * @param array $_field 要还原的KEY
	 * @return array $_data
	 * @author MaWei ( http://www.phpyrb.com )
	 * @date 2014-4-17 下午1:50:15
	 */
	function rehtml($_data,$_filed = array('content')){
		foreach ($_data as $key => $val){
			foreach ($val as $k => $v){
				if(in_array($k, $_filed)){
					$_data[$key][$k] = html_entity_decode($_data[$key][$k]);
				}
			}
		}
		return $_data;
	}
	
	/**
	 * 输入json信息
	 * @param string $_msg 提示信息
	 * @param int $_status 状态
	 * @param array 扩展
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-10-14 下午6:23:32
	 */
	function ejson($_msg,$_status = 1,$_extend = array()){
		$re = array('status'=>$_status,'msg'=>$_msg);
		$re = array_merge($re,$_extend);
		echo json_encode($re);
		exit;
	}
	
	function uploadFile(){
		if($_FILES['file']){
			
		}
		
	}
	
	/**
	 * 图片上传
	 * @param  array
	 * @param  string
	 * @return array
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-8-3  下午2:09:25
	 */
	function uploads( $config = array(),$thumb = FALSE) {
		$upload = new Vendor\UploadFile();
		// 设置上传文件大小
		$upload -> maxSize = empty($config['maxSize']) ? 300292200 : $config['maxSize'];
		// 设置上传文件类型
		$allowExts = empty($config['allowExts']) ? C('ALLOW_IMAGE_EXTS') : $config['allowExts'];
		$upload -> allowExts = explode(',', $allowExts);
		// 设置附件上传目录
		$pathd = date('Y-m') . '/';
		$upload -> savePath = $config['path'] ? 'Uploads/' .$config['path'].'/'.$pathd : './Uploads/Photo/' . $pathd;
		createDir($upload -> savePath);
		$path = $upload -> savePath;
		// 设置上传文件规则
		$upload -> saveRule = 'uniqid';
		$upload -> diyname = $config['diyname'] ? $config['diyname'] : C('UPLOAD_DIY_NAME');
		$upload -> uploadReplace = true;
		$config['filename'] && $upload -> filename = $config['filename'];
		if($thumb){
			$pathname = $config['path'] ? 'Uploads/'.$config['path'].'/'.$pathd : 'Uploads/Photo/'.$pathd;
			createDir($pathname);
			$upload -> thumb = TRUE; //是否保存缩略图
			// 			$upload -> thumbRemoveOrigin = TRUE; //是否移除原图
			$upload ->thumbPath = $pathname;// 缩略图保存路径
			$upload ->thumbFile = C('UPLOAD_DIY_NAME');// 缩略图文件名
			$upload ->thumbMaxWidth   =  $config['ImgWidth'] ? $config['ImgWidth'] : '220';// 缩略图最大宽度
			$upload ->thumbMaxHeight  =  $config['ImgHeight'] ? $config['ImgHeight'] : '240';// 缩略图最大高度
			// 			$upload ->thumbExt  = '';// 缩略图扩展名
		}
		if (!$upload -> upload()) {
			// 捕获上传异常
			if ( $upload -> getErrorMsg() == '没有选择上传文件' ) {
				$uploadList = array('');
			} else
				dump($upload -> getErrorMsg());
		} else {
			// 取得成功上传的文件信息
			$uploadList = $upload -> getUploadFileInfo();
			$uploadList = count($uploadList) < 2 ? $uploadList[0] : $uploadList;
// 			$uploadList = $path . $uploadList[0]['savename'];
// 			$uploadList['path']=$path;
		}
		return $uploadList;
	}
	
	/**
	* 文件上传
	* @param  string　$_file 
	* @return array $info
	* @author MaWei (http://www.phpyrb.com)
	* @date 2014-10-19  下午2:55:23
	*/
	function fileUpload($_path = 'file',$_file = null){
		$file = $_file ? $_file : $_FILES['file'];
		$upload = new Vendor\UploadFile();
		$upload -> maxSize = C('ALLOW_FILE_SIZE') ? C('ALLOW_FILE_SIZE') : 300292200;
		$upload -> allowExts = explode(',', C('ALLOW_FILE_EXTS'));
		$upload -> savePath = './Uploads/'.$_path.'/'.date('Y-m').'/';
		$upload -> saveRule = 'uniqid';
		$upload -> filename = C('UPLOAD_DIY_NAME');
		$info = $upload -> uploadOne($file);
		return count($info) > 1 ? $info : array_shift($info);
	}
	
	/**
	* 生成多张缩略图
	* @param  string $_path 原图路径 
	* @param  string|array $_name 名字
	* @param  2array $_info 宽高　array(array('width'=>100,'height'=>100,'name'=>'thumb'));
	* @return array 
	* @author MaWei (http://www.phpyrb.com)
	* @date 2014-10-18  下午12:00:57
	*/
	function multiThumb($_path,$_info = array('width'=>100,'height'=>100)){
		$upload = new Vendor\UploadFile();
		$upload -> maxSize = 300292200;
		$upload -> allowExts = explode(',', C('ALLOW_IMAGE_EXTS'));
		$upload -> savePath = './Uploads/'.$_path.'/'.date('Y-m').'/';
		$upload -> saveRule = 'uniqid';
		$upload -> filename = C('UPLOAD_DIY_NAME');
		//上传文件
		$upload -> upload();
		//图片信息
		$info = array();
		$info['info'] = array_shift($upload->getUploadFileInfo());
		//图片路径
		$path = $info['info']['path'];
		//生成缩略图
		$image = new Vendor\Image();
		//生成多张缩略图
		foreach ($_info as $k => $v){
			$name = $v['name'] ? $v['name'] : $upload->savePath.$upload->filename.'-'.$v['width'].'_'.$v['height'].'.'.$info['info']['extension'];
			$info[$k] = $image->thumb2($path, $name,'png',$v['width'],$v['height']);
		}
		return $info;
	}
	
	/**
	 * 公共删除函数
	 * @param  int | string $_idst
	 * @param  model $_model 表名
	 * @return array
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-8-7  下午11:01:39
	 */
	function delall($_ids,$_model){
		$model = null;
		if(strpos($_model,'|') === false){
			$model = M("$_model");
		}else{
			$m = explode('|', $_model);
			$model = M("$m[0]",$m['1']);
		}
		$reid = $model->delete($_ids);
	
		return $reid;
	}
	
	/**
	 * 排序
	 * @param array
	 * @param string
	 * @return array
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-8-5 下午6:35:39
	 */
	function setsort($_model,$_newsort,$_oldsort,$_field = 'sort'){
		$m = M("$_model");
		$where = array();
		if($_newsort > $ $_oldsort){
			$where["$_field"] = array(array('GT',$_oldsort),array('ELT',$_newsort));
			$reid = $m->where($where)->setInc("$_field");
			echo $m->getLastSql();exit;
		}elseif($_newsort < $_oldsort){
			$where["$_field"] = array(array('LT',$_oldsort),array('EGT',$_newsort));
			$reid = $m->where($where)->setDec("$_field");
		}elseif($_newsort == $_oldsort){
			return 1;
		}
		if($reid === false){
			return null;
		}else{
			return 1;
		}
	}
	
	/**
	 * 修改状态
	 * @param model $_model 表名
	 * @param int|array $_ids 要修改的ID
	 * @param string $_string 要修改的状态
	 * @return array
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-8-6 上午10:23:32
	 */
	function status($_model,$_ids,$_status = 0){
		if(empty($_ids)) return false;
		$model = null;
		if(strpos($_model,'|') === false){
			$model = M("$_model");
		}else{
			$m = explode('|', $_model);
			$model = M("$m[0]",$m['1']);
		}
		$where = array();
		$where['id'] = is_string($_ids) ? array('IN',"$_ids") : $_ids;
		$reid = $model->where($where)->setField('status',$_status);
// 		echo $model->getlastsql();
		return $reid;
	}
	
	/**
	* 推荐设置	 
	* @param model $_model 表名
	* @param int|array $_ids 要修改的ID
	* @param string $_string 要修改的状态
	* @return array 
	* @author MaWei (http://www.phpyrb.com)
	* @date 2014-10-4  下午11:38:37
	*/
	function recommend($_model,$_ids,$_recomm = 0){
		if(empty($_ids)) return false;
		$model = null;
		if(strpos($_model,'|') === false){
			$model = M("$_model");
		}else{
			$m = explode('|', $_model);
			$model = M("$m[0]",$m['1']);
		}
		$where = array();
		$where['id'] = is_string($_ids) ? array('IN',"$_ids") : $_ids;
		$reid = $model->where($where)->setField('recommend',$_recomm);
// 		echo $model->getlastsql();
		return $reid;
	}
	
	/**
	* 返回两层层级分类
	* @param  array $_catelist 
	* @return array 
	* @author MaWei (http://www.phpyrb.com)
	* @date 2014-10-7  下午10:32:38
	*/
	function getChildrenLevel($_catelist){
		$pid = null;
		$children = array();
		foreach ($_catelist as $k => $v){
			if($v['pid'] == 0){
				$pid = $v['id'];
				$children[$pid] = $v;
			}else{
				$children[$pid]['children'][] = $v;	
			}
		}
		return $children;
	}
	
	/**
	* 返回两到三层的树形菜单
	* @param  array $_list 
	* @param  int $_level
	* @return array 
	* @author MaWei (http://www.phpyrb.com)
	* @date 2014-10-18  上午11:05:59
	*/
	function getTree($_list,$_level = 2){
		$pid = $pid2 = null;
		$tree = array();
		foreach ($_list as $k => $v){
			if($v['pid'] == 0){
				$pid = $k;
				$tree[$k] = $v;
			}else{
				if($_level == 2 || $v['level'] == 1){
					$tree[$pid]['children'][$k] = $v;
					$pid2 = $v['pid'];
				}elseif($_level == 3 || $v['level'] > 1){
					$tree[$pid]['children'][$pid2][$k] = $v;
				}
			}
		}
		return $tree;
	}
	
	/**
	 * 创建文件夹
	 * @param  string $_path 文件夹路径
	 * @return array
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-8-3  下午2:10:22
	 */
	function createDir($_path){
		if (!file_exists($_path)){
			createDir(dirname($_path));
			mkdir($_path, 0777);
		}
	}
	
	/**
	 * 返回目录下的文件夹名称
	 * @param string $_path 路径
	 * @return array $filelist
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-10-8 下午2:48:38
	 */
	function getDirFile($_path){
		if(is_dir($_path)){
			$filelist = scandir($_path);
			foreach ($filelist as $k => $v){
				if(strpos($v, '.') !== false){
					unset($filelist[$k]);
				}
			}
			return $filelist;
		}else{
			return null;
		}
	}
	
	/**
	 * 把数组里的字符转换成全小、大写,暂时只支持一维数组
	 * @param array $_arr 要转换的数组
	 * @param string 类型，1为小写，0为大写
	 * @return array $_arr
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-10-8 下午5:06:43
	 */
	function arrtolower($_arr){
		foreach ($_arr as $k => $v){
			$_arr[$k] = strtolower($v);
		}
		return $_arr;
	}
	
	/**
	 * 数据添加、更新
	 * @param array $_data 数据
	 * @param string $_model 表名  表前缀用｜分隔
	 * @param string $_upfiled 主键
	 * @return string | boolean $reid
	 * @author MaWei ( http://www.phpyrb.com )
	 * @date 2014-4-17 下午1:50:15
	 */
	function add_updata($_data = array(),$_model = 'Article',$_upfiled = 'id'){
		if(strpos($_model,'|') === false){
			$model = M("$_model");
		}else{
			$m = explode('|', $_model);
			$model = M("$m[0]",$m['1']);
		}
		$reid = FALSE;
		if($_upfiled && $_data["$_upfiled"]){
			$where = array();
			$where["$_upfiled"] = $_data["$_upfiled"];
			unset($_data["$_upfiled"]);
			$reid = $model->where($where)->save($_data);
		}else{
			$reid = $model->add($_data);
		}
		
// 		dump($model);
// 		dump($_data);
// 		echo $model->getLastSql();
// 		exit;
		return $reid;
	}

	
	/**
	 * 合并二维数组里面一些相同的元素
	 * @param array $data
	 * @param string $_key 共同点的下标
	 * @param string $_mergeKey 需要合并的字段
	 * @return array $new
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-6-11 下午3:38:27
	 */
	function merge_array($_data,$_key = 'id',$_mergeKey = array('description','path','thumb')){
		$new = array();
		$i = 0;
		foreach ($_data as $k => $v){
			if(!empty($new[$v[$_key]])){
				foreach ($_mergeKey as $key => $val){
					$new[$v[$_key]]['image'][$i]["$val"] = $v["$val"];
				}
				unset($_data["$k"]);
				$i++;
			}else{
				foreach ($v as $key => $val){
					if(!in_array($key, $_mergeKey)){
						$new[$v[$_key]][$key] = $val;
					}else{
						$new[$v[$_key]]['image'][$i][$key] = $val;
					}
				}
				$i++;
			}
		}
		// 		dump($new);
		return $new;
	}
	
	/**
	 * 友好的时间显示
	 *
	 * @param int    $sTime 待显示的时间
	 * @param string $type  类型. normal | mohu | full | ymd | other
	 * @param string $alt   已失效
	 * @return string
	 */
	function friendlyDate($sTime,$type = 'normal',$alt = 'false') {
		if (!$sTime)
			return '';
		//sTime=源时间，cTime=当前时间，dTime=时间差
		$cTime      =   time();
		$dTime      =   $cTime - $sTime;
		$dDay       =   intval(date("z",$cTime)) - intval(date("z",$sTime));
		//$dDay     =   intval($dTime/3600/24);
		$dYear      =   intval(date("Y",$cTime)) - intval(date("Y",$sTime));
		//normal：n秒前，n分钟前，n小时前，日期
		if($type=='normal'){
			if( $dTime < 60 ){
				if($dTime < 10){
					return '刚刚';    //by yangjs
				}else{
					return intval(floor($dTime / 10) * 10)."秒前";
				}
			}elseif( $dTime < 3600 ){
				return intval($dTime/60)."分钟前";
				//今天的数据.年份相同.日期相同.
			}elseif( $dYear==0 && $dDay == 0  ){
				//return intval($dTime/3600)."小时前";
				return '今天'.date('H:i',$sTime);
			}elseif($dYear==0){
				return date("m月d日 H:i",$sTime);
			}else{
				return date("Y-m-d H:i",$sTime);
			}
		}elseif($type=='mohu'){
			if( $dTime < 60 ){
				return $dTime."秒前";
			}elseif( $dTime < 3600 ){
				return intval($dTime/60)."分钟前";
			}elseif( $dTime >= 3600 && $dDay == 0  ){
				return intval($dTime/3600)."小时前";
			}elseif( $dDay > 0 && $dDay<=7 ){
				return intval($dDay)."天前";
			}elseif( $dDay > 7 &&  $dDay <= 30 ){
				return intval($dDay/7) . '周前';
			}elseif( $dDay > 30 ){
				return intval($dDay/30) . '个月前';
			}
			//full: Y-m-d , H:i:s
		}elseif($type=='full'){
			return date("Y-m-d , H:i:s",$sTime);
		}elseif($type=='ymd'){
			return date("Y-m-d",$sTime);
		}else{
			if( $dTime < 60 ){
				return $dTime."秒前";
			}elseif( $dTime < 3600 ){
				return intval($dTime/60)."分钟前";
			}elseif( $dTime >= 3600 && $dDay == 0  ){
				return intval($dTime/3600)."小时前";
			}elseif($dYear==0){
				return date("Y-m-d H:i:s",$sTime);
			}else{
				return date("Y-m-d H:i:s",$sTime);
			}
		}
	}
	
	/**
	 * 返回目录下的图片列表
	 * @param string $_path
	 * @return array
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-10-9 下午2:44:56
	 */
	function getDirImageList($_path = null){
		$path = $_path ? $_path : UPLOAD_PATH.'/tmpavatar/';
		$file = scandir($path);
		$list = array();
		if(count($file) > 0){
			foreach ($file as $k => $v){
				if(in_array(strtolower(substr($v, strrpos($v, '.')+1)),array('jpg','gif','jpeg','png'))){
					$list[] = $v;
				}
			}
		}
		return $list;
	}
	
	
	/**
	 * 把数组的某的值做为键
	 * @param array $_data
	 * @param string $_field
	 * @return array $newdata
	 * @author MaWei ( http://www.phpyrb.com )
	 * @date 2014-4-17 下午1:50:15
	 */
	function fieldtokey($_data = array(),$_field = 'id'){
		$newdata = array();
		foreach ($_data as $k => $v){
			if(is_array($v["$_field"])){
				foreach ($v["$_field"] as $key => $val){
					$newdata[$v["$_field"]][$val["$_field"]] = $val;
				}
			}
			$newdata[$v["$_field"]] = $v;
		}
		return $newdata;
	}
	
	/**
	 * t函数用于过滤标签，输出没有html的干净的文本
	 * @param string text 文本内容
	 * @return string 处理后内容
	 */
	function text($text){
		$text = nl2br($text);
		$text = real_strip_tags($text);
		$text = addslashes($text);
		$text = trim($text);
		return $text;
	}
	
	/**
	 * 去掉HTML标签
	 * @param  string $str
	 * @param  string
	 * @return array
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-8-3  上午1:38:43
	 */
	function real_strip_tags($str, $allowable_tags="") {
		$str = html_entity_decode($str,ENT_QUOTES,'UTF-8');
		return strip_tags($str, $allowable_tags);
	}
	
	/**
	 * h函数用于过滤不安全的html标签，输出安全的html
	 * @param string $text 待过滤的字符串
	 * @param string $type 保留的标签格式
	 * @return string 处理后内容
	 */
	function h($text, $type = 'html'){
		// 无标签格式
		$text_tags  = '';
		//只保留链接
		$link_tags  = '<a>';
		//只保留图片
		$image_tags = '<img>';
		//只存在字体样式
		$font_tags  = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
		//标题摘要基本格式
		$base_tags  = $font_tags.'<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
		//兼容Form格式
		$form_tags  = $base_tags.'<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
		//内容等允许HTML的格式
		$html_tags  = $base_tags.'<meta><ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed><param>';
		//专题等全HTML格式
		$all_tags   = $form_tags.$html_tags.'<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
		//过滤标签
		$text = real_strip_tags($text, ${$type.'_tags'});
		// 过滤攻击代码
		if($type != 'all') {
			// 过滤危险的属性，如：过滤on事件lang js
			while(preg_match('/(<[^><]+)(ondblclick|onclick|onload|onerror|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i',$text,$mat)){
				$text = str_ireplace($mat[0], $mat[1].$mat[3], $text);
			}
			while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
				$text = str_ireplace($mat[0], $mat[1].$mat[3], $text);
			}
		}
		return $text;
	}