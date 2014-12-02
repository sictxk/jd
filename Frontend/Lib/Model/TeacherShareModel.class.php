<?php
    class TeacherShareModel extends Model{
    	
		protected $tableName = 'teacher_share';
		protected $trueTableName = 'teacher_share';
		protected $fields = array('pkid',  'teacher_id', 'student_id', 'ctime', '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>