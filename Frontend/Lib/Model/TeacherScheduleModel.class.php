<?php
    class TeacherScheduleModel extends Model{
    	
		protected $tableName = 'teacher_schedule';
		protected $trueTableName = 'teacher_schedule';
		protected $fields = array('pkid', 'teacher_id', 'weekday', 'period','start_time', 'end_time', 'status','ctime','modify_time', '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>