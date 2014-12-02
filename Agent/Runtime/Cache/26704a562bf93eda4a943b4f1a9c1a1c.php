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
    <script type="text/javascript" src="/Public/Backend/scripts/jquery-1.4.2.js"></script>
    <script type="text/javascript">var $$=jQuery.noConflict();</script>
    <script type="text/javascript" src="/Public/Backend/scripts/jq_order.js"></script>
	<script type="text/javascript">
		window.addEvent('domready', function(){
			initTab(0);
			checkOrder();
		});
	</script>
</head>

<body>
	<div class="header">
		<?php echo ($data_user["truename"]); ?>&gt;&gt;开通课程
	</div>
	
	<div class="container">
		<form id="MyForm1" name="MyForm1" method="post" action="/Backend/UserOrder/save" >
            <input name="user_id" id="user_id" type="hidden" value="<?php echo ($user_id); ?>"/>
			<ul id="tabmenu">
				<li><a href="javascript:void(0);">科目设定</a></li>
			</ul>
			<div id="tabcontent">
				<div class="tabitem tabitemselected">
					<table border="0" cellpadding="5" cellspacing="0" class="tableform" width="100%">
						<tr>
							<td align="right" width="100"><span class="need">年级科目：</span></td>
							<td>
                                <select id="grade_id" name="grade_id" onchange="" ondblclick="" class="" ><option value="" >选择年级</option><?php  foreach($grade_id as $key=>$val) { if(!empty($grade_id) && ($grade_id == $key || in_array($key,$grade_id))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
                                <span id="course_panel"></span></td>
						</tr>
                        <tr>
                            <td align="right" width="100"><span class="need">费用金额：</span></td>
                            <td>
                                <input name="pay_amount" id="pay_amount" selected="<?php echo ($arr_form["pay_amount"]); ?>" />元
                                </td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">支付日期：</span></td>
                            <td>
                                <input name="pay_date" id="pay_date" type="date" selected="<?php echo ($arr_form["pay_date"]); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">课程开始：</span></td>
                            <td>
                                <input name="start_date" id="start_date" type="date" selected="<?php echo ($arr_form["start_date"]); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" width="100"><span class="need">截止日期：</span></td>
                            <td>
                                <input name="expire_date" id="expire_date" type="date" selected="<?php echo ($arr_form["expire_date"]); ?>" />
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
                            <input type="button" id="back" name="back" value="返回" onclick="javascript:history.back();"/>
							<input type="button" id="SubmitBtn" name="SubmitBtn" value="提交" />
						</td>
					</tr>
				</table>
				</div>
		</form>
	</div>
</body>
</html>