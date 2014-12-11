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
//     $phpquery = phpQuery::newDocumentFilePHP('http://www.biquge.la/book/1344/');
// //     print $phpquery->getDocument();
// //     pq('#list')->html();
// //     print phpQuery::getDocument('content');

       
//         $ret = pq('#list')->find('dd');
//         $part = array();
//         foreach($ret as $k => $name){
//             $part[$k]['title'] = pq($name)->text();
//             $part[$k]['url'] = pq($name)->find('a')->attr('href');
//          }
//         dump($part);exit;
    $ph = phpQuery::newDocumentFileHTML('http://www.biquge.la/book/80/64636.html');
    $p = pq('#content');
    foreach ($p as $v){
        $p = $p->text();
    }
    dump($p);
    
    
    
    
    
    
    
    class QueryList{
        
        function reArray(){
            
        }
    }