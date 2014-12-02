<?php
    class AgencyCourseModel extends Model{
		protected $tableName = 'agency_course';
		protected $trueTableName = 'agency_course';
		protected $fields = array('pkid', 'title', 'category_id','second_id','third_id','create_time', 'status',  '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '名称必须', Model::MUST_VALIDATE),
			//array('title', '', '名称已经存在', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
	        array('category_id', 'require', '分类必须', Model::MUST_VALIDATE),
	        //array('context', 'require', '内容必须', Model::MUST_VALIDATE)
	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('status', 'Y', self::MODEL_INSERT),
	        array('create_time', 'time', self::MODEL_INSERT, 'function')
	    );

    }
    
?>    