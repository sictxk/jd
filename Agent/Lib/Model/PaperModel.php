<?php
    class PaperModel extends Model{
    	
		protected $tableName = 'paper';
		protected $fields = array('pkid', 'title','grade_id', 'course_id', 'term', 'path','size','hits_num','source','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// 自动验证设置
	    protected $_validate = array(
	        array('title', 'require', '标题必须', Model::MUST_VALIDATE),
	        array('grade_id', 'require', '分类必须', Model::MUST_VALIDATE),
	        array('course_id', 'require', '科目必须', Model::MUST_VALIDATE),

	    );
		
		// 自动填充设置
	    protected $_auto = array(
	        array('hits_num', '0', self::MODEL_INSERT),
	        array('status', 'Y', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    