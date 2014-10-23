<?php
/**
*  +----------------------------------------------------------------------------------------------+
*   | Explain: 分布类
*  +----------------------------------------------------------------------------------------------+
*   | Author: ONLY <491518132@qq.com>
*	+----------------------------------------------------------------------------------------------+
*   | Creater Time : 2014-1-17 	
*  +----------------------------------------------------------------------------------------------+
*   | Link :		http://www.phpyrb.com	     
*  +----------------------------------------------------------------------------------------------+
**/
namespace Vendor;

class Page{
	public $firstRow; // 起始行数
	public $listRows; // 列表每页显示行数
	public $parameter; // 分页跳转时要带的参数
	public $totalRows; // 总行数
	public $totalPages; // 分页总页面数
	public $rollPage   = 5;// 分页栏每页显示的页数
	public $lastSuffix = true; // 最后一页是否显示总页数

	private $p       = 'p'; //分页参数名
	private $url     = ''; //当前链接URL
	private $nowPage = 1;
	private $action  = '';
	private $ajax    = false;
	private $repid   = '#rephtml';

	// 分页显示定制
	private $config  = array(
			'header' => '<a class="textpage" href="javascript:void(0);">%TOTAL_ROW% 条</a>',
			'prev'   => '上一页',
			'next'   => '下一页',
			'first'  => '1...',
			'last'   => '...%TOTAL_PAGE%',
			'theme'  => '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% <a href="javascript:void(0);">%NOW_PAGE%/%TOTAL_PAGE%页</a>',
// 			'theme'  => '%HEADER% %UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% <a href="javascript:void(0);">%NOW_PAGE%/%TOTAL_PAGE%页</a>',
// 			'theme'  => '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ',
	);

	/**
	 * 架构函数
	 * @param array $totalRows  总的记录数
	 * @param array $listRows  每页显示记录数
	 * @param array $parameter  分页跳转的参数
	*/
	public function __construct($totalRows, $listRows,$_ajax = array(),$parameter = array()) {
		C('VAR_PAGE') && $this->p = C('VAR_PAGE'); //设置分页参数名称
		/* 基础设置 */
		$this->totalRows  = $totalRows; //设置总记录数
		$this->listRows   = $listRows;  //设置每页显示行数
		$this->parameter  = empty($parameter) ? $_GET : $parameter;
		$this->nowPage    = empty($_REQUEST[$this->p]) ? 1 : intval($_REQUEST[$this->p]);
		$this->firstRow   = $this->listRows * ($this->nowPage - 1);
// 		$this->action     = $_url ? $_url : ACTION_NAME;

		//ajax分页配置
		$this->ajax       = $_ajax['ajax'];
		$_ajax['repid']   && $this->repid = $_ajax['repid'];
		
		/* 生成URL */
		!$_ajax['ajax'] && $this->parameter[$this->p] = 'PAGE';
		$this->url = $_ajax['url'] ? $_ajax['url'] : U(ACTION_NAME,$this->parameter);
	}

	/**
	 * 定制分页链接设置
	 * @param string $name  设置名称
	 * @param string $value 设置值
	 */
	public function setConfig($name,$value) {
		if(isset($this->config[$name])) {
			$this->config[$name] = $value;
		}
	}

	/**
	 * 生成链接URL
	 * @param  integer $page 页码
	 * @return string
	 */
	private function url($page){
		if($this->ajax){
			return 'javascript:AjaxPages('.$page.');';
		}else{
			return str_replace('PAGE', $page, $this->url);
		}
		
	}

	/**
	 * 组装分页链接
	 * @return string
	 */
	public function show() {
		if(0 == $this->totalRows) return '';

// 		/* 生成URL */
// 		$this->parameter[$this->p] = 'PAGE';
// 		$this->url = U($this->action, $this->parameter);
		/* 计算分页信息 */
		$this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
		if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
			$this->nowPage = $this->totalPages;
		}

		/* 计算分页零时变量 */
		$now_cool_page      = $this->rollPage/2;
		$now_cool_page_ceil = ceil($now_cool_page);
		$this->lastSuffix && $this->config['last'] = $this->totalPages;
		
		//上一页
		$up_row  = $this->nowPage - 1;
		$up_page = $up_row > 0 ? '<a href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a>' : '';
		
		//下一页
		$down_row  = $this->nowPage + 1;
		$down_page = ($down_row <= $this->totalPages) ? '<a href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a>' : '';

		//第一页
		$the_first = '';
		if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1){
			$the_first = '<a href="' . $this->url(1) . '">' . $this->config['first'] . '</a>';
		}

		//最后一页
		$the_end = '';
		if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
			$the_end = '<a href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a>';
		}

		//数字连接
		$link_page = "";
		for($i = 1; $i <= $this->rollPage; $i++){
			if(($this->nowPage - $now_cool_page) <= 0 ){
				$page = $i;
			}elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
				$page = $this->totalPages - $this->rollPage + $i;
			}else{
				$page = $this->nowPage - $now_cool_page_ceil + $i;
			}
			if($page > 0 && $page != $this->nowPage){

				if($page <= $this->totalPages){
					$link_page .= '<a href="' . $this->url($page) . '">' . $page . '</a>';
				}else{
					break;
				}
			}else{
				if($page > 0 && $this->totalPages != 1){
					$link_page .= '<a class="current" href="javascript:void(0);">' . $page . '</a>';
				}
			}
		}

		//替换分页内容
		$show = str_replace(
				array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),
				array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages),
				$this->config['theme']);
		$script = '<script>var AjaxPages = function (p){$.ajax({type:"post",url:"'.$this->url.'",data:"ajaxPage=y&p="+p,dataType:"json"}).done(function (e){if(e){$("'.$this->repid.'").html(e.html);$("#repacepages").html(e.show);}});}</script>';
		return $this->ajax ? $script.'<div id="repacepages">'.$show.'</div>' : $show;
	}
}