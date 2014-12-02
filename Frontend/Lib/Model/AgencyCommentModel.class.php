<?php
    class AgencyCommentModel extends Model{
		protected $tableName = 'agency_comment';
		protected $trueTableName = 'agency_comment';
		protected $fields = array('pkid', 'agency_id', 'order_id','comment','score','user_id', 'bespeaked', 'reply_status','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('comment', 'require', '标题必须', Model::MUST_VALIDATE),
	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function'),
	    );
    	
    }
    
?>    