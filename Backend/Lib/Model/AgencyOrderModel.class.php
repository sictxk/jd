<?php
    class AgencyOrderModel extends Model{
    	
		protected $tableName = 'agency_order';
		protected $trueTableName = 'agency_order';
		protected $fields = array('order_id','order_sn', 'agency_id', 'user_id',
								'bespeak_date','visitor_mobile','visitor_name', 'visitor_age','vouchsafe', 'order_status',
                                'cancel_time', 'cancel_reason','ctime','modify_time','_pk'=>'order_id', '_autoinc'=>true);


    }
    
?>  