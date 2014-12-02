$$(function(){
		$$("#grade_id").change(function() {
			var grade_id = $$("#grade_id").val();
			if(grade_id!=''){
				$$.ajax({
					type:"GET",
					url: "/Backend/Course/getCourseCheckbox/",
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
});