/**
  *		基于lagou下的 一些常用方法
  *
  *		@copyright cdv 
  *		@version 1.0
  */

jQuery.lagou = {
	/**
	  *	 lagou 信息弹框
	  *  需要加载的样式文件有: jquery-ui-1.10.3.full.min.css & ace.min.css 
	  *  需要在使用的页面创建 标签 <div id="lg-alert" class="hide" style="margin-bottom:-1.5em;"></div>
	  *	  其中 ID标签 可以根据需要改变，如果改变，则需要在第四个参数传递对应的ID
	  *	  -- 事例
	  *	  	$.lagou.alert('网络通信错误！', 0, 2);
	  *
	  * @author songmw<song_mingwei@cdv.com>
	  * @version 1.0
	  * ----------------------------------------------------------------------------------------------------------------------------
	  * @param string msg  			显示信息
	  * @param int 	code  			信息码   0红色1蓝色2绿色 默认1蓝色
	  * @param int		time				自动关闭时间  秒 默认为关闭
	  * @param string elementId	信息框的ID
	  */
	alert : function(msg, code, closeTime, elementId)
	{
		if(code == undefined)
		{
			 code = 1;
		}
		
		if(closeTime == undefined)
		{
			closeTime = false;
		}
		
		if(elementId == undefined)
		{
			elementId = "lg-alert";
		}

		var obj = $('#'+elementId);
		var alertHtml = buttonHtml = '';
		
		// 判断信息码
		switch(code)
		{
			case 0 :
				alertHtml = 'alert-error';
				buttonHtml = 'btn-danger';
				iconHtml = '<i class="icon-warning-sign red"></i>';
				break;
			case 1 :
				alertHtml = 'alert-info';
				buttonHtml = 'btn-info';
				iconHtml = '<i class="icon-bullhorn"></i>';
				break;
			case 2 :
				alertHtml = 'alert-success';
				buttonHtml = 'btn-success';
				iconHtml = '<i class="alert-success icon-ok" style="background:none;"></i>';
				break;
		}
		
		msg = '<div class="alert '+alertHtml+'" style="margin-top:0.5em;">' + msg + '</div>';
		obj.html(msg);
		
		// 为jq-ui dialog追加一个title_html 配置
		$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
			_title: function(title) {
				var $title = this.options.title || '&nbsp;'
				if( ("title_html" in this.options) && this.options.title_html == true )
					title.html($title);
				else title.text($title);
			}
		}));

		var dialog = $( "#"+elementId ).dialog({
			modal: true,
			title: '<div class="widget-header">'+iconHtml+'<strong class="alert no-border '+alertHtml+'" style="background:none;">信息提示！</strong></div>',
			title_html: true,
			minWidth:500,
			buttons: [
				{
					text: "确定",
					"class" : "btn btn-small " + buttonHtml,
					click: function() {
						$( this ).dialog( "close" ); 
					} 
				}
			]
		});
		
		if(closeTime)
		{
			closeTime = parseInt(closeTime) * 1000;

			setTimeout(function(){
				dialog.dialog( "close" );
			},closeTime);
		}
	},
	
	/**	
	  *	  confirm 询问弹框
	  *	  其中 ID标签 可以根据需要改变，如果改变，则需要在第四个参数传递对应的ID
	  *	  -- 事例
	  *	  	$.lagou.confirm('是否删除', function(rs){
	  *   		if(rs) {alert('ok')} else {alert('no')}
	  *	  	}, 0);
	  *
	  * @author songmw<song_mingwei@cdv.com>
	  * @version 1.0
	  * ----------------------------------------------------------------------------------------------------------------------------
	  * @param string msg  			显示信息
	  * @param callback 	  			回调函数，返回TRUE 或 FALSE  
	  * @param int		code				信息码   	0红色1蓝色2绿色 默认0红色
	  * @param string elementId	信息框的ID
	  */
	confirm : function(msg, callback, code, elementId)
	{
		
		if(code == undefined)
		{
			 code = 0;
		}

		if(elementId == undefined)
		{
			elementId = "lg-alert";
		}

		var obj = $('#'+elementId);
		var alertHtml = buttonHtml = '';
		
		// 判断信息码
		switch(code)
		{
			case 0 :
				alertHtml = 'alert-error';
				buttonHtml = 'btn-danger';
				iconHtml = '<i class="icon-warning-sign red"></i>';
				break;
			case 1 :
				alertHtml = 'alert-info';
				buttonHtml = 'btn-info';
				iconHtml = '<i class="icon-bullhorn"></i>';
				break;
			case 2 :
				alertHtml = 'alert-success';
				buttonHtml = 'btn-success';
				iconHtml = '<i class="alert-success icon-ok" style="background:none;"></i>';
				break;
		}
		
		msg = '<div class="alert '+alertHtml+'" style="margin-top:0.5em;">' + msg + '</div>';
		obj.html(msg);
		
		// 为jq-ui dialog追加一个title_html 配置
		$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
			_title: function(title) {
				var $title = this.options.title || '&nbsp;'
				if( ("title_html" in this.options) && this.options.title_html == true )
					title.html($title);
				else title.text($title);
			}
		}));

		var dialog = $( "#"+elementId ).dialog({
			modal: true,
			title: '<div class="widget-header">'+iconHtml+'<strong class="alert no-border '+alertHtml+'" style="background:none;">操作确认提示！</strong></div>',
			title_html: true,
			minWidth:500,
			buttons: [
				{
					text: "取消",
					"class" : "btn btn-small",
					click: function() {
						$( this ).dialog( "close" ); 
						callback(false);
					} 
				},
				{
					text: "确定",
					"class" : "btn btn-small " + buttonHtml,
					click: function() {
						$( this ).dialog( "close" ); 
						callback(true);
					} 
				}
			]
		});
	},
	
	/**
	  *	 显示Form表单
	  * @author songmw<song_mingwei@cdv.com>
	  * @version 1.0
	  * ----------------------------------------------------------------------------------------------------------------------------
	  * @param string url  				要显示的页面地址
	  * @param string elementId	页面上Form的ID
	  */
	formShow : function(url, elementId)
	{
		if(elementId == undefined)
		{
			elementId = "lg-form";
		}

		$.get(url, '', function(data){
			$('#'+elementId).html('').html(data);
		});
		$('#'+elementId).modal({show:true});
	},
	
	/**
	  * 关闭Form表单
	  * @author songmw<song_mingwei@cdv.com>
	  * @version 1.0
	  * ----------------------------------------------------------------------------------------------------------------------------
	  *	@param string elementId 页面上Form的ID
	  */
	formHide : function(elementId)
	{
		if(elementId == undefined)
		{
			elementId = "lg-form";
		}
		
		$('#'+elementId).html('');
		$('#'+elementId).modal('hide');	
	},
	
	/**
	  *	 lagou TABLE表单插件封装
	  *  需要加载的样式文件有: jquery.dataTables.min.js & jquery.dataTables.bootstrap.js
	  *	  -- 事例
	  *	  	
	  *
	  * @author songmw<song_mingwei@cdv.com>
	  * @version 1.0
	  * ----------------------------------------------------------------------------------------------------------------------------
	  * @param json 	dSortColumn		默认排序的字段和类型	required
	  * @param json 	aSortColumn		需要排序的字段  			required
	  * @param string url  						ajax数据源URL				required
	  * @param string	fnServerParams	附加数据的回调函数
	  * @param string elementId			信息框的ID
	  * @return object 返回datatables对象
	  */
	  table : function(dSortColumn, aSortColumn, ajaxSourceUrl, lgServerParams, elementId)
	  {
			if(ajaxSourceUrl == undefined || dSortColumn == undefined || aSortColumn == undefined)
			{
				return false;
			}
			
			if(elementId == undefined)
			{
				elementId = "lg-table";
			}
			
			if(lgServerParams == undefined)
			{
				lgServerParams = "params";
			}
			
			// dataTables 初始化设置
			var lg = $('#'+elementId).dataTable({
			
				// ------------------------------------- 对dataTables进行布局
				// 布局datatable的一个强大属性
				// lfrtip
				// l- 每页显示数量
				//f – 过滤输入
				//t – 表单Table
				//i – 信息
				//p – 翻页
				//r – pRocessing
				//< and > – div elements
				//<”class” and > – div with a class
				//Examples: <”wrapper”flipt>, <lf<t>ip>
				// t <div class="row-fluid"><div class="span2">信息</div><div class="span4">每页显示数</div><div class="span6">翻页</div></div>
				"sDom" : "t<'row-fluid'<'span3'i><'span2'l><'span7'p>>",
				
				// 用于渲染一个参数 ?
				"bDeferRender":false,	
				
				// -------- 额外传递的数据
				"fnServerParams" : eval(lgServerParams),

				// ------------------------------------- 设置处理数据相关操作
				
				//是否显示 “正在处理” 信息
				"bProcessing":false,		// 要使用sDom 此必须为false ?
				//是否后数据 ?
				"bServerSide":true,
				//后台数据源  url
				"sAjaxSource" : ajaxSourceUrl,			
				//处理数据的方法
				"fnServerData":function(sSource, aoData, fnCallback ){
				
					// 设置 url参数
					// sSource = sSource
					
					// ajax提交方法
					$.ajax({
						"dataType": "json",
						"type": "GET",
						"url": sSource,
						"data": aoData,
						"success": fnCallback
					});
				},
			
				// ------------------------------------- 设置一些相关显示的信息

				//是否显示搜索按钮
				"bFilter" : false,	
				//是否显示分页
				"bPaginate":true,
				//是否显示信息
				"bInfo":true,
				
				// 设置哪些列 需要 排序
				"aoColumns" : aSortColumn,
				
				// 设置默认排序字段 以及 类型
				"aaSorting" : dSortColumn,
				
				// 设置多语言
				"oLanguage": {
					"sLengthMenu": "每页显示 _MENU_ 条记录",
					"sZeroRecords": "对不起，查询不到任何相关数据",
					"sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录",
					"sInfoEmpty": "找不到相关数据",
					"sInfoFiltered": "共为 _MAX_ 条记录)",
					"sProcessing": "正在加载中...",
					"sSearch": "搜索",
					"sUrl": "", 						//多语言配置文件
					"oPaginate": {
						"sFirst":    "第一页",
						"sPrevious": " 上一页 ",
						"sNext":     " 下一页 ",
						"sLast":     " 最后一页 "
					}
				}
			});
	  
		return lg;
	  },
	  
	  /**
	  *	 lagou TABLE表单刷新页面
	  *	  -- 事例
	  *			$.lagou.tableRefresh(lg, 'last');  // 刷新到最后一页
	  *			$.lagou.tableRefresh(lg, 'first'); // 刷新到第一页
	  *			$.lagou.tableRefresh(lg);			 // 刷新后停留在当前页		默认
	  *			$.lagou.tableRefresh(lg, 2);		 // 刷新到第二页
	  *			$.lagou.tableRefresh(lg, 'all');	 //  表格重新绘制			搜索的时候要用这个
	  *
	  * @author songmw<song_mingwei@cdv.com>
	  * @version 1.0
	  * ----------------------------------------------------------------------------------------------------------------------------
	  * @param object 		obj					通过 $.lagou.table 生成的对象
	  * @param int/string 	refresh				跳转的最后参数   
	  * 															整形数值 ： 跳转 对应的页数
	  *																字符串     :   'first' 第一页  'last' 最后一页	'all'  全部重新绘制
	  *																默认		   :  当前页刷新
	  * @return object 返回datatables对象
	  */
	  tableRefresh : function (obj, refresh)
	  {
			if(obj == undefined)
			{
				return false;
			}
		
			if(refresh == undefined)
			{
				refresh = parseInt($('.dataTables_paginate.paging_bootstrap.pagination .active a').html());
			}

			if(typeof refresh == 'number')
			{
				if(refresh >= 1)
				{
					refresh = refresh - 1;
				}
				
				obj.fnPageChange(refresh);
			}
			else if (typeof refresh == 'string')
			{
				if(refresh == 'all')
				{
					obj.fnDraw();
				}
				else if (refresh == 'first' || refresh == 'last')
				{
					obj.fnPageChange(refresh);
				}
			}
			
			return false;
	  },

	  dataTable : function(id_name, ao_columns)
	  {
		var tableObj = $('#'+id_name).dataTable({
			// 这个是配置都有哪些列 是允许进行 排序的.
			"aoColumns": ao_columns,
			//是否显示搜索按钮
			"bFilter" : false,	
			"sDom" : "t<'row-fluid'<'span3'i><'span2'><'span7'p>>",
			// 设置多语言
			"oLanguage": {
				"sLengthMenu": "每页显示 _MENU_ 条记录",
				"sZeroRecords": "对不起，查询不到任何相关数据",
				"sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录",
				"sInfoEmpty": "找不到相关数据",
				"sInfoFiltered": "共为 _MAX_ 条记录)",
				"sProcessing": "正在加载中...",
				"sSearch": "搜索",
				"sUrl": "", 						//多语言配置文件
				"oPaginate": {
					"sFirst":    "第一页",
					"sPrevious": " 上一页 ",
					"sNext":     " 下一页 ",
					"sLast":     " 最后一页 "
				}
			}
		});

	  	 return tableObj;
	  }
	
}