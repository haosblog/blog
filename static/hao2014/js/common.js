//全局变量
var ajaxStop = true;		//ajax请求是否完成。用于判断是否展示遮罩层
var loading = null;			//加载中的遮罩层对象
var navDomValue = [];		//导航条每个元素的参数
var navClickBind = false;	// 导航栏是否被绑定click事件。用于响应式视图中手机版导航栏的滑出滑入事件绑定

//点击前进后退按钮时的操作
window.onpopstate = function(event){
	if(event && event.state){
		document.title = event.state.title;
		$("#mainBox").fadeTo(500, 0, function(){
			$("#mainBox").fadeTo(500, 1);
			$("#content").html(event.state.html);
		});
	}
}
// 页面加载完成，初始化页面
$(document).ready(function() {
	createClock();			// 生成时钟
	loopInit();				// 初始化滚动
	navInit();				// 初始化导航条
	loadingInit();			// 生成loading图标遮罩层

	// 注册各种必须的页面事件
	regEvent.index_msgbox();	// 注册
	regEvent.linkClick();		// 注册链接点击事件，实现ajax加载
	regEvent.navbar();		// 注册响应式中navbar的点击事件
	regEvent.form();		// 注册表单提交事件
});

/**
 * 初始化生成页面loading图标的遮罩层
 * @returns {undefined}
 */
function loadingInit(){
	loading = $("<div><img src='/static/hao2014/image/loading.gif' alt='加载中...' /></div>");
	loading.css({"background" : "rgba(0, 0, 0, 0.6)", "width" : "100%", "height" : $(document).height(), "display" : "none", "text-align" : "center"});
	loading.children("img").css("margin-top", ($(document).height() - 32) / 2);
	$("body").append(loading);
}

/**
 * 初始化导航栏
 *
 * @returns {Boolean}
 */
function navInit(){
	if(screen.width < 800){// 分辨率宽度大于800的时候才执行导航栏初始化
		return false;
	}

	//初始化导航条参数
	var navImgPath = "/static/hao2014/image/navbar/";
	$("nav img").each(function(){
		//预加载图像
		new Image().src = navImgPath + $(this).attr("_src")+ "_on.png";
		var temjson = {
			"width" : 80,
			"height" : 80,
			"oldwidth" : 80,
			"oldheight" : 80,
			"imgold" : navImgPath + $(this).attr("_src") + "_on.png",
			"imgon" : navImgPath + $(this).attr("_src") + ".png",
			"left" : $(this).offset().left -  $("nav").offset().left
		}
		$(this).attr("src", temjson.imgold);
		navDomValue.push(temjson);
	});
	//导航鼠标移过
	$("nav").mouseover(navDock);
	$("nav").mousemove(navDock);
	$("nav").mouseout(navDockReset);
}

//导航栏Dock样式
function navDock(e) {
	if($(window).width() < 800){// 窗口宽度小于800则不执行
		return false;
	}
	var topWidth = 0;
	for(var i = 0; i < navDomValue.length; i++){
		navDomValue[i].left = $("nav img:eq(" + i + ")").offset().left;
		var point = Math.abs(navDomValue[i].left + (navDomValue[i].width / 2) - e.pageX);
		if(point < 160){
			navDomValue[i].width = navDomValue[i].oldwidth * (-0.125 * point + 125) / 100;
			navDomValue[i].height = navDomValue[i].oldheight * (-0.125 * point + 125) / 100;
			$("nav img:eq(" + i + ")").attr("src", navDomValue[i].imgon);
		} else {
			navDomValue[i].width = navDomValue[i].oldwidth;
			navDomValue[i].height = navDomValue[i].oldheight;
			$("nav img:eq(" + i + ")").attr("src", navDomValue[i].imgold);
		}
		$("nav img:eq(" + i + ")").width(navDomValue[i].width);
		$("nav img:eq(" + i + ")").height(navDomValue[i].height);
		topWidth += navDomValue[i].width;
	}
	$("nav").width(topWidth + 2);
}

