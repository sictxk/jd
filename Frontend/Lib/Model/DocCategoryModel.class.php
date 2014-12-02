<?php
    class DocCategoryModel extends Model{
		protected $tableName = 'doc_category';
		protected $trueTableName = 'note';
		protected $fields = array('pkid', 'title', 'parent_id','level','ctime', 'status',  '_pk'=>'pkid', '_autoinc'=>true);
		


    }
    
?>    