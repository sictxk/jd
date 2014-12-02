<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>复爵教育后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/list.css" />
	<script type="text/javascript">    
	    function DoDel(Pkid){
	    	var msg='确定要删除订单吗?';
	    	if(confirm(msg)){
	    		window.location.href="/Backend/UserOrder/remove/order_id/"+Pkid ;
	    	}
	    }

	    function DoEdit(Pkid){
    		window.location.href="/Backend/UserOrder/edit/order_id/"+Pkid ;
	    }
	    
	</script>
</head>

<body>
	<div class="header">
		<?php echo ($data_user["truename"]); ?>&gt;&gt;订单列表
        <input type="button"  name="" value="返回" onclick="javascript:history.back();"/>
        <input type="button"  name="SubmitBtn" value="新增课程" onclick="javascript:location.href='/Backend/UserOrder/add/user_id/<?php echo ($data_user["pkid"]); ?>'"/>
	</div>
	<!--<form action="/Backend/UserOrder/index" method="post" class="formskin">
		<input name="mode" type="hidden" value="search">
		<div>
			<label>
				订单号
				<input type="text" name="order_sn" maxlength="30" value="<?php echo ($map["order_sn"]); ?>"/>
			</label>
			<label>
				学生姓名
				<input type="text" name="student_name" maxlength="30" value="<?php echo ($map["student_name"]); ?>"/>
			</label>
			<label>
				学生手机
				<input type="text" name="student_mobile" maxlength="30" value="<?php echo ($map["student_mobile"]); ?>"/>
			</label>
			<label>
				备注
				<input type="text" name="mark" maxlength="30" value="<?php echo ($map["mark"]); ?>"/>
			</label>
			
			<select id="" name="order_status" onchange="" ondblclick="" class="" ><option value="" >订单状态</option><?php  foreach($order_status as $key=>$val) { if(!empty($value) && ($value == $key || in_array($key,$value))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
			<input type="submit" value="搜索" />
		</div>
	</form>-->
	<div class="container">
		<table border="1" cellpadding="5" cellspacing="0" width="100%" class="tableskin">
			<tr>
				<th width="110">订单编号</th>
				<th width="90">订单时间</th>
				<th width="90">年级信息</th>
				<th width="50">付款科目</th>
				<th width="60">付款金额</th>
                <th width="60">付款日期</th>
                <th width="60">课程开始</th>
                <th width="60">截止日期</th>
				<!--<th width="50">操作</th>-->
			</tr>
			
			<?php if(is_array($order_list)): $i = 0; $__LIST__ = $order_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td>
                    <a href="javascript:DoEdit('<?php echo ($vo["pkid"]); ?>');"><?php echo ($vo["order_sn"]); ?></a>
				</td>
				<td><?php echo ($vo["ctime"]); ?></td>
				<td><?php echo ($vo["grade_title"]); ?></td>
				<td><?php if(is_array($vo['course_list'])): $i = 0; $__LIST__ = $vo['course_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i; echo ($c["title"]); ?><br><?php endforeach; endif; else: echo "" ;endif; ?></td>
				<td>￥<?php echo ($vo["pay_amount"]); ?></td>
				<td><?php echo ($vo["pay_date"]); ?></td>
				<td><?php echo ($vo["start_date"]); ?></td>
                <td><?php echo ($vo["expire_date"]); ?></td>
				<!--<td align="center">
					<a href="javascript:DoEdit('<?php echo ($vo["pkid"]); ?>');">详情</a>
				</td>-->
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
		<div class="pagenation">
			<?php echo ($page); ?>
		</div>
	</div>
</body>
</html>