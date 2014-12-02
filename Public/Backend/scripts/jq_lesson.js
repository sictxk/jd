$$(function(){
		$$("#lesson_id").change(function() {
			var lesson_id = $$("#lesson_id").val();
			if(lesson_id!=''){
				$$.ajax({
					type:"GET",
					url: "/Backend/Common/chapter_select/",
					data: {lesson_id:$$("#lesson_id").val()},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						$$("#chapter_panel").html(data);
					}
				});
			}else{
				$$("#chapter_panel").html("");
			}
		});
		
		$$("#grade_id").change(function() {
			var grade_id = $$("#grade_id").val();
			if(grade_id!=''){
				$$.ajax({
					type:"GET",
					url: "/Backend/Course/getCourse/",
					data: {grade_id:$$("#grade_id").val()},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						$$("#course_panel").html(data);
					}
				});
			}else{
				$$("#second_panel").html("");
			}
			$$("#third_panel").html("");
		});
		
		$$("#second_id").live('change',function() {
			var second_id = $$("#second_id").val();
			if(second_id!=''){
				$$.ajax({
					type:"GET",
					url: "/Backend/AgencyCategory/getThird",
					data: {second_id:$$("#second_id").val()},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						$$("#third_panel").html(data);
					}
				});
			}else{
				$$("#third_panel").html("");
			}
		});
		
		$$("#third_id").live('change',function() {
			var third_id = $$("#third_id").val();
			var course_panel = $$("#course_panel");
			if(third_id!='' && third_panel){
				$$.ajax({
					type:"GET",
					url: "/Backend/AgencyCategory/getCourse",
					data: {third_id:$$("#third_id").val()},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						$$("#course_panel").html(data);
					}
				});
			}else{
				$$("#course_panel").html("");
			}
		});
		
		
		$$("#bindCourseBtn").click(function() {
			var pno = $$("#pno").val();
			var agency_id = $$("#pkid").val();
			var course_id = $$("#course_id").val();
			if(course_id!=''){
				window.location.href='/Backend/Agency/bindCourse/agency_id/'+agency_id+'/course_id/'+course_id+'/pno/'+pno;
				/*$$.ajax({
					type:"GET",
					url: "/Backend/Agency/bindCourse",
					data: {agency_id:agency_id,course_id:course_id},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						$$("#Step2Tbody").html(data);
					}
				});*/
			}else{
				//$$("#course_panel").html("");
				alert('ÇëÑ¡Ôñ¿ÆÄ¿');return false;
			}
		});
		
		$$("#Step2Tbody .second_cat").live('change',function() {
			var second_id = $$(this).val();
			var Index = $$("#Step2Tbody .sceond_cat").index(this);
			var id = $$(this).attr("id");
			//alert(second_id);
			//alert(Index);
			$$(this).parent().next().children(".course_span").html("");
			if(second_id!=''){
				$$.ajax({
					type:"GET",
					url: "/Backend/Agency/getCourse",
					data: {second_id:second_id},
					timeout: 3000,
					error: function() {
						alert("error!");
					},
					success: function(data) {
						//alert(data);
					    $$("#course_cat_"+id).html(data);
						//$$(this).parent().next().children(".course_span").html("aaaa");
						//$$(this).parent().next().html("");
					}
				});
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
		 
		$$(".DelBtn").click(function(){
			$$(this).parent().parent().remove();	  
		 }); 
		 
		 
		 $$("input[name=promotion_type[]]").click(function(){
		   var type = $$(this).val();
		   showCont(type);
		 });
		 
});