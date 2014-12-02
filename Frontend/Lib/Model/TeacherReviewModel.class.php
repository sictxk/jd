<?php
    class TeacherReviewModel extends Model{
    	
		protected $tableName = 'teacher_review';
		protected $trueTableName = 'teacher_review';
		protected $fields = array('pkid',  'order_id', 'teacher_id', 'student_id','rating','comment','ctime', '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>