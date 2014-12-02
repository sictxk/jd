{
	function initTab(Num) {
		var tab = $('tabmenu').getElements('li');
		var item = $('tabcontent').getElements('div.tabitem');
		tab[Num].addClass('selected');
		item[Num].addClass('tabitemselected');
		tab.each(function(el, index){
			el.addEvent('click', function(e){
				tab.removeClass('selected');
				item.removeClass('tabitemselected');
				el.addClass('selected');
				if (item[index]) {
					item[index].addClass('tabitemselected');
				}
			});
		});
	}
	 
	function checkAssign(){
		var subBtn = $('SubmitBtn');
	     var agentSelect = $('agentId');
		var quantityInput = $('quantity');
		
		
	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var agentId = agentSelect.value;
		var quantity = quantityInput.value;
	    	var msg = '';
			if(agentId == ''){
				msg += '代理商不能为空\n';
			}
			if(quantity == ''){
				msg += '分配数量不能为空\n';
			}
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkAgencyCategory(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');

	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;

	    	var msg = '';
			if(title == ''){
				msg += '标题不能为空\n';
			}
			
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkAgencyBrand(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');

	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;

	    	var msg = '';
			if(title == ''){
				msg += '标题不能为空\n';
			}
			
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkAgency(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');

	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;

	    	var msg = '';
			if(title == ''){
				msg += '机构名称不能为空\n';
			}
			
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkOrder(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('pay_amount');

	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var pay_amount = titleInput.value;

	    	var msg = '';
			if(pay_amount == ''){
				msg += '金额不能为空\n';
			}
			
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkCourse(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');
	    var cateInput = $('grade_id');
	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;
	    	var grade_id = cateInput.value;
	    	var msg = '';
			if(title == ''){
				msg += '科目名称不能为空\n';
			}
			if(grade_id == ''){
				msg += '所属年级不能为空';
			}
			
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkArticle(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');

	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;
	    	var msg = '';
			if(title == ''){
				msg += '文章标题不能为空\n';
			}
			
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkAdver(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');

	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;

	    	var msg = '';
			if(title == ''){
				msg += '视频标题不能为空\n';
			}

			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkLink(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');
		var urlInput = $('url');
	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;
			var url = urlInput.value;
	    	var msg = '';
			if(title == ''){
				msg += '链接标题不能为空\n';
			}
			if(url == ''){
				msg += '链接URL不能为空\n';
			}
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkUser(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('truename');
	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var truename = titleInput.value;
	    	var msg = '';
			if(truename == ''){
				msg += '学生姓名不能为空\n';
			}
			
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkDocCategory(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');

	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;

	    	var msg = '';
			if(title == ''){
				msg += '标题不能为空\n';
			}
			
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkDocument(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');
		var categorySel = $('category_id');
	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;
			var category_id = categorySel.value;
	    	var msg = '';
			if(title == ''){
				msg += '资料标题不能为空\n';
			}
			if(category_id == ''){
				msg += '资料分类不能为空\n';
			}
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkNotice(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');
		var contentInput = $('content');
	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;
			var content = contentInput.value;
	    	var msg = '';
			if(title == ''){
				msg += '通知标题不能为空\n';
			}
			if(content == ''){
				msg += '通知内容不能为空\n';
			}
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkPaper(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');
		var gradeSel = $('grade_id');
	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;
			var grade_id = gradeSel.value;
	    	var msg = '';
			if(title == ''){
				msg += '试卷标题不能为空\n';
			}
			if(grade_id == ''){
				msg += '试卷年级不能为空\n';
			}
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}

  function checkRating(){
    var subBtn = $('SubmitBtn');
    var ratingSel = $('rating_type');
    subBtn.addEvent('click', function(e){
        e.stop();
        var rating_type = ratingSel.value;
        var msg = '';
        if(rating_type == ''){
            msg += '评级类型不能为空\n';
        }
        if(msg != ''){
            alert(msg);
        }else{
            document.MyForm1.submit();
        }
    });
}

	function checkLesson(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');
		var lectuerInput = $('lectuer');
	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;
			var lectuer = lectuerInput.value;
	    	var msg = '';
			if(title == ''){
				msg += '课程标题不能为空\n';
			}
			if(lectuer == ''){
				msg += '课程讲师不能为空\n';
			}
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function checkLessonVideo(){
		var subBtn = $('SubmitBtn');
	    var titleInput = $('title');
		var pathInput = $('video_path');
	    subBtn.addEvent('click', function(e){
	    	e.stop();
	    	var title = titleInput.value;
			var video_path = pathInput.value;
	    	var msg = '';
			if(title == ''){
				msg += '章节标题不能为空\n';
			}
			if(video_path == ''){
				msg += '视频路径不能为空\n';
			}
			if(msg != ''){
				alert(msg);
			}else{
				document.MyForm1.submit();
			}
	    });
	}
	
	function configContents(e, bol) {
		var oEditor = CKEDITOR.instances[e];
		if (oEditor != null) {
			if (bol) {
				return(encodeURIComponent(oEditor.getData()));
			} else {
				oEditor.setData(null);
			}
		} else {
			return '';
		}
	}
	function SetFileField(fileUrl, data) {
		$(data['selectActionData']).value = fileUrl;
		$(this).setProperty('src', fileUrl);
	}
	function BrowseServer(startupPath, functionData, element) {
		var finder = new CKFinder();
		finder.startupPath = startupPath;
		finder.selectActionData = functionData;
		finder.selectActionFunction = SetFileField.bind(element);
		finder.popup();
	}
}