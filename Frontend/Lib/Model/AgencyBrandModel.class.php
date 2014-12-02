<?php
    class AgencyBrandModel extends Model{
		protected $tableName = 'agency_brand';
		protected $trueTableName = 'agency_brand';
		protected $fields = array('pkid', 'title','logo','intro','picture','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '标题必须', Model::MUST_VALIDATE),
	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function'),

	    );
    	
    }
    
?>    