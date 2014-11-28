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
	function arr2to1($_data = array(),$_key = 'id',$_field = null,$_unique = true){
		$onearray = array();
		foreach ($_data as $k => $v){
			if($_field && is_array($v["$_field"])){
				foreach ($v["$_field"] as $key => $val){
					$onearray[] = $val["$_key"];
				}
			}
			$onearray[] = $v["$_key"];
		}
		return $_unique ? array_unique($onearray) : $onearray;
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
	 * 检查字符串是否是UTF8编码
	 * @param string $string 字符串
	 * @return Boolean
	 */
	function isUtf8($str) {
	    $c=0; $b=0;
	    $bits=0;
	    $len=strlen($str);
	    for($i=0; $i<$len; $i++){
	        $c=ord($str[$i]);
	        if($c > 128){
	            if(($c >= 254)) return false;
	            elseif($c >= 252) $bits=6;
	            elseif($c >= 248) $bits=5;
	            elseif($c >= 240) $bits=4;
	            elseif($c >= 224) $bits=3;
	            elseif($c >= 192) $bits=2;
	            else return false;
	            if(($i+$bits) > $len) return false;
	            while($bits > 1){
	                $i++;
	                $b=ord($str[$i]);
	                if($b < 128 || $b > 191) return false;
	                $bits--;
	            }
	        }
	    }
	    return true;
	}
	
	/**
	 * 
	 * @param array
	 * @param string 
	 * @return array
	 * @author MaWei (http://www.phpyrb.com)
	 * @date 2014-11-20 下午4:32:24
	 */
	function strCutOut($str, $length = 40, $ext = '……') {
	    $str    =   strip_tags($str);
	    $strlenth   =   0;
	    $out        =   '';
	    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match);
	    foreach($match[0] as $v){
	        preg_match("/[\xe0-\xef][\x80-\xbf]{2}/",$v, $matchs);
	        if(!empty($matchs[0])){
	            $strlenth   +=  1;
	        }elseif(is_numeric($v)){
	            //$strlenth +=  0.545;  // 字符像素宽度比例 汉字为1
	            $strlenth   +=  0.5;    // 字符字节长度比例 汉字为1
	        }else{
	            //$strlenth +=  0.475;  // 字符像素宽度比例 汉字为1
	            $strlenth   +=  0.5;    // 字符字节长度比例 汉字为1
	        }
	
	        if ($strlenth > $length) {
	            $output .= $ext;
	            break;
	        }
	
	        $output .=  $v;
	    }
	    return $output;
	}
	
	/**
	 * 字符串截取，支持中文和其他编码
	 * @static
	 * @access public
	 * @param string $str 需要转换的字符串
	 * @param string $start 开始位置
	 * @param string $length 截取长度
	 * @param string $charset 编码格式
	 * @param string $suffix 截断显示字符
	 * @return string
	 */
	function msubstr($str, $length, $start=0, $charset="utf-8", $suffix=true) {
	    if(function_exists("mb_substr"))
	        $slice = mb_substr($str, $start, $length, $charset);
	    elseif(function_exists('iconv_substr')) {
	        $slice = iconv_substr($str,$start,$length,$charset);
	    }else{
	        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	        preg_match_all($re[$charset], $str, $match);
	        $slice = join("",array_slice($match[0], $start, $length));
	    }
	    return $suffix ? $slice.'...' : $slice;
	}
	
	/**
	 * 产生随机字串，可用来自动生成密码
	 * 默认长度6位 字母和数字混合 支持中文
	 * @param string $len 长度
	 * @param string $type 字串类型
	 * 0 字母 1 数字 其它 混合
	 * @param string $addChars 额外字符
	 * @return string
	 */
	function randString($len=6,$type='',$addChars='') {
	    $len = is_int($len) ? $len : rand($len[0],$len[1]);
	    $str ='';
	    switch($type) {
	        case 0:
	            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
	            break;
	        case 1:
	            $chars= str_repeat('0123456789',3);
	            break;
	        case 2:
	            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
	            break;
	        case 3:
	            $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
	            break;
	        case 4:
	            $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
	            break;
	        default :
	            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
	            $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
	            break;
	    }
	    if($len>10 ) {//位数过长重复字符串一定次数
	        $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
	    }
	    if($type!=4) {
	        $chars   =   str_shuffle($chars);
	        $str     =   substr($chars,0,$len);
	    }else{
	        // 中文随机字
	        for($i=0;$i<$len;$i++){
	            $str.= msubstr($chars,1, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),'utf-8',false);
	        }
	    }
	    return $str;
	}
	
	/**
	 * 生成一定数量的随机数，并且不重复
	 * @param integer $number 数量
	 * @param string $len 长度
	 * @param string $type 字串类型
	 * 0 字母 1 数字 其它 混合
	 * @return string
	 */
	function buildCountRand ($number,$length=4,$mode=1) {
	    if($mode==1 && $length<strlen($number) ) {
	        //不足以生成一定数量的不重复数字
	        return false;
	    }
	    $rand   =  array();
	    for($i=0; $i<$number; $i++) {
	        $rand[] =   self::randString($length,$mode);
	    }
	    $unqiue = array_unique($rand);
	    if(count($unqiue)==count($rand)) {
	        return $rand;
	    }
	    $count   = count($rand)-count($unqiue);
	    for($i=0; $i<$count*3; $i++) {
	        $rand[] =   self::randString($length,$mode);
	    }
	    $rand = array_slice(array_unique ($rand),0,$number);
	    return $rand;
	}
	
	/**
	 *  带格式生成随机字符 支持批量生成
	 *  但可能存在重复
	 * @param string $format 字符格式
	 *     # 表示数字 * 表示字母和数字 $ 表示字母
	 * @param integer $number 生成数量
	 * @return string | array
	 */
	function buildFormatRand($format,$number=1) {
	    $str  =  array();
	    $length =  strlen($format);
	    for($j=0; $j<$number; $j++) {
	        $strtemp   = '';
	        for($i=0; $i<$length; $i++) {
	            $char = substr($format,$i,1);
	            switch($char){
	                case "*"://字母和数字混合
	                    $strtemp   .= String::randString(1);
	                    break;
	                case "#"://数字
	                    $strtemp  .= String::randString(1,1);
	                    break;
	                case "$"://大写字母
	                    $strtemp .=  String::randString(1,2);
	                    break;
	                default://其他格式均不转换
	                    $strtemp .=   $char;
	                    break;
	            }
	        }
	        $str[] = $strtemp;
	    }
	    return $number==1? $strtemp : $str ;
	}
	
	// 自动转换字符集 支持数组转换
	function autoCharset($string, $from='gbk', $to='utf-8') {
	    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
	    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
	    if (strtoupper($from) === strtoupper($to) || empty($string) || (is_scalar($string) && !is_string($string))) {
	        //如果编码相同或者非字符串标量则不转换
	        return $string;
	    }
	    if (is_string($string)) {
	        if (function_exists('mb_convert_encoding')) {
	            return mb_convert_encoding($string, $to, $from);
	        } elseif (function_exists('iconv')) {
	            return iconv($from, $to, $string);
	        } else {
	            return $string;
	        }
	    } elseif (is_array($string)) {
	        foreach ($string as $key => $val) {
	            $_key = self::autoCharset($key, $from, $to);
	            $string[$_key] = self::autoCharset($val, $from, $to);
	            if ($key != $_key)
	                unset($string[$key]);
	        }
	        return $string;
	    }
	    else {
	        return $string;
	    }
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