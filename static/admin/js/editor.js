$(function(){
	$("[data-role=editor-toolbar]").each(function(){
		var textName = $(this).attr("data-target").substr(1);
		var contentArea = $("textarea#" + textName);
		var obj = this;
		var $this = $(this);

		$this.find("[data-edit]").click(function(){
			var event = $(this).attr("data-edit");

			if(typeof editorFunction[event] == "function"){
				editorFunction[event].call();
			}
		});

		contentArea.keypress(function(e){
			var k = e.which || e.keyCode;

			if(e.ctrlKey){
				switch(k){// 带ctrl的事件监听
					case 119:// ctrl+w，阻止误操作导致窗口关闭
						return false;
						break;

					case 98:// ctrl+b，加粗快捷键
						editorFunction.bold();
						return false;
						break;

					case 105:// ctrl+i，倾斜快捷键
						editorFunction.italic();
						return false;
						break;

					case 117:// ctrl+u，下划线快捷键
						editorFunction.underline();
						return false;
						break;
				}
//			alert(k);
			}

			switch(k){// 普通键盘事件监听，注意，带ctrl、shift或不带都会在这里被监听
					// 如果需要同时监听一个键带ctrl等多种情况的请在本处监听，以免歧义
				case 9:// tab，阻止切换焦点事件，改为输入制表符
					var sel = contentArea.selection();
					if(sel.start === sel.end){// 未选中文本，则在光标处插入一个制表符
						sel.replace("	");
					} else {// 已选中文本，则在每行开头增加制表符
						editorFunction.outdent();
					}
					return false;
					break;

				case 116:// F5，避免在编辑中不小心点了刷新
//					return false;
					break;


				case 13:// 回车键，自动判断上一行是否存在制表符/列表标记，自动添加
					var sel = contentArea.selectionLine();
					var firstLetter = contentArea.val().substr(sel.start, 1);
					if(firstLetter === "	" || firstLetter === "#" || firstLetter === "*"){
						if(firstLetter !== "	"){ firstLetter += " "; }
						sel.selection.replace("\n" + firstLetter);
						return false;
					}
					break;
			}
		})


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
			},
			"insertunorderedlist" : function (){
				contentArea.selectionLine().replace("* ");
			},
			"insertorderedlist" : function (){
				contentArea.selectionLine().replace("# ");
			},
			"outdent" : function(){
				contentArea.selectionLine().replace("	");
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
			return contentArea.val().substr(start -1, 1) === "\n";
		}
	})


	$('[data-popover]').popover({
		"container" : "body",
		"html" : true,
		"placement" : "bottom",
		"content" : function(){ return $("[data-popover-content=" + $(this).attr("data-popover").substr(1) + "]").html(); }
	})
});