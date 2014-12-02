<?php
    class AdminModel extends Model{
    	
		protected $tableName = 'admin';
		//protected $trueTableName = 'admin';
		protected $fields = array('pkid', 'account', 'password', 'email','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('account', 'require', '账号必须', Model::MUST_VALIDATE),
			//array('account', '', '账号已经存在', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        array('password', 'require', '链接必须', Model::MUST_VALIDATE),
	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    