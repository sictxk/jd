$$(function(){
		// 为 SELECT 添加事件
		$$("#grade_id").change(function() {
			var grade_id = $$("#grade_id").val();
			if(grade_id!=''){
				$$.ajax({
					type:"GET",
					url: "/Supervisor/Paper/getcourse/",
					data: {grade_id:$$("#grade_id").val()},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						// 回调函数，将返回的数据添加到 P 标签中
						$$("#source_panel").html(data);
					}
				});
			}else{
				$$("#source_panel").html("");
			}
		});
		
		// 为 SELECT 添加事件
		$$("#category_id").change(function() {
			var category_id = $$("#category_id").val();
			if(category_id!=''){
				$$.ajax({
					type:"GET",
					url: "/Supervisor/Common/getcourse_select/",
					data: {category_id:$$("#category_id").val()},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						// 回调函数，将返回的数据添加到 P 标签中
						$$("#source_panel").html(data);
					}
				});
			}else{
				$$("#source_panel").html("");
			}
		});
		
		$$(".select #city_id").change(function() {
			var city_id = $$("#city_id").val();
			if(city_id!=''){
				$$.ajax({
					type:"GET",
					url: "/Supervisor/Common/getarea_select/",
					data: {city_id:$$("#city_id").val()},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						$$("#area_panel").html(data);
					}
				});
			}else{
				$$("#area_panel").html("");
			}
		});
		
		$$("#city_id").change(function() {
			var city_id = $$("#city_id").val();
			if(city_id!=''){
				$$.ajax({
					type:"GET",
					url: "/Supervisor/Common/getarea_checkbox/",
					data: {city_id:$$("#city_id").val()},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						$$("#area_panel").html(data);
					}
				});
			}else{
				$$("#area_panel").html("");
			}
		});
		
		$$(".NewBtn").click(function(){  
			$$(this).parent().parent().clone(true).insertAfter($$(this).parent().parent());	  
		 }); 
		$$(".DelBtn").click(function(){
			$$(this).parent().parent().remove();	  
		 }); 
		 
		$$(".session dd input").click(function(){
			var week = $$(".session dd input").index(this)
			var check = $$(this).attr("checked");
			
			if(check!=true){
				$$(".cycle tr").eq(week).hide();
			}else{
				$$(".cycle tr").eq(week).show();
			}
				
		 }); 
});
