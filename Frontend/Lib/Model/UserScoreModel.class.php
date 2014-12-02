<?php
    class UserScoreModel extends Model{
    	
		protected $tableName = 'user_score';
		protected $trueTableName = 'user_score';
		protected $fields = array('pkid','user_id','order_id','s_type', 'score','mark','ctime','_pk'=>'pkid', '_autoinc'=>true);
		
		
    }
    
?>    