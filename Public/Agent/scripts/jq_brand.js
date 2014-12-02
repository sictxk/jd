$$(function(){
	$$("#brand_id").change(function() {
		var brand_id = $$("#brand_id").val();
		if(brand_id!=''){
			$$.ajax({
				type:"GET",
				url: "/Supervisor/Kechengbao/getAgencyCheckbox/",
				data: {brand_id:$$("#brand_id").val()},
				timeout: 3000,
				error: function() {
					alert("error!");
				},
				success: function(data) {
					$$("#agency_panel").html(data);
				}
			});
		}else{
			$$("#agency_panel").html("");
		}
	});
});