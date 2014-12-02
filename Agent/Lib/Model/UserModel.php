<?php
    class UserModel extends Model{
    	
		protected $tableName = 'user';
		protected $trueTableName = 'user';
		protected $fields = array('pkid', 'nickname', 'login_email', 'login_pass','gendar', 'birth_year', 'birth_month', 'birth_day', 
		 'motto', 'interests', 'avatar', 'mobile', 'alipay', 'hits', 'is_teacher', 'ctime','modify_time','status', '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>