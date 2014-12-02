<?php if (!defined('THINK_PATH')) exit();?><select name="chapter_id" id="chapter_id">
<option value="">选择章节</option>
<?php if(is_array($chapter_list)): $i = 0; $__LIST__ = $chapter_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["pkid"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
</select>