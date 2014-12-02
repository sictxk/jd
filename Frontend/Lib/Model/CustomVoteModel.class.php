<?php
    class CustomVoteModel extends Model{
    	
		protected $tableName = 'custom_vote';
		protected $fields = array('pkid', 'vote','user_id','ctime', '_pk'=>'pkid', '_autoinc'=>true);
		
		// ×Ô¶¯Ìî³äÉèÖÃ
	    protected $_auto = array(
	        array('ctime', 'time', self::MODEL_INSERT, 'function')
	    );
    }
    
?>    