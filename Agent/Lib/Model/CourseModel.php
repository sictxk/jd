<?php
    class CourseModel extends Model{
    	
		protected $tableName = 'course';
		protected $trueTableName = 'course';
		protected $fields = array('course_id',  'title', 'category_id','status', 'create_time',   '_pk'=>'course_id', '_autoinc'=>true);
		
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '标题必须', Model::MUST_VALIDATE),
			//array('title', '', '标题已经存在', Model::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
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