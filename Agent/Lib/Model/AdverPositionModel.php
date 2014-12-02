<?php
    class AdverPositionModel extends Model{
    	
		protected $tableName = 'adver_position';
		protected $fields = array('pkid', 'title', 'width', 'height', '_pk'=>'pkid', '_autoinc'=>true);

    }
    
?>    