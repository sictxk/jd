<?php
    class OrderInfoModel extends Model{
    	
		protected $tableName = 'order_info';
		protected $trueTableName = 'order_info';
		protected $fields = array('order_id','order_sn', 'teacher_id', 'student_id', 'course_id','hourly_pay','hours','order_amount','area_id',
								'bespeak_place','bespeak_date','start_time','end_time','teacher_mobile','student_mobile','student_name', 'order_status',
								'cancel_time','cancel_reason','rating_status','ctime','modify_time','_pk'=>'order_id', '_autoinc'=>true);
		
		
    }
    
?>    