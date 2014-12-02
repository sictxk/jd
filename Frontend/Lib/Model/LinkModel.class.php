<?php
    class LinkModel extends Model{
    	
		protected $tableName = 'link';
		//protected $trueTableName = 'link';
		protected $fields = array('pkid', 'title', 'url', 'thumb','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '标题必须', Model::MUST_VALIDATE),
			//array('title', '', '标题已经存在', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        array('url', 'require', '链接必须', Model::MUST_VALIDATE),
	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    