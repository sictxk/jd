<?php
    class TeacherCourseModel extends Model{
    	
		protected $tableName = 'teacher_course';
		protected $trueTableName = 'teacher_course';
		protected $fields = array('pkid',  'teacher_id', 'course_id', 'hourly_pay', 'promote_price', 'ctime', '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>