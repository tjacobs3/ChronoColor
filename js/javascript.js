$(document).ready(function() {
    // Init Timeline, artist-id = 1
    timeline.init(1);

    // Init FancyBox
	$(".fancybox").fancybox({
		beforeShow   : function() {
            imageData = timeline.getDataForImage($(this.element).data("image_id"));
			this.title = '<h3>' + imageData.name + '</h3><p class="caption">' + imageData.year + '</p><p class="caption">' + imageData.medium + ', ' + imageData.dimension + '</p>'
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