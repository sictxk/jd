<?php
    class TeacherModel extends Model{
    	
		protected $tableName = 'teacher';
		protected $trueTableName = 'teacher';
		protected $fields = array('pkid','truename', 'email', 'mobile','aplipay', 'code',  'resume', 'certificates', 'description', 
		 'avatar','service_mode','city_id','rating', 'ctime','modify_time', '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>