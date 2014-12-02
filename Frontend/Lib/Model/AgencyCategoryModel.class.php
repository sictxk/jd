<?php
    class AgencyCategoryModel extends Model{
		protected $tableName = 'agency_category';
		protected $trueTableName = 'agency_category';
		
		 public	function getCategoryTree(){
		 	 $rootCategory = $this->getRootCategory();
		 	 foreach($rootCategory as $key=>$first){
		 	 	 $secondCategory = $this->getSubCategory($first['pkid']);
		 	 	 foreach($secondCategory as $k=>$second){
		 	 	 	 $secondCategory[$k]['subCate'] = $this->getSubCategory($second['pkid']);
		 	 	 }
		 	 	 $CategoryTree[$first['pkid']] = $secondCategory;
		 	 }
		 	 return $CategoryTree;
		 }
			
		 public	function getRootCategory(){
		 	 return $this->where("level=1")->select();
		 }
		 
		 public	function getSubCategory($parentId){
		 	 return $this->field('pkid,title')->where("parent_id=".$parentId)->select();
		 }
	 
    }
    
?>    