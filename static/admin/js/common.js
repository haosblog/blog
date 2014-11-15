$(function(){
	// 读取cookie处理侧边栏菜单的展开情况
	$(".menu_button").each(function(){
		var id = $(this).attr("id");
		var cookie = getCookie(id);
		if(cookie == 1){
			$(this).removeClass("collapsed").parent().children("ul")
					.removeClass("collapse").addClass("in").height("auto");
		}
		
		// 监听click事件处理cookie
		$(this).click(function(){
			var cookieName = $(this).attr("id");
			if($(this).hasClass("collapsed")){// 存在class collapsed，则展开菜单
				setCookie(cookieName, 1, 365);
			} else {
				setCookie(cookieName, 0, 365);
			}
		});
	});
});