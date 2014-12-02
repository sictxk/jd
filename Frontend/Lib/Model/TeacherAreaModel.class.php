<?php
    class TeacherAreaModel extends Model{
    	
		protected $tableName = 'teacher_area';
		protected $trueTableName = 'teacher_area';
		protected $fields = array('pkid', 'user_id','teacher_id', 'city_id','area_id', 'ctime', '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>