var timeline = {
    artistID: null,
    imageData: null,
  
    init: function (id) {
        this.artistID = id;
    
        // Set up timeline animations
        $(".timelineBlock").hover(function(){
            $(this).stop(true, false).animate({ height:"80px" });
        }, function() {
            $(this).stop(true, false).animate({ height:"60px" });
        });
        
        // Set up the clicks
        var that = this;
        $(".timelineBlock").click(function(){
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/index.php/image/images/1/" + $(this).data("year") + "/" + $(this).data("quarter")
            }).done(function( msg ) {
                that.imageData = msg;
                that.showGallery(msg);
            });
        });
    },
    
    getDataForImage: function (id) {
        for(var i = 0; i < this.imageData.length; i++) {
            if(this.imageData[i].id == id) return this.imageData[i];
        }
        return null;
    },
    
    showGallery: function(imageData) {
        var $displayBlock = $(".detailblock").first();
        
        var elementsToAttach = [];
        for(var i = 0; i < imageData.length; i++) {
            var $imageLink = $('<a class="fancybox fancybox.ajax" rel="group" href="/index.php/image/image_view/' + imageData[i].id + '" data-image_id="' + imageData[i].id + '"></a>');
            var $imageThumb = $('<img class="thumbnail" src="' + imageData[i].thumb_location + '" alt="" />');
            
            $imageLink.append($imageThumb);
            elementsToAttach.push($imageLink);
        }
        
        $displayBlock.empty();
        $displayBlock.append(elementsToAttach);
    }
}