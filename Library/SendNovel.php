<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain: 小说发布处理
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: MaWei <1123265518@qq.com>
	*	+----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2015-1-7 下午3:55:28
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/
    
    namespace Library;
    
    
    class SendNovel {
        
        function __construct(){
            
        }
        
        
        /**
         * 把小说章节写入文件
         * @param int $_novelid 小说ID
         * @param int $_chapter 小说章节ID
         * @param string $_content 章节内容
         * @return boolean|string
         * @author MaWei (http://www.phpyrb.com)
         * @date 2015-1-7 下午3:52:19
         */
        function SaveChapter($_novelid,$_chapterid,$_content){
            $basepath = C('Novel_Save_Path') ? C('Novel_Save_Path') : '/Novel/'.date('Y-m').'/'.$_novelid.'/';
            $name = $_chapterid.'txt';
            createDir($basepath);
            if($_content && file_put_contents($basepath.$name, $_content)){
                return $basepath.$name;
            }else{
                return null;
            }
        }
        
    }