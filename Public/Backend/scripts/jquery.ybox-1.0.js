(function($$){  
	$$.extend($$.fn,{
		ybox:function(){
			//当窗口发生变化时候重置弹窗位置
			window.onresize=set_position;
			//function 设置弹窗位置
			function set_position(){
				//获取文档部分宽度
				var wa=document.documentElement.clientWidth;
				var wb=document.body.clientWidth;
				var mask_w=Math.max(wa,wb); 
				//获取文档部分高度
				var ha=document.documentElement.clientHeight;
				var hb=document.body.clientHeight;
				var mask_h=Math.max(ha,hb);
				//给半透明黑色蒙版赋样式值
				$$("#mask").css("width",mask_w);
				$$("#mask").css("height",mask_h);
				//计算中间弹窗左边距,上边距	
				var pop_w=$$("#pop").width();
				var pop_h=$$("#pop").height();
				pop_left=(mask_w-pop_w)/2;
				pop_top=(ha-pop_h)/2;
				//判断如果浏览器窗口被用户缩小到很小的时候确保弹窗左边距不会因为left负值而隐藏
				if(pop_left<=0){
					pop_left=0;	
				}
				//判断如果浏览器窗口被用户缩小到很小的时候确保弹窗顶部不会因为top负值而隐藏
				if(pop_top<=0){
					pop_top=0;	
				}
				//给弹窗部分赋左边距,上边距样式值
				$$("#pop").css("left",pop_left);
				$$("#pop").css("top",pop_top);	
			}
			//点击事件
        	$$(this).click(function(){
				//获取被点击的a标签的class名与指向的info div的id值
				var click_name=$$.trim($$(this).attr("class"));
				var info_name = $$.trim($$(this).attr("href")).replace("#","");
				//准备待显示的弹窗html内容
				var html="<div id='mask' style='display:none;'></div><div id='pop' style='display:none;'><div id='pop_1'><span class='pop_close'></span><div id='pop_content'><div id='"+info_name+"'>"+$$("#"+info_name+"").html()+"</div></div></div></div>";
				//将弹窗附加到body中
				$$("body").append(html);

				//定位
				set_position();

				//渐变方式载入遮罩层
				$$("#mask").fadeTo(50, 0.5);
				$$("#pop").fadeIn(50);	
				
				//关闭按钮
				$$(".pop_close").click(function(){
					$$("#mask").fadeOut(200);
					$$("#pop").fadeOut(200);
					var int=setTimeout(remove_dom,200);//待弹窗完全退出后执行清除之前附加的html的方法
				});
				
				//清除附加html方法
				function remove_dom(){
					$$("#mask").remove();
					$$("#pop").remove();	
				}
			})			
		}
	});
})(jQuery);