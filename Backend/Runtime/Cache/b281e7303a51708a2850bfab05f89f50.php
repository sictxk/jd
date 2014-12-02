<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>复爵教育后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/list.css" />
	<script type="text/javascript">    
	    function DoDel(Pkid){
	    	var msg='确定要删除学员吗?';
	    	if(confirm(msg)){
	    		window.location.href="/Backend/User/remove/pkid/"+Pkid ;
	    	}
	    }
	    function DoEdit(Pkid){
    		window.location.href="/Backend/User/edit/pkid/"+Pkid ;
	    }
	    function DoManage(Pkid){
    		window.location.href="/Backend/UserOrder/index/user_id/"+Pkid ;
	    }
	</script>
</head>

<body>
	<div class="header">
		学员列表
	</div>
	<form action="/Backend/User/index" method="post" class="formskin">
		<input name="mode" type="hidden" value="search">
		<div>
			<label>
				姓名
				<input type="text" name="truename" maxlength="50" value="<?php echo ($map["truename"]); ?>"/>
			</label>
			<input type="submit" value="搜索" />
		</div>
	</form>
	<div class="container">
		<table border="1" cellpadding="5" cellspacing="0" width="100%" class="tableskin">
			<tr>
				<th width="40">姓名</th>
				<th width="80">邮箱</th>
				<th width="50">手机</th>
				<th width="50">学校</th>
				<th width="10">性别</th>
				<th width="50">家长</th>
				<th width="60">注册时间</th>
				<th width="30">操作</th>
			</tr>
			
			<?php if(is_array($user_list)): $i = 0; $__LIST__ = $user_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td>
					<a href="javascript:DoEdit('<?php echo ($vo["pkid"]); ?>');"><?php echo ($vo["truename"]); ?></a>
				</td>
				<td><?php echo ($vo["email"]); ?></td>
				<td><?php echo ($vo["mobile"]); ?></td>
				<td><?php echo ($vo["school"]); ?></td>
				<td><?php if($vo["gender"] == 'M'): ?>男<?php else: ?>女<?php endif; ?></td>
				<td><?php echo ($vo["parents"]); ?></td>
				<td><?php echo ($vo["ctime"]); ?></td>
				<td align="center">
					<a href="javascript:DoManage('<?php echo ($vo["pkid"]); ?>');">课程管理</a>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
		<div class="pagenation">
			<?php echo ($page); ?>
		</div>
	</div>
</body>
</html>