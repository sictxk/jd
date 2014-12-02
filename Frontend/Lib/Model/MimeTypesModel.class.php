<?php
	
    class MimeTypesModel extends Model{
		protected $tableName = 'mime_types';
		protected $fields = array('pkid', 'ext', 'mime_type',  '_pk'=>'pkid', '_autoinc'=>true);
    }
    
?>    