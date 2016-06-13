/**
  *  weitac 元素JS  
  *  如 tree...
  *  
  * @copyright cdv
  * @date  2013-11-22
  */


(function($ , undefined) {
	$.fn.weitac_tree = function(options) {
		var $options = {
			'open-icon' : 'icon-folder-open',
			'close-icon' : 'icon-folder-close',
			'selectable' : true,
			'selected-icon' : 'icon-ok',
			'unselected-icon' : 'tree-dot'
		}
		var treeObj = '';
		$options = $.extend({}, $options, options)

		this.each(function() {
			var $this = $(this);
			$this.html('<div class = "tree-folder" style="display:none;">\
				<div class="tree-folder-header">\
					<i class="'+$options['close-icon']+'"></i>\
					<div class="tree-folder-name"></div>\
				</div>\
				<div class="tree-folder-content"></div>\
				<div class="tree-loader" style="display:none"></div>\
			</div>\
			<div class="tree-item" style="display:none;">\
				'+($options['unselected-icon'] == null ? '' : '<i class="'+$options['unselected-icon']+'"></i>')+'\
				<div class="tree-item-name"></div>\
			</div>');
			$this.addClass($options['selectable'] == true ? 'tree-selectable' : 'tree-unselectable');
			
			$this.tree($options);
			
		});

		return this;
	}
})(window.jQuery);