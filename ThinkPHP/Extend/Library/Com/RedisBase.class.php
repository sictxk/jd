<?php
class RedisBase {
	
	var $server;
    var $port;
    var $_sock;
 
    function RedisBase($host='localhost', $port=6379) {
        $this->host = $host;
        $this->port = $port;
        $this->connect();
    }
    
    function connect() {
        if ($this->_sock)
            return;
        if ($sock = fsockopen($this->host, $this->port, $errno, $errstr)) {
            $this->_sock = $sock;
            return;
        }
        $msg = "Cannot open socket to {$this->host}:{$this->port}";
        if ($errno || $errmsg)
            $msg .= "," . ($errno ? " error $errno" : "") . ($errmsg ? " $errmsg" : "");
        trigger_error("$msg.", E_USER_ERROR);
    }
    
    function disconnect() {
        if ($this->_sock)
            @fclose($this->_sock);
        $this->_sock = null;
    }
    
	// 使用客户端向 Redis 服务器发送一个 PING
	// 如果连接正常就返回一个 PONG ，否则返回一个连接错误
    function &ping() {
		
        $this->_write("PING\r\n");
        return $this->get_response();
    }
    
	// 打印一个特定的信息 message ，测试时使用
    function &do_echo($s) {

        $this->_write("ECHO " . strlen($s) . "\r\n$s\r\n");
        return $this->_get_value();
    }
    
	// 将字符串值 value 关联到 key
	// $preserve 是否保留 key 的原始值
    function &set($name, $value, $preserve=false) {

        $this->_write(
            ($preserve ? 'SETNX' : 'SET') .
            " $name " . strlen($value) . "\r\n$value\r\n"
        );
        return $this->get_response();
    }
    
	// 返回 key 所关联的字符串值
	// 如果 key 不存在那么返回特殊值 null
    function &get($name) {

        $this->_write("GET $name\r\n");
        return $this->_get_value();
    }
    
	// 将 key 中储存的数字值增加
    function &incr($name, $amount=1) {

        if ($amount == 1)
            $this->_write("INCR $name\r\n");
        else
            $this->_write("INCRBY $name $amount\r\n");
        return $this->get_response();
    }
    
	// 将 key 中储存的数字值减少
    function &decr($name, $amount=1) {

        if ($amount == 1)
            $this->_write("DECR $name\r\n");
        else
            $this->_write("DECRBY $name $amount\r\n");
        return $this->get_response();
    }
    
	// 检查给定 key 是否存在
    function &exists($name) {

        $this->_write("EXISTS $name\r\n");
        return $this->get_response();
    }
    
	// 删除给定的 key
    function &delete($name) {

        $this->_write("DEL $name\r\n");
        return $this->get_response();
    }
    
	// 查找所有符合给定模式 pattern 的 key
    function &keys($pattern) {

        $this->_write("KEYS $pattern\r\n");
        return explode(' ', $this->_get_value());
    }
    
	// 从当前数据库中随机返回(不删除)一个 key
    function &randomkey() {

        $this->_write("RANDOMKEY\r\n");
        return $this->get_response();
    }
    
	// 将 key 改名为 newkey
	// $preserve 判断 不存在时是否改名
    function &rename($src, $dst, $preserve=false) {

        $this->_write($preserve ? "RENAMENX $src $dst\r\n" : "RENAME $src $dst\r\n");
        return $this->get_response();
    }
    
	// 为给定 key 设置生存时间，当 key 过期时(生存时间为 0 )，它会被自动删除
    function &expire($name, $time) {

        $this->_write("EXPIRE $name $time\r\n");
        return $this->get_response();
    }
    
	// 
    function &push($name, $value, $tail=true) {
        // default is to append the element to the list

        $this->_write(
            ($tail ? 'RPUSH' : 'LPUSH') .
            " $name " . strlen($value) . "\r\n$value\r\n"
        );
        return $this->get_response();
    }
    
    function &ltrim($name, $start, $end) {

        $this->_write("LTRIM $name $start $end\r\n");
        return $this->get_response();
    }
    
    function &lindex($name, $index) {

        $this->_write("LINDEX $name $index\r\n");
        return $this->_get_value();
    }
    
    function &pop($name, $tail=true) {

        $this->_write(
            ($tail ? 'RPOP' : 'LPOP') .
            " $name\r\n"
        );
        return $this->_get_value();
    }
    
