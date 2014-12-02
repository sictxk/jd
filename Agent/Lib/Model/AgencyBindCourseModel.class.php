<?php
    class AgencyBindCourseModel extends Model{
    	
		protected $tableName = 'agency_bind_course';
		protected $trueTableName = 'agency_bind_course';
		protected $fields = array('pkid',  'agency_id', 'course_id', 'ctime', '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>