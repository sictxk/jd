<?php
    class ArticleModel extends Model{
		protected $tableName = 'article';
		protected $trueTableName = 'article';
		protected $fields = array('pkid', 'title','author','source', 'intro', 'context', 'ctime', 'status', '_pk'=>'pkid', '_autoinc'=>true);

		

    	
    }
    
?>    