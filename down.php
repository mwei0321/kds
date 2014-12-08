<?php
	/**
	*  +----------------------------------------------------------------------------------------------+
	*   | Explain: 
	*  +----------------------------------------------------------------------------------------------+
	*   | Author: MaWei <1123265518@qq.com>
	*	+----------------------------------------------------------------------------------------------+
	*   | Creater Time : 2014-12-8 下午3:17:09
	*  +----------------------------------------------------------------------------------------------+
	*   | Link :		http://www.phpyrb.com	     
	*  +----------------------------------------------------------------------------------------------+
	**/

    function dump($v){
        return var_dump($v);
    }
    require_once 'phpQuery.php';
    $phpquery = phpQuery::newDocumentFilePHP('http://www.biquge.la/book/1344/');
//     print $phpquery->getDocument();
//     pq('#list')->html();
//     print phpQuery::getDocument('content');

       
        $ret = pq('#list')->find('dd a');
        $part = array();
        foreach($ret as $k => $name){
            $part[$k] = pq($name)->html();
            $part[$k]['url'] = pq($name)->attr('href')[0];
        }
        dump($part);exit;
    
    
    
    
    
    
    
    class QueryList{
        
        function reArray(){
            
        }
    }