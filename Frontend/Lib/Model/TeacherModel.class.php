<?php
    class TeacherModel extends Model{
    	
		protected $tableName = 'teacher';
		protected $trueTableName = 'teacher';
		protected $fields = array('pkid', 'user_id', 'homepage', 'college','career_status', 'resume', 'certificates', 'description', 
		  'trial_learn', 'city_id', 'share_num', 'service_mode','ctime','modify_time', '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>