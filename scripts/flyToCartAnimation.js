
var toastMsgBody = document.getElementById("toastMsgBody");

$('.add-to-cart').on('click', function () {
        var cart = $('.shopping-cart');
        //var imgtodrag = $(this).parent('.item').find("img").eq(0);
		var imgtodrag = $(this).parent().parent().parent().next();
        var imgtodragWidth = $(this).parent().parent().parent().next().width();
        if (imgtodrag) {
            var imgclone = imgtodrag.clone()
                .offset({
                top: imgtodrag.offset().top,
                left: imgtodrag.offset().left
            })
                .css({
                'opacity': '0.8',
                    'position': 'absolute',     
                    'width': imgtodragWidth,        
                    'z-index': '1000'
            })

                .appendTo($('body'))
                .animate({
                    'top': cart.offset().top + 10,
                    'left': cart.offset().left + 10,
                    'width': 50,
                    'height': 50
            }, 1000, 'easeInOutExpo');

            setTimeout(function () {
                cart.effect("shake", {
                    times: 2,
                    distance: 2
                }, 400);
            }, 1500);
            
            
            
            imgclone.animate({
                'width': 0,
                    'height': 0
            }, function () {
                $(this).detach()
            });
        }
    });    

