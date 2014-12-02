<?php
    class AgencyPictureModel extends Model{
		protected $tableName = 'agency_picture';
		protected $trueTableName = 'agency_picture';
		protected $fields = array('pkid', 'agency_id','picture','title','ctime', 'status',  '_pk'=>'pkid', '_autoinc'=>true);
		
    }
    
?>    