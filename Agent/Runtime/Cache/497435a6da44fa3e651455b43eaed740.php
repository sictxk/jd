<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>复爵教育后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/editor.css" />
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-core-1.4.5.js"></script>
	<script type="text/javascript" src="/Public/Backend/scripts/mootools-more-1.4.0.1.js"></script>
	<script type="text/javascript" src="/Public/Backend/scripts/public.js"></script>
    
	<script type="text/javascript">
		window.addEvent('domready', function(){
			initTab(0);
			checkNotice();
		});
	</script>

</head>

<body>
	<div class="header">
		通知编辑&gt;&gt;<?php echo ($arr_form["title"]); ?>
	</div>
	
	<div class="container">
		<form id="MyForm1" name="MyForm1" method="post" action="/Backend/Notice/renew">
			<input name="pkid" type="hidden" value="<?php echo ($arr_form["pkid"]); ?>"/>
			<ul id="tabmenu">
				<li><a href="javascript:void(0);">基本信息</a></li>
			</ul>
			<div id="tabcontent">
				<div class="tabitem tabitemselected">
					<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
                        <tr>
                            <td align="right" width="100"><span class="need">通知标题：</span></td>
                            <td><textarea name="title" id="title" cols="50" rows="1"/><?php echo ($arr_form["title"]); ?></textarea></td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">通知内容：</span></td>
                            <td><textarea id="content" name="content" rows="12" cols="80"><?php echo ($arr_form["content"]); ?></textarea></td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">通知范围：</span></td>
                            <td>
                                <?php if(is_array($grade_list)): $key = 0; $__LIST__ = $grade_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><lable><input type="checkbox" name="grade_id[]" value="<?php echo ($vo["pkid"]); ?>" <?php echo ($vo["checked"]); ?>/><?php echo ($vo["title"]); ?></lable><?php endforeach; endif; else: echo "" ;endif; ?>
                            </td>
                        </tr>
					</table>
		    	</div>
			</div>
				<div>
				<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
					<tr>
						<td width="100" height="40">&nbsp;</td>
						<td>
                            <input type="button" id="" name="back" value="返回" onclick="javascript:location.href='/Backend/Notice/index'"/>
							<input type="submit" id="SubmitBtn" name="SubmitBtn" value="提交" />
						</td>
					</tr>
				</table>
				</div>
		</form>
	</div>
</body>
</html>