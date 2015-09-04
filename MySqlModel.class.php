<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain: mysql数据库操作类
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: MaWei <1123265518@qq.com>
	*	+----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-12-23 下午1:56:44
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

    class MySqlModel{
        private $dbconfig;
        // 当前连接ID
        protected $linkID = null;
        // 最后插入ID
        protected $lastInsID  = null;
        // 当前SQL指令
        protected $Sql   = null;
        // 数据库类型
        protected $dbtype = null;
        // 是否使用永久连接
        protected $pconnect   = false;
        // 当前查询ID
        protected $queryID    = null;
        // 返回或者影响记录数
        protected $numRows    = 0;
        // 返回字段数
        protected $numCols    = 0;
        // 
        protected $method = array();
        //获取成员变量
        function __get($_name){
            return isset($this->method[$_name]) ? $this->method[$_name] : null;
        }
        //设置成员变量
        function __set($_name,$_value){
            $this->method[$_name] = $_value;
        }
        //消除变量
        function __unset($_name){
            unset($this->method[$_name]);
        }
        
        /**
         * 实现连贯操作方法
         * @param string $_method 方法
         * @param string|int|array 参数
         * @return object 
         * @author MaWei (http://www.phpyrb.com)
         * @date 2014-12-23 下午5:02:16
         */
        function __call($_method,$_args){
            if(in_array($_method, array('filed','where','order','group','limit','count'))){
                $this->method[strtolower($_method)] = $_args[0];
                return $this;
            }
            return $this;
        }
        
        /**
        * 初始化设置
        * @param  array $_config 配置数组 
        * @return array 
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-8  下午10:20:10
        */
        function __construct($_config = array()){
            //判断数据库配置
            empty($_config) && exit('not db config');
            //数据库配置文件处理
            $config = $this->_parseConfig($_config);
            //检测数据库类型
            if(!extension_loaded($config['dbtype'])){
                echo 'Not Support '.$config['dbtype'];
            }else{
                $this->dbtype = $config['dbtype'];
                $this->_connenct($config);
            }
        }
        
        /**
         * sql语句查询
         * @param  string $_sql sql语句
         * @return array
         * @author MaWei (http://www.phpyrb.com)
         * @date 2015-2-8  下午10:19:04
         */
        public function query($_sql){
        	if(! $this->linkID ) return 'DateBase Connent Fialure';
        	$this->Sql = $_sql;
        	//释放之前查询
        	$this->linkID && $this->free();
        	//查询sql
        	if($this->dbtype == 'mysqli'){
        		$this->queryID = $this->linkID->query($_sql);
        		// 对存储过程改进
        		if( $this->linkID->more_results() ){
        			while (($res = $this->linkID->next_result()) != NULL) {
        				$res->free_result();
        			}
        		}
        		$this->numRows  = $this->queryID->num_rows;
        		$this->numCols    = $this->queryID->field_count;
        	}else{
        		$this->queryID = mysql_query($_sql, $this->linkID);
        		$this->numRows = mysql_num_rows($this->queryID);
        	}
        }
        
        /**
        * 
        * @param  string $_sql sql语句
        * @return integer
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-25  上午12:48:44
        */
        public function execute($_sql) {
        	if ( !$this->linkID ) return false;
        	$this->Sql = $_sql;
        	//释放前次的查询结果
        	if ( $this->queryID ) $this->free();
        	$result =   $this->linkID->query($_sql);
        	if ( false === $result ) {
        		$this->geterror();
        		return false;
        	} else {
        		$this->numRows = $this->linkID->affected_rows;
        		$this->lastInsID = $this->linkID->insert_id;
        		return $this->numRows;
        	}
        }
        
        /**
        * 添加、更新数据
        * @param  array $_data 数据 
        * @param  string $_tableName 表名
        * @return array 
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-8  下午10:22:26
        */
        public function add_updata($_data,$_tableName){
            if(empty($_data)){
                
            }
        }
        
        /**
        * 插入多条记录
        * @param  array $_datas
        * @param  string 
        * @return array 
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-8  下午10:56:02
        */
	    public function insertAll($_datas,$_options=array(),$_replace=false) {
	        if(!is_array($_datas[0])) return false;
	        $fields = array_keys($_datas[0]);
	        array_walk($fields, array($this, 'parseKey'));
	        $values  =  array();
	        foreach ($_datas as $data){
	            $value   =  array();
	            foreach ($data as $key=>$val){
	                $val   =  $this->parseValue($val);
	                if(is_scalar($val)) { // 过滤非标量数据
	                    $value[]   =  $val;
	                }
	            }
	            $values[]    = '('.implode(',', $value).')';
	        }
	        $sql   =  ($replace?'REPLACE':'INSERT').' INTO '.$this->parseTable($_options['table']).' ('.implode(',', $fields).') VALUES '.implode(',',$values);
	        return $this->execute($sql);
	    }
	    
	    /**
	     * 字段和表名处理添加`
	     * @access protected
	     * @param string $key
	     * @return string
	     */
	    protected function parseKey(&$key) {
	    	$key   =  trim($key);
	    	if(!is_numeric($key) && !preg_match('/[,\'\"\*\(\)`.\s]/',$key)) {
	    		$key = '`'.$key.'`';
	    	}
	    	return $key;
	    }
	    
	    /**
	     * SQL指令安全过滤
	     * @static
	     * @access public
	     * @param string $str  SQL指令
	     * @return string
	     */
	    public function escapeString($str) {
	    	if($this->_linkID) {
	    		return  $this->_linkID->real_escape_string($str);
	    	}else{
	    		return addslashes($str);
	    	}
	    }
        
        /**
         * 验证数据 支持 in between equal length regex expire ip_allow ip_deny
         * @access public
         * @param string $value 验证数据
         * @param mixed $rule 验证表达式
         * @param string $type 验证方式 默认为正则验证
         * @return boolean
         */
        public function check($value,$rule,$type='regex'){
        	$type   =   strtolower(trim($type));
        	switch($type) {
        		case 'in': // 验证是否在某个指定范围之内 逗号分隔字符串或者数组
        		case 'notin':
        			$range   = is_array($rule)? $rule : explode(',',$rule);
        			return $type == 'in' ? in_array($value ,$range) : !in_array($value ,$range);
        		case 'between': // 验证是否在某个范围
        		case 'notbetween': // 验证是否不在某个范围
        			if (is_array($rule)){
        				$min    =    $rule[0];
        				$max    =    $rule[1];
        			}else{
        				list($min,$max)   =  explode(',',$rule);
        			}
        			return $type == 'between' ? $value>=$min && $value<=$max : $value<$min || $value>$max;
        		case 'equal': // 验证是否等于某个值
        		case 'notequal': // 验证是否等于某个值
        			return $type == 'equal' ? $value == $rule : $value != $rule;
        		case 'length': // 验证长度
        			$length  =  mb_strlen($value,'utf-8'); // 当前数据长度
        			if(strpos($rule,',')) { // 长度区间
        				list($min,$max)   =  explode(',',$rule);
        				return $length >= $min && $length <= $max;
        			}else{// 指定长度
        				return $length == $rule;
        			}
        		case 'expire':
        			list($start,$end)   =  explode(',',$rule);
        			if(!is_numeric($start)) $start   =  strtotime($start);
        			if(!is_numeric($end)) $end   =  strtotime($end);
        			return time() >= $start && time() <= $end;
        		case 'ip_allow': // IP 操作许可验证
        			return in_array(get_client_ip(),explode(',',$rule));
        		case 'ip_deny': // IP 操作禁止验证
        			return !in_array(get_client_ip(),explode(',',$rule));
        		case 'regex':
        		default:    // 默认使用正则验证 可以使用验证类中定义的验证名称
        			// 检查附加规则
        			return $this->regex($value,$rule);
        	}
        }
        
        /**
        * 处理数据
        * @param  array 
        * @param  string 
        * @return array 
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-8  下午10:43:53
        */
        private function _disposeDate($_data){
            
        }
        
        /**
        * 
        * @param  array 
        * @param  string 
        * @return array 
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-8  下午10:43:46
        */
        private function _method($_method = array()){
            //操作方法合并
            if(is_array($_method))
                $fun = array_merge($this->method,$_method);
            
            
        }
        
        /**
         * 数据库链接
         * @param array $_config
         * @param int 链接ID序列号
         * @return sourc $this->link
         * @author MaWei (http://www.phpyrb.com)
         * @date 2014-12-23 下午3:33:50
         */
        private function _connenct($_config){
            if(!isset($this->linkID)){
                //数据链接类型
                if($this->dbtype == 'mysqli'){
                    //新建mysqli链接
                    $this->linkID = new \mysqli($_config['hostname'], $_config['dbuser'], $_config['dbpasswd'], $_config['dbname'], $_config['dbport']);
                    //链接错误判断
                    if(mysqli_connect_errno()) exit(mysqli_connect_error());
                    $dbVersion = $this->linkID->server_vervion;
                    //设置编码
                    $this->linkID->query("SET NAMES '".$_config['dbcharset']."'");
                    //设置 sql_model
                    if($dbVersion >'5.0.1'){
                        $this->linkID->query("SET sql_mode=''");
                    }
                }elseif($this->dbtype == 'mysql'){
                    //是否是长链接
                    if($this->pconnect){
                        //新建mysql链接
                        $this->linkID = mysql_pconnect($_config['hostname'],$_config['dbuser'],$_config['dbpasswd'],131072);
                    }else{
                        //新建mysql链接
                        $this->linkID = mysql_connect($_config['hostname'],$_config['dbuser'],$_config['dbpasswd'],true,131072);
                    }
                    if(! $this->linkID || (! empty($_config['dbname']) && ! mysql_select_db($_config['dbname'],$this->linkID))){
                        exit(mysql_error());
                    }
                    $dbVersion = mysql_get_server_info($this->linkID);
                    //设置存取数据库编码
                    mysql_query("SET NAMES '".$_config['dbcharset']."'", $this->linkID);
                    //设置 sql_model
                    if($dbVersion >'5.0.1'){
                        mysql_query("SET sql_mode=''",$this->linkID);
                    }
                }else{
                    exit('Not Support DateBase Type!');
                }
            }
            return $this->linkID;
        }

        /**
        * 获取最近一次查询的sql语句 
        * @return string 
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-8  下午10:29:25
        */
        public function getlastsql($_model = ''){
        	return $this->Sql;
        }
        
        /**
        * 获取最近插入的ID
        * @return int 
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-8  下午10:31:53
        */
        public function getlastid(){
        	return $this->lastInsId;
        }
        
        /**
        * 获取最近的错误信息
        * @return string
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-8  下午10:30:24
        */
        public function geterror(){
        	return $this->error;
        }
        
        /**
        * 关闭数据库
        * @return volid 
        * @author MaWei (http://www.phpyrb.com)
        * @date 2015-2-8  下午10:53:59
        */
        public function close(){
        	if($this->linkID){
        		$this->linkID->close();
        	}
        	$this->linkID = null;
        }
        
        /**
         * 释放查询
         * @author MaWei (http://www.phpyrb.com)
         * @date 2014-12-23 下午3:47:15
         */
        public function free(){
            if(is_object($this->linkID)){
                $this->queryID->free_result();
            }
            $this->queryID = null;
        }
        
        /**
         * 数据库配置处理
         * @param array $_config 数据库配置
         * @param string 'DB_TYPE'=>'mysqli','DB_HOST'=>'localhost','DB_USER'=>'root',
         * 'DB_PWD'=>'','DB_NAME'=>'','DB_PORT'=>3306,'DB_PREFIX'=>'','DB_CHARSET'=>'utf8'
         * @return array $config
         * @author MaWei (http://www.phpyrb.com)
         * @date 2014-12-23 下午3:28:40
         */
        private function _parseConfig($_config = array()){
            $config = array();
            if($_config){
                // 数据库类型
                $config['dbtype'] = $_config['DB_TYPE'] ? $_config['DB_TYPE'] : 'mysqli';
                // 数据库地址
                $config['hostname'] = $_config['DB_HOST'] ? $_config['DB_HOST'] : 'localhost';
                // 数据库用户名
                $config['dbuser'] = $_config['DB_USER'];
                // 数据库密码
                $config['dbpasswd'] = $_config['DB_PWD'];
                // 数据库名
                $config['dbname'] = $_config['DB_NAME'];
                // 数据库端口
                $config['dbport'] = $_config['DB_PORT'] ? $_config['DB_PORT'] : '3306';
                // 数据库表前缀
                $config['dbprefix'] = $_config['DB_PREFIX'];
                // 数据库编码默认采用utf8
                $config['dbcharset'] = $_config['DB_CHARSET'] ? $_config['DB_CHARSET'] : 'utf8';
                return $config;
            }else{
                return 'Not DateBase Config!';
            }
        }
    }