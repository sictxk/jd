<?php
    class NoteModel extends Model{
		protected $tableName = 'note';
		protected $trueTableName = 'note';
		protected $fields = array('note_id', 'teacher_id', 'title', 'content', 'image', 'status', 'create_time', 'modify_time',  '_pk'=>'notice_id', '_autoinc'=>true);
		
		
    	
    }
    
?>    