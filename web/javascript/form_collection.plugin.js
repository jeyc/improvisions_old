(function($)
{
	$.fn.formCollection = function(options)
	{
		// Extend our default options with those provided.
		var opts = $.extend({}, $.fn.formCollection.defaults, options);
		
		return this.each(function() {
			
			var $moreLink = $('<a href="#"></a>').text(opts.moreText);
			var $lessLink = $('<a href="#"></a>').text(opts.lessText);
			
			var $moreLi = $('<li></li>').append($moreLink);
			var $lessLi = $('<li></li>').append($lessLink);
			
			var $ul = $('<ul></ul>')
				.append($moreLi)
				.append($lessLi);
			
			$(this).before($ul);
			
			$(this).data('index', $(this).find('tr').length);
			
			var $table = $(this);
			$moreLink.on('click', function(e) {
				
				// prevent the link from creating a "#" on the URL
				e.preventDefault();
				
				// add a new tag form (see next code block)
				var proto = $table.data('prototype');
				
				var index = $table.data('index');
				
				if (index < opts.max)
				{
					var newForm = proto.replace(/__name__/g, index);
			
					$table.data('index', index + 1);
			
					$table.append(newForm);
					opts.callBack.call();
				}
			});
			
			$lessLink.on('click', function(e) {
				e.preventDefault();
				var index = $table.data('index');
				
				if (index > opts.min)
				{
					$table.data('index', index - 1);
					$table.find('tr:last').remove();
					opts.callBack.call();
				}
			});
			
		});
		
	};	
	
	$.fn.formCollection.defaults = {
		moreText: "More",
		lessText: "Less",
		max: "10",
		min: "1",
		callBack: function() {}
	};
	
})(jQuery);
