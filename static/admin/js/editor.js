$(function(){
	$("[data-role=editor-toolbar]").each(function(){
		var textName = $(this).attr("data-target").substr(1);
		var contentArea = $("textarea#" + textName);
		var obj = this;

		$(this).find("[data-edit]").click(function(){
			var event = $(this).attr("data-edit");

			if(typeof editorFunction[event] == "function"){
				editorFunction[event].call();
			}
		});
		
		contentArea.keypress(keyevent);
		
		function keyevent(e){
			var k = e.keyCode || e.which; 
			var returnValue = true;
			switch(k){
				case 9:
					contentArea.selection().replace("	");
					returnValue = false;
					break;
			}
			
			if(!returnValue){
				return false;
			}
			alert(k);
		}

		var editorFunction = {
			"bold" : function (){
				around("'''");
			},
			"italic" : function (){
				around("''");
			},
			"strikethrough" : function (){
				around("---");
			},
			"underline" : function (){
				around("___");
			}
		};

		/**
		 * 设置围绕自身的文字
		 *
		 * @param string startText	围绕选中区文本的头文本
		 * @returns {undefined}
		 */
		function around(startText){
			//如果没有传入第二个参数，则围绕文本前后一致
			var endText = arguments[1] ? arguments[1] : startText;
			var sel = contentArea.selection();

			sel.replace(startText + sel.text + endText);
		}

		/**
		 * 检测当前光标是否在行首
		 *
		 * @returns {undefined}
		 */
		function checkFirst(){
			var start = contentArea.selection().start;
			return contentArea.val().substr(start -1, 1) == "\n";
		}
	})


	$('[data-popover]').popover({
		"container" : "body",
		"html" : true,
		"placement" : "bottom",
		"content" : function(){ return $("[data-popover-content=" + $(this).attr("data-popover").substr(1) + "]").html(); }
	})
});