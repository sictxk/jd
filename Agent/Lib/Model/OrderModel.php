<?php
    class OrderModel extends Model{
    	
		protected $tableName = 'order';
		protected $trueTableName = 'morder';
		protected $fields = array('order_id','order_sn', 'teacher_id', 'student_id', 'course_id','hourly_pay','hours','order_amount','bespeak_place',
								'bespeak_date','start_time','end_time','teacher_mobile','student_mobile','student_name', 'order_status','cancel_time',
								 'cancel_reason','create_time','modify_time','_pk'=>'order_id', '_autoinc'=>true);
		
		
    }
    
?>    