function navDockReset(){
	if($(window).width() < 800){// 窗口宽度小于800则不执行
		return false;
	}
	for(var i = 0; i < navDomValue.length; i++){
		navDomValue[i].width = navDomValue[i].oldwidth;
		navDomValue[i].height = navDomValue[i].oldheight;
		$("nav img:eq(" + i + ")").width(navDomValue[i].oldwidth);
		$("nav img:eq(" + i + ")").height(navDomValue[i].oldheight);
		navDomValue[i].left = $("nav img:eq(" + i + ")").offset().left;
		$("nav img:eq(" + i + ")").attr("src", navDomValue[i].imgold);
	}
	// 移除style属性清除由JQ产生的样式
	$("nav").removeAttr("style");
}

//设置滚动
function loopInit(){
	$(".loop").each(function(){
		loopStart($(this));
	});
}

function loopStart(loop){
	var loopbox = loop.find(".loopbox");
	var childWidth = loopbox.children("*").width() + parseInt(loopbox.children("*").css("margin-left"));
	var boxWidth = loopbox.children("*").length * childWidth;

	loopbox.width(boxWidth);
	//开始滚动
	var speed = loop.attr("_speed") ? loop.attr("_speed") : 3000;
	loopbox.animate({"marginLeft" : -childWidth}, speed, function(){
		var stop = loop.attr("_stop") ? loop.attr("_stop") : 0;
		//删除第一个子元素并放置最后
		var last = loopbox.children("*:first");
		last.remove();
		loopbox.children("*:last").after(last);
		loopbox.css("margin-left" , "0");

		setTimeout(function(){ loopStart(loop); }, stop);
	});
}

function linkClick(){
	if($(this).attr("target") == "_blank"){
		return true;
	}

	var url = $(this).attr("href");
	ajaxPage(url);

	return false;
}

function ajaxPage(url){
	ajaxStop = false;		//ajax开始时，ajaxStop设置为false
	var data = arguments[1] ? arguments[1] : {};

	$("#mainBox").fadeTo(1000, 0, function(){
		if(!ajaxStop){
			loading.show();
		}
	});
	$.ajax({
		"url" : url ,
		"data" : data,
		"dataType" : "xml",
		"success" : function(xml){
			ajaxStop = true;
			//修正内容，将网页标题修改为新的
			var title = document.title = $(xml).find("title").text() + " - 小皓的blog";
			var cotent = $(xml).find("body").text();
			$("#content").html(cotent);

			history.pushState({"title" : title, "html" : cotent}, "", url);
			loading.hide();
			$("#mainBox").stop().fadeTo(1000, 1);
			regEvent.index_msgbox();
			regEvent.form();		// 注册表单提交事件
		},
		"error" : function (){

		}
	});
}


// 事件注册，主要用于需要在页面更新后需要重新加载的事件
var regEvent = {
	linkClick : function(){
		$(document).on("click", "a", linkClick);
	},
	navbar : function(){
		$("#pagetitle>button").click(function(){
			$("nav").show();
			$("nav>ul").animate({ "width" : "200px"}, 500);

			if(!navClickBind){
				navClickBind = true;
				$("nav, nav a").click(function(){
					if(screen.width >= 800){
						return false;
					}
					$("nav>ul").animate({ "width" : "0"}, 500, function(){
						// 删除由JQ生成的内嵌样式，恢复到初始状态（避免响应式出问题）
						$("nav, nav>ul").removeAttr("style");
					});
				});
			}
		});
	},
	form : function (){// 表单提交事件监听
		$("form").unbind("submit").submit(function(){
			var method = $(this).attr("method");
			var url = $(this).attr("action");
			var data = $(this).serialize();

			if(method.toLowerCase() == "get"){// get方式提交的页面，直接转到对应页
				url += (url.indexOf("?") === -1 ? "?" : "&") + data;
				ajaxPage(url);
			} else {
				loading.show();
				$.ajax({
					"url" : url ,
					"data" : data,
					"type" : "post",
					"dataType" : "json",
					"success" : function(data){
						alert(data.msg);
						loading.hide();
						ajaxPage(location.href);
					},
					"error" : function (){
						alert("系统错误，请稍后再试");
					}
				});
			}

			return false;
		});
	},
	index_msgbox : function(){
		var lineCount = 4;
		for(var i = 0; i < $("#msgbox .msgmain").length; i++){
			var num = i < lineCount ? i : i % lineCount;
			if(num < (lineCount / 2)){
				var thisLeft = (num + 1) * 100 + 10;
				$("#msgbox .msgmain:eq(" + i + ")").css("left", thisLeft);
			} else {
				var thisRight = (lineCount - num) * 100 + 10;
				$("#msgbox .msgmain:eq(" + i + ")").css("right", thisRight);
			}
		}
		$("#msgbox .msgface").hover(function(){
			$(this).parent().children(".msgmain").animate({"width" : "200px"}, 500);
		},function(){
			$(this).parent().children(".msgmain").animate({"width" : "0"}, 500);
		});
	}
}