    function &llen($name) {

        $this->_write("LLEN $name\r\n");
        return $this->get_response();
    }
    
    function &lrange($name, $start, $end) {

        $this->_write("LRANGE $name $start $end\r\n");
        return $this->get_response();
    }

    function &sort($name, $query=false) {

        $this->_write($query == false ? "SORT $name\r\n" : "SORT $name $query\r\n");
        return $this->get_response();
    }
    
    function &lset($name, $value, $index) {

        $this->_write("LSET $name $index " . strlen($value) . "\r\n$value\r\n");
        return $this->get_response();
    }
    
    function &sadd($name, $value) {

        $this->_write("SADD $name " . strlen($value) . "\r\n$value\r\n");
        return $this->get_response();
    }
    
    function &srem($name, $value) {

        $this->_write("SREM $name " . strlen($value) . "\r\n$value\r\n");
        return $this->get_response();
    }
    
    function &sismember($name, $value) {

        $this->_write("SISMEMBER $name " . strlen($value) . "\r\n$value\r\n");
        return $this->get_response();
    }
    
    function &sinter($sets) {

        $this->_write('SINTER ' . implode(' ', $sets) . "\r\n");
        return $this->get_response();
    }
    
    function &smembers($name) {

        $this->_write("SMEMBERS $name\r\n");
        return $this->get_response();
    }

    function &scard($name) {

        $this->_write("SCARD $name\r\n");
        return $this->get_response();
    }
    
    function &select_db($name) {

        $this->_write("SELECT $name\r\n");
        return $this->get_response();
    }
    
    function &move($name, $db) {

        $this->_write("MOVE $name $db\r\n");
        return $this->get_response();
    }
    
    function &save($background=false) {

        $this->_write(($background ? "BGSAVE\r\n" : "SAVE\r\n"));
        return $this->get_response();
    }
    
    function &lastsave() {

        $this->_write("LASTSAVE\r\n");
        return $this->get_response();
    }
    
    function &flush($all=false) {

        $this->_write($all ? "FLUSH\r\n" : "FLUSHDB\r\n");
        return $this->get_response();
    }
    
    function &info() {

        $this->_write("INFO\r\n");
        $info = array();
        $data =& $this->get_response();
        foreach (explode("\r\n", $data) as $l) {
            if (!$l)
                continue;
            list($k, $v) = explode(':', $l, 2);
            $_v = strpos($v, '.') !== false ? (float)$v : (int)$v;
            $info[$k] = (string)$_v == $v ? $_v : $v;
        }
        return $info;
    }
    
    function &_write($s) {
        while ($s) {
            $i = fwrite($this->_sock, $s);
            if ($i == 0) // || $i == strlen($s))
                break;
            $s = substr($s, $i);
        }
    }
    
    function &_read($len=1024) {
        if ($s = fgets($this->_sock))
            return $s;
        $this->disconnect();
        trigger_error("Cannot read from socket.", E_USER_ERROR);
    }
    
    function &get_response() {
        $data = trim($this->_read());
        $c = $data[0];
        $data = substr($data, 1);
        switch ($c) {
            case '-':
                trigger_error(substr($data, 0, 4) == 'ERR ' ? substr($data, 4) : $data, E_USER_ERROR);
                break;
            case '+':
                return $data;
            case '*':
                $num = (int)$data;
                if ((string)$num != $data)
                    trigger_error("Cannot convert multi-response header '$data' to integer", E_USER_ERROR);
                $result = array();
                for ($i=0; $i<$num; $i++)
                    $result[] =& $this->_get_value();
                return $result;
            default:
                return $this->_get_value($c . $data);
        }
    }
    
    function &_get_value($data = null) {
        if ($data === null)
            $data =& trim($this->_read());
        if ($data == '$-1')
            return null;
        $c = $data[0];
        $data = substr($data, 1);
        $i = strpos($data, '.') !== false ? (int)$data : (float)$data;
        if ((string)$i != $data)
            trigger_error("Cannot convert data '$c$data' to integer", E_USER_ERROR);
        if ($c == ':')
            return $i;
        if ($c != '$')
            trigger_error("Unkown response prefix for '$c$data'", E_USER_ERROR);
        $buffer = '';
        while (true) {
            $data =& $this->_read();
            $i -= strlen($data);
            $buffer .= $data;
            if ($i < 0)
                break;
        }
        return substr($buffer, 0, -2);
    }

    
}   
//$r =& new Redis();
//var_dump($r->info());

?>