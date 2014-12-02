<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>复爵教育后台管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/layout.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Backend/css/list.css" />
	<script type="text/javascript">    
	    function DoDel(Pkid){
	    	var msg='确定要删除公告吗?';
	    	if(confirm(msg)){
	    		window.location.href="/Backend/Notice/remove/pkid/"+Pkid ;
	    	}
	    }
        function DoEdit(Pkid){
            window.location.href="/Backend/Notice/edit/pkid/"+Pkid ;
        }
	</script>
</head>

<body>
	<div class="header">
		公告列表
	</div>
	<form action="/Backend/Notice/index" method="post" class="formskin">
		<input name="mode" type="hidden" value="search">
		<div>
			<label>
				公告关键字
				<input type="text" name="content" maxlength="50" value="<?php echo ($map["content"]); ?>"/>
			</label>		
			<input type="submit" value="搜索" />
		</div>
	</form>
	<div class="container">
		<table border="1" cellpadding="5" cellspacing="0" width="100%" class="tableskin">
			<tr>
				<th width="250">标题</th>
				<th width="">公告内容</th>
                <th width="150">通知范围</th>
				<th width="100">发布时间</th>
				<<th width="50">操作</th>
			</tr>
			

			<?php if(is_array($notice_list)): $i = 0; $__LIST__ = $notice_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td><a href="javascript:DoEdit('<?php echo ($vo["pkid"]); ?>');"><?php echo ($vo["title"]); ?></a></td>
				<td><?php echo ($vo["content"]); ?></td>
                <td><?php if(is_array($vo["grade_id_list"])): $i = 0; $__LIST__ = $vo["grade_id_list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i; echo ($grade_list[$c]); ?><br><?php endforeach; endif; else: echo "" ;endif; ?></td>
				<td><?php echo ($vo["ctime"]); ?></td>
				<td align="center">
					<a href="javascript:DoDel('<?php echo ($vo["pkid"]); ?>');">删除</a>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</table>
		<div class="pagenation">
			<?php echo ($page); ?>
		</div>
	</div>
</body>
</html>