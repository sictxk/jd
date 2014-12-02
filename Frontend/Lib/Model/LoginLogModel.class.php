<?php
    class LoginLogModel extends Model{
    	
		protected $tableName = 'login_log';
		protected $trueTableName = 'login_log';
		
		public function addLog($data){
			return $this->data($data)->add();
		}
		
		public function getMacLog($userId){
			$sql = "SELECT distinct mac_address FROM login_log WHERE user_id=".$userId." order by pkid desc limit 3";
			return $this->query($sql);
		}
    }
    
?>