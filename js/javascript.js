$(document).ready(function() {
	$(".fancybox").fancybox({
		afterLoad   : function() {
			this.title = '<h3>Portrait de Sebastià Junyent</h3><p class="caption">1904</p><p class="caption">Oil on canvas, 73 x 60 cm</p>'
		},
    	helpers:  {
        	thumbs : {
            	width: 50,
            	height: 50
        	},
			title  :  {
				type  :  'outside'
			}
    	}
	});
});