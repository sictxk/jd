<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>交大100代理商后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Agent/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Agent/css/list.css" />
	<script type="text/javascript">    
	    function DoLock(CardNo){
	    	var msg='确定要锁定卡号吗?';
	    	if(confirm(msg)){
	    		window.location.href="/Agent/Card/lock/CardNo/"+CardNo ;
	    	}
	    }
	</script>
</head>

<body>
	<div class="header">
		卡号列表
	</div>
	<form action="/Agent/Card/index" method="post" class="formskin">
		<input name="mode" type="hidden" value="search">
		<div>
			<label>
				卡号
				<input type="text" name="CardNo" maxlength="50" value="<?php echo ($cardNo); ?>"/>
			</label>
			
			<select id="" name="Status" onchange="" ondblclick="" class="" ><option value="" >使用状态</option><?php  foreach($Status as $key=>$val) { if(!empty($value) && ($value == $key || in_array($key,$value))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
			<select id="" name="AssignStatus" onchange="" ondblclick="" class="" ><option value="" >分配状态</option><?php  foreach($IsAssign as $key=>$val) { if(!empty($assign_value) && ($assign_value == $key || in_array($key,$assign_value))) { ?><option selected="selected" value="<?php echo $key ?>"><?php echo $val ?></option><?php }else { ?><option value="<?php echo $key ?>"><?php echo $val ?></option><?php } } ?></select>
			<input type="submit" value="搜索" />
		</div>
	</form>
	<div class="container">
		<table border="1" cellpadding="5" cellspacing="0" width="100%" class="tableskin">
			<tr>
				<th width="80">卡号</th>
				<th width="80">面值</th>
				<th width="70">卡类型</th>
				<th width="70">分配状态</th>
				<th width="70">使用状态</th>
				<th width="70">代理商</th>
				<th width="70">终端用户</th>
				<th width="70">订单号</th>
				<th width="150">生成时间</th>
				<th width="50">操作</th>
			</tr>
			
			<?php if(is_array($card_list)): $i = 0; $__LIST__ = $card_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td>
					<strong><?php echo ($vo["CardNo"]); ?></strong>
				</td>
				<td>￥<?php echo ($vo["Price"]); ?></td>
				<?php if($vo["CardType"] == 0): ?><td align="center">体验卡</td>
				<?php else: ?>
				<td align="center">--</td><?php endif; ?>
				<?php if($vo["IsAssign"] == '0'): ?><td align="center">未分配</td>
				<?php else: ?>
				<td align="center">已分配</td><?php endif; ?>
				<?php if($vo["Status"] == '0'): ?><td align="center">未使用</td>
				<?php elseif($vo["Status"] == '1'): ?>
				<td align="center">已使用</td>
				<?php else: ?>
				<td align="center">已锁定</td><?php endif; ?>
				<td align="center"><?php echo ($vo["AgentName"]); ?></td>
				<td align="center"><?php echo ($vo["StudentName"]); ?></td>
				<td align="center"><?php echo ($vo["OrderSn"]); ?></td>
				<td align="center"><?php echo ($vo["CreateTime"]); ?></td>
				<td align="center">
				<?php if($vo["Status"] != '3'): ?><a href="javascript:DoLock('<?php echo ($vo["CardNo"]); ?>');">锁定</a>
				<?php else: ?>
					--<?php endif; ?>	
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
		<div class="pagenation">
			<?php echo ($page); ?>
		</div>
	</div>
</body>
</html>