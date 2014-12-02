<?php
class TokyoTyrantModel{
	
	protected $db_host = 'localhost';

	function __construct($db_host)
	{
		//连接远程数据库. 
		$tt = new TokyoTyrant();
		
		$tt->connect($db_host, TokyoTyrant::RDBDEF_PORT);
		
	}
	
	/*
	 * 增加int或double值，返回值是增长之后该key对应的新值,如果key不存在则创建一个新的并以increment参数作为初始值, 
	 * type取值为TokyoTyrant::RDBREC_INT或TokyoTyrant::RDBREC_DBL, 分别代表将increment参数的值作为int, double处理.   
	 */
	function tt_add($key,$num){
		
		if(intval($num)){
			
			$arr=$tt->add($key, $num);
			
		}else{
			
			$arr=$tt->add($key, $num, TokyoTyrant::RDBREC_DBL);
			
		}
		
		return $arr;
	}
	
	
	/*
	 * 连接远程数据库.  
	 * $options可以包括timeout(超时时间, 默认5.0), reconnect(默认True), persistent(默认True)  
	 * 返回当前连接对象, 如果失败抛出TokyoTyrantException   
     */
	function tt_content($db_host,$port){
		
		$tt = new TokyoTyrant();
		
		$tt->connect($db_host, $port);
		
	}
	
	/*
	 * 获取一个迭代器, 用于迭代所有的key/value, 返回的是一个TokyoTyrantIterator对象如果失败抛出TokyoTyrantException. 
	 */
	function tt_getIterator(){
		
		$it = $tt->getIterator();

		foreach ($it as $k => $v) {
		
		}
		
	}
	
	//创建一个当前数据库的拷贝. path参数指定要拷贝到的路径, 用户必须要有文件的写权限. 
	function tt_copy($url){
		
		$tt->copy($url);
		
	}
	
	/*
	 * 执行一个远程脚本扩展.指的就是启动ttserver时通过-ext指定的lua脚本文件中定义的函数.
	 * name: 要执行的函数名称.  
	 * options: TokyoTyrant::RDBXO_LCKREC用于记录锁定, TokyoTyrant::RDBXO_LCKGLB用于全局锁定.  
	 * key: 要传递给函数的key.  
	 * value: 要传递给函数的value.  
	 * 返回脚本函数执行的结果.     
	 */
	function tt_ext($name,$key,$value){
		
		$tt->ext($name, TokyoTyrant::RDBXO_LCKREC, $key, $value);
		
	}
	
	
	/*
	 * 通过key前缀匹配获取指定条数的记录.  
	 * prefix: 用以匹配的key前缀.  
	 * max_recs: 返回的记录条数.  
	 * 以数组形式返回匹配到的key. 
	 */
	
    /*function tt_fwmKeys( $prefix,$max_recs,$valuename){
		
		for ($i = 0; $i < $max_recs; $i++) {
		    $tt->put("key_" . $i, "value_" . $i);
		}
		
		for ($i = 0; $i < $max_recs; $i++) {
   	 		$tt->put("something_" . $i, "data_" . $i);
		}

		var_dump($tt->fwmKeys("key_", 5));
		
	}*/
	
	
	/*
	 * 用于获取一个或多个值, 接受一个字符串或一个字符串数组的key.  
	 * 根据接受参数不同, 返回单个的字符串或数组. 发生错误是抛出TokyoTyrantException, 如果key没有找到, 返回空字符串, 
	 * 在传递了数组参数时仅仅所有key都存在才会返回, 不会因为一个key找不到而返回错误.   
	 */
	function tt_get($keys){
		
		$arr=$tt->get($keys);
		
		return $arr;
	}
	
 	//获取数据库内的记录总条数.   
	function tt_num(){
		
		$regist=$tt->num();
		
		return $regist;
	}
	/*
	 * 通过参数指定的一个或多个key移除记录.  
	 * keys: 一个字符串或字符串数组  
	 * 返回当前TokyoTyrant对象或在失败时抛出TokyoTyrant异常. 
	 */
	
	function tt_out($keys){
		
		$tt->out($keys);
		
	}
	
	/*
	 * 将一个或多个key-value对插入到数据库中, 如果keys是字符串, 第二个参数就是对应的value, 
	 * 如果第一个参数是数组, 第二个参数无效, 是数组的时候, 数组自身维护key-value, 如果key存在, 则替换.  
	 * 返回当前连接对象TokyoTyrant或者在失败时抛出TokyoTyrantException.   
	 */
	function tt_put($keys,$value){
		
		$tt->put($keys, $value);
		
	}
	
	/*
	 * 如果keys是数组, 将value追加到已经存在的key原值之后, 第二个参数只有在keys是字符串时有效,
	 * 如果记录不存在, 创建新的记录.  
	 * 返回当前连接对象TokyoTyrant或者在失败时抛出TokyoTyrantException.   
	 */
	function tt_putCat($keys,$value){
		
		$tt->putCat($keys, $value);
		
	}
	
	/*
	 * 向数据库插入一个或多个key-value对, 如果keys是字符串, 第二个参数就是它对应的value, 
	 * 如果第一个参数是数组, 第二个参数失效. 如果key已经存在, 这个方法抛出一个异常标示该记录已经存在.  
	 * 返回当前连接对象TokyoTyrant或者在失败时抛出TokyoTyrantException.   
	 */
	function tt_putKeep($keys,$value){
		try {
			
		    $tt->putKeep($keys, $value);
		    
		} catch (TokyoTyrantException $e) {
			
		    if ($e->getCode() === TokyoTyrant::TTE_KEEP) {
		    	
		        echo "Existing record! Not modified\n";
		        
		    } else {
		    	
		        echo "Error: " , $e->getMessage() , "\n"; 
		        
		    }
		}
	}
	
	
	/*
	 * 向数据库插入一个或多个key-value, 这个方法不会等待服务端的响应.  
	 * 返回当前连接对象TokyoTyrant或者在失败时抛出TokyoTyrantException.   
	 */
	function tt_putNr($array){

		$tt->putNr($array);

	}
	
	/*
	 * 连接一条记录并自左端开始截掉$width个字符.  
	 * 返回当前连接对象TokyoTyrant或者在失败时抛出TokyoTyrantException.   
	 */
	function tt_putShl($keys,$value,$size=0){
		
		$tt->putShl($keys, $value, $size);
		
	}
	
	/*调整数据库连接参数.  
	 * 超时重新连接  
	 * 返回当前连接对象TokyoTyrant或者在失败时抛出TokyoTyrantException.   */
	function tt_tune(){
		
		$tt->tune();
		
	}
	
	/*
	 * 获取指定key对应的value的大小.  
	 * 返回对应value的大小或在失败时抛出一个TokyoTyrantException.   
	 */
	function tt_size($keys){
		
		$tt->size($keys);
		
	}
	
	//返回远程数据库的统计数据, 返回值是数组形式.   
	function tt_stat(){
	
		$tt->stat();
		
	}
	
	//将远程数据库清空.返回当前连接对象TokyoTyrant或者在失败时抛出TokyoTyrantException.   
	function tt_vanish(){
		
		$tt->vanish();
		
	}

}

?>