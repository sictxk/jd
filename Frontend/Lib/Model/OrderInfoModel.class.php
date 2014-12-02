<?php
    class OrderInfoModel extends Model{
    	
		protected $tableName = 'order_info';
		protected $trueTableName = 'order_info';
		protected $fields = array('order_id','order_sn', 'teacher_id', 'student_id', 'course_id','hourly_pay','hours','order_amount','commission_fee','total_amount','bespeak_place','mark',
								'bespeak_date','start_time','end_time','teacher_mobile','student_mobile','teacher_name','student_name', 'order_status','pay_status','alipay_code',
                                'pay_time','cancel_time', 'cancel_reason','ctime','modify_time','_pk'=>'order_id', '_autoinc'=>true);


    }
    
?>  