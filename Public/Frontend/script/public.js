$(document).ready(function() {
	
    /*
    * 发送短信验证码
    */
	$("#VerifyBtn").click(function() {
		
		var mobile = $("#mobile").val();
        var msg = '';
        if(mobile=='' || mobile=='请输入手机号'){
            var mobile_notice = '请输入手机号';
            msg += mobile_notice+'\n';
        }else{
            var mobileReg= /^1[0-9]\d{9}$/ ;
            if(!mobileReg.test(mobile)){
                var mobile_notice = '手机号格式不正确';
                msg += mobile_notice+'\n';
            }
        }
        if(msg!=''){
            alert(msg);
        }else{
        	
            $.ajax({
                type:"POST",
                url: "/Member/Sms/sendVerifyCode/",
                data: {mobile:mobile},
                timeout: 3000,
                error: function() {
                    //alert("error!");
                },
                success: function(data) {
                    if(data){
                        $("#resVerifyCode").val(data);
                        alert('验证码已发送至号码'+mobile+'手机，请注意查收。');return false;
                    }
                }
            });
        }
        return false;
	});
	
	$("#RegistBtn").click(function() {
	    var email = $('#email').val();
	    var password = $('#password').val();
	    var truename = $('#truename').val();
	    var mobile = $('#mobile').val();
	    var school = $('#school').val();
	    
    	    var msg = '';
		if(mobile == ''){
			msg += '手机号码不能为空！\n';
		}else{
			if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(mobile))){ 
				msg += '手机号码格式不正确！\n';
			}
		}
		if(email != ''){
			/*msg += '注册邮箱不能为空！\n';
		}else{*/
			var format = /^(?:[a-z\d]+[_\-\+\.]?)*[a-z\d]+@(?:([a-z\d]+\-?)*[a-z\d]+\.)+([a-z]{2,})+$/i;
		    if(! format.test( email ) ){
		        msg += '邮箱地址格式不正确！\n';
		    }
		}
		if(truename == ''){
			msg += '学生姓名不能为空！\n';
		}
		if(msg != ''){
			alert(msg);
		}else{
			$("#registerForm").submit();
		}
	});
	

	
	$("#login_email").blur(function() {
	    var login_email = $('#login_email').val();
    	var msg = '';
		if(login_email == ''){
			msg += '邮箱地址不能为空！\n';
		}else{
			var format = /^(?:[a-z\d]+[_\-\+\.]?)*[a-z\d]+@(?:([a-z\d]+\-?)*[a-z\d]+\.)+([a-z]{2,})+$/i;
		    if(! format.test( login_email ) ){
		        msg += '邮箱地址格式不正确！\n';
		    }
		}
		if(msg==''){
			$("#emailImg").show();
			$("#emailNotice").hide();
		}else{
			$("#emailNotice").html(msg);
			$("#emailNotice").show();
			$("#emailImg").hide();
		}
	});

	$("#login_pass").blur(function() {
	    var login_pass = $('#login_pass').val();
    	var msg = '';
		if(login_pass == ''){
			msg += '密码不能为空！\n';
		}
		if(msg==''){
			$("#passImg").show();
			$("#passNotice").hide();
		}else{
			$("#passNotice").html(msg);
			$("#passNotice").show();
			$("#passImg").hide();
		}
	});
	$("#login_pass2").blur(function() {
		var login_pass = $('#login_pass').val();
	    var login_pass2 = $('#login_pass2').val();
    	var msg = '';
		if(login_pass2 == ''){
			msg += '确认密码不能为空！\n';
		}else{
			if(login_pass!=login_pass2){
				msg += '两次密码输入不一致！\n';
			}
		}
		if(msg==''){
			$("#pass2Img").show();
			$("#pass2Notice").hide();
		}else{
			$("#pass2Notice").html(msg);
			$("#pass2Notice").show();
			$("#pass2Img").hide();
		}
	});
	
	$("#mobile").blur(function() {
	    var mobile = $('#mobile').val();
    	var msg = '';
		if(mobile == ''){
			msg += '手机号码不能为空！\n';
		}else{
			if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(mobile))){ 
				msg += '手机号码格式不正确！\n';
			}
		}
		if(msg==''){
			$("#mobileImg").show();
			$("#mobileNotice").hide();
		}else{
			$("#mobileNotice").html(msg);
			$("#mobileNotice").show();
			$("#mobileImg").hide();
		}
	});
	

	
	$("#LoginBtn").click(function() {

	    var username = $('#username').val();
	    var password = $('#password').val();

    	var msg = '';

		if(username == '' || username=='账号/手机号/邮箱'){
			msg += '账号不能为空！\n';
		}
		if(password == '' || password=='******'){
			msg += '密码不能为空！\n';
		}
		if(msg != ''){
			alert(msg);
		}else{
			$("#LoginForm").submit();
		}
	});
	
	$("input[name='method']").click(function() {
		var method = $('input[name="method"]:checked').val();
		if(method=='email'){
			$("#emailBox").show();
			$("#mobileBox").hide();
		}else{
			$("#mobileBox").show();
			$("#emailBox").hide();
			//alert('暂不支持手机找回密码');
		}
	});
	
	$("#ForgetBtn").click(function() {

	    var method = $('input[name="method"]:checked').val();
	    var mobile = $('#mobile').val();
		var email = $('#email').val();
    	var msg = '';
	    if(method !='email' && method !='mobile'){
	    	msg += '请选择密码找回方式！\n';
	    }else{
			if(method == 'mobile' && mobile == ''){
				msg += '请填写手机号！\n';
			}
			if(method == 'email' && email == ''){
				msg += '请填写邮箱地址！\n';
			}
		}
		
		if(msg != ''){
			alert(msg);
		}else{
			$("#ForgetForm").submit();
		}
	});
	
	
	$("#topSearch").click(function() {

	    var tabType = $('#tabType').val();
    	var msg = '';

		if(tabType == 'agency'){
			var agency_title = $('#agency_title').val();
			if(agency_title=='' || agency_title=='请输入商家机构名称'){
				msg += '请输入商家机构名称！\n';
			}
			$('#course_title').val('');
		}else{
			var course_title = $('#course_title').val();
			if(course_title=='' || course_title=='请输入课程科目名称'){
				msg += '请输入课程科目名称！\n';
			}
			$('#agency_title').val('');
		}
		if(msg != ''){
			alert(msg);
		}else{
			document.topForm.submit();
		}
	});
	
	
	$("#agencyTab").click(function() {
		$(this).addClass("ok");
		$("#courseTab").removeClass("ok");
		$("#agency_title").val('请输入商家机构名称');
		$("#course_title").val('');
		$("#agency_title").show();
		$("#course_title").hide();
		$("#tabType").val('agency');
	});
	
	
	$("#courseTab").click(function() {
		$(this).addClass("ok");
		$("#agencyTab").removeClass("ok");
		$("#agency_title").hide();
		$("#agency_title").val('');
		$("#course_title").val('请输入课程科目名称');
		$("#course_title").show();
		$("#tabType").val('course');
	});
	
	$("#ReviewBtn").click(function() {

	    var comment = $('#comment').val();
	    var rating = $('#rating').val();
    	var msg = '';

		if(comment == '' || comment == '请输入您对老师的点评或留言'){
			msg += '请输入留言内容！\n';
		}
		if(rating == ''){
			msg += '请点击星星评分！\n';
		}
		if(msg != ''){
			alert(msg);
		}else{
			//document.ReviewForm.submit();
			$("#ReviewForm").submit();
		}
	});
	
	$("#CommentBtn").click(function() {
	    var user_id = $('#user_id').val();
		if(user_id == ''){
			msg =  '请登录后发表评论！\n';
			if(confirm(msg)){
				window.location.href='/Member/User/login';
			}
			return false;
		}
		
		var comment = $('#comment').val();
		var msg = ''; 
		if(comment == '' || comment=='写下你的感受，分享给更多有需要的人'){
			msg += '请写下你的评价内容！\n';
		}
		if(msg != ''){
			alert(msg);return false;
		}else{
			$("#CommentForm").submit();
		}
	});
	
	$("#CancelBtn").click(function() {

	    var comment = $('#cancel_reason').val();
    	var msg = '';
		if(comment == ''){
			msg += '请输入您撤销预定的原因\n';
		}
		if(msg != ''){
			alert(msg);
		}else{
			document.CancelForm.submit();
		}
	});
	
	$("#ConfirmBtn").click(function() {
		var msg = '请确认在授课完成后点击，确定吗？';
		var oid = $(this).attr('alt');
		if(confirm(msg)){
			//document.ConfirmForm.submit();
			window.location.href= '/Member/Order/teaching_confirm/oid/'+oid;
		}else{
			return false;
		}
	});
	

	// 为 SELECT 添加事件
	$("#grade_id").change(function() {
		var grade_id = $("#grade_id").val();
		if(grade_id!=''){
			$.ajax({
				type:"GET",
				url: "/Member/Paper/getcourse/",
				data: {grade_id:$("#grade_id").val()},
				timeout: 3000,
				error: function() {
					alert("error!");
				},
				success: function(data) {
					// 回调函数，将返回的数据添加到 P 标签中
					$("#source_panel").html(data);
				}
			});
		}else{
			$("#source_panel").html("");
		}
	});
	
	
	
	
});


