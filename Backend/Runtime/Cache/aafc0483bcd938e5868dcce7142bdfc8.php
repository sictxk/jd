<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>复爵教育后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/general.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/frameset.css" />
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-core-1.4.5.js"></script>
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-more-1.4.0.1.js"></script>
	<script type="text/javascript">
		window.addEvent('domready', function(){
			new Fx.Accordion($('accordion'), '#accordion h2', '#accordion div', {
				onActive: function(toggler, element){
					toggler.setStyle('background-image', 'url(/Public/Backend/images/menu/minus.gif)');
				},
				onBackground: function(toggler, element){
					toggler.setStyle('background-image', 'url(/Public/Backend/images/menu/plus.gif)');
				}
			});
		});
	</script>
</head>

<body>
<div class="frame" id="topframe">
	<div>
		<span>管理员</span>
		<label>[<?php echo ($account); ?>]</label>
		<span><a href="/Backend/Index/logout" target="_top">[退出]</a></span>
	</div>
</div>
<div class="frame" id="topframeshadow"></div>
<div class="frame" id="leftframeshadow"></div>
<div class="frame" id="leftframe">
	<div id="accordion">

		<h2>科目管理</h2>
		<div>
			<p><a href="/Backend/Grade/index" target="main">年级列表</a></p>
			<!--<p><a href="/Backend/Category/add" target="main">新增分类</a></p>-->
			<p><a href="/Backend/Course/index" target="main">科目列表</a></p>
			<p><a href="/Backend/Course/add" target="main">新增科目</a></p>
		</div>
		<!--<h2>文章管理</h2>
		<div>
			<p><a href="/Backend/Article/index" target="main">文章列表</a></p>
			<p><a href="/Backend/Article/add" target="main">新增文章</a></p>
		</div>-->
		<!--<h2>广告管理</h2>
		<div>
			<p><a href="/Backend/Adver/index" target="main">广告列表</a></p>
			<p><a href="/Backend/Adver/add" target="main">新增广告</a></p>
		</div>-->
		<!--<h2>伊兔文库</h2>
		<div>
			<p><a href="/Backend/DocCategory/index" target="main">资料分类</a></p>
			<p><a href="/Backend/DocCategory/add" target="main">新增分类</a></p>
			<p><a href="/Backend/Document/index" target="main">资料列表</a></p>
			<p><a href="/Backend/Document/add" target="main">新增资料</a></p>
		</div>-->


		<h2>视频管理</h2>
		<div>
			<p><a href="/Backend/Lesson/index" target="main">教材列表</a></p>
			<p><a href="/Backend/Lesson/add" target="main">新增教材</a></p>
			<p><a href="/Backend/Chapter/index" target="main">章节列表</a></p>
			<p><a href="/Backend/Chapter/add" target="main">新增章节</a></p>
			<p><a href="/Backend/LessonVideo/index" target="main">课程列表</a></p>
			<p><a href="/Backend/LessonVideo/add" target="main">新增课程</a></p>
		</div>	

		<h2>公告管理</h2>
		<div>
            <p><a href="/Backend/Notice/index" target="main">通知列表</a></p>
			<p><a href="/Backend/Notice/add" target="main">发布通知</a></p>
		</div>
		<h2>学员管理</h2>
		<div>
			<p><a href="/Backend/User/index" target="main">学员列表</a></p>
            <p><a href="/Backend/User/add" target="main">录入学员</a></p>
		</div>


		<h2>后台账号</h2>
		<div>
			<p><a href="/Backend/Admin/index" target="main">账号列表</a></p>
			<p><a href="/Backend/Admin/add" target="main">新增账号</a></p>
		</div>
	</div>
</div>

<div class="frame" id="mainframe">
	<iframe id="main" name="main" src="<?php echo ($inner_url); ?>" scrolling="auto" frameborder="0"></iframe>
</div>

<div class="frame" id="bottomframe">
	Fujue Education Management System v1.0b.
	&copy; 2014. 复爵教育 All Rights Reserved.
</div>
</body>
</html>