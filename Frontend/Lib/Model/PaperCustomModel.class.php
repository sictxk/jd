<?php
    class PaperCustomModel extends Model{
    	
		protected $tableName = 'paper_custom';
		protected $fields = array('pkid','grade_id', 'course_id', 'term','user_id','ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);
		
		// �Զ��������
	    protected $_auto = array(
	        array('status', 'N', self::MODEL_INSERT),
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    