//###########################################时钟###########################################
//时钟创建函数
var point = new Array(15);
function createClock(){
	for(var i = 1; i < 13; i++){
		var clockDiv = document.createElement("p");
		var text = document.createTextNode(i);
		clockDiv.appendChild(text);
		document.getElementById("clock").appendChild(clockDiv);
		clockDiv.style.left = (85 + Math.cos((3 - i) * 30 * 0.017453293) * 78) + "px";
		clockDiv.style.top = (85 - Math.sin((3 - i) * 30 * 0.017453293) * 78) + "px";
	}
	for(var i = 0; i < 15; i++){
		var clockPoint = document.createElement("div");
		point[i] = clockPoint;
		var txt = document.createTextNode("·");
		clockPoint.appendChild(txt);
		document.getElementById("clock").appendChild(clockPoint);
	}

	//调用运行函数
	clockRun();
}
//时钟运行函数
function clockRun(){
	var nowTime = new Date();
	var secondV = (15 - nowTime.getSeconds()) * 6;
	var minutV = (15 - nowTime.getMinutes()) * 6;
	var hourV = (3 - nowTime.getHours()) * 30 + (3 - nowTime.getMinutes()) * 0.5;
	for(var i = 1; i < 6; i++){
		point[i].style.left = (85 + Math.cos(secondV * 0.017453293) * i * 10) + "px";
		point[i].style.top = (80 - Math.sin(secondV * 0.017453293) * i * 10) + "px";
	}
	for(var i = 6; i < 11; i++){
		point[i].style.left = (85 + Math.cos(minutV * 0.017453293) * (i - 5) * 9) + "px";
		point[i].style.top = (80 - Math.sin(minutV * 0.017453293) * (i - 5) * 9) + "px";
	}
	for(var i = 11; i < 14; i++){
		point[i].style.left = (85 + Math.cos(hourV * 0.017453293) * (i - 10) * 9) + "px";
		point[i].style.top = (80 - Math.sin(hourV * 0.017453293) * (i - 10) * 9) + "px";
	}
	setTimeout(function(){clockRun();},1000);
}


//调整图片大小
function ImgAutoSize(imgD,FitWidth,FitHeight) {
 var image1=new Image();
 image1.onload = function () {
  if(this.width>0 && this.height>0) {
   if(this.width/this.height>= FitWidth/FitHeight) {
    if(this.width>FitWidth) {
     imgD.width=FitWidth;
     imgD.height=(this.height*FitWidth)/this.width;
    } else {
     imgD.width=this.width;
     imgD.height=this.height;
    }
   } else {
    if(this.height>FitHeight) {
     imgD.height=FitHeight;
     imgD.width=(this.width*FitHeight)/this.height;
    } else {
     imgD.width=this.width;
     imgD.height=this.height;
    }
   }
  }
  image1 = null;
 }
 image1.src=imgD.src;
}