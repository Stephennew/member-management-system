(function ($) {
 "use strict";
    
/*---------------------
 TOP Menu Stick
--------------------- */
	var s = $("#sticker");
	var pos = s.position();					   
	$(window).scroll(function() {
		var windowpos = $(window).scrollTop();
		if (windowpos > pos.top) {
		s.addClass("stick");
		} else {
			s.removeClass("stick");	
		}
	});	
	
/*----------------------------
 wow js active
------------------------------ */
 new WOW().init();

/*---------------------
     venobox
--------------------- */
    
$('.venobox').venobox(); 
    
/*----------------------------
 jQuery MeanMenu
------------------------------ */
	jQuery('nav#dropdown').meanmenu();
	

/*--------------------------
     scrollUp
---------------------------- */
    $.scrollUp({
        scrollText: '<i class="fa fa-angle-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });
	
/*----------------------------
  team-member-carousel
------------------------------ */  
$('.team-member-carousel').owlCarousel({
		loop:true,
		margin:15,
		nav:true,		
		autoplay:true,
	    dots:false,
		smartSpeed:3000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:2
			},
			1000:{
				items:3
			}
		}
	});
/*---------------------
 testimonial-curosel
--------------------- */
	$('.testimonial-carousel').owlCarousel({
		loop:true,
		margin:0,
		nav:false,
        dots:true,
		animateOut: 'slideOutDown',
		animateIn: 'zoomInLeft',		
		autoplay:false,
		smartSpeed:3000,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	});
/*---------------------
 blog-curosel
--------------------- */
	$('.blog-carousel').owlCarousel({
		loop:true,
		margin:0,
		nav:true,
        dots:true,
		animateOut: 'slideOutDown',
		animateIn: 'zoomInLeft',		
		autoplay:false,
		smartSpeed:3000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			700:{
				items:2
			},
			1000:{
				items:3
			}
		}
	})
/*----------------------------
 isotope active
------------------------------ */
	// portfolio start
    $(window).on("load",function() {
        var $container = $('.awesome-project-content');
        $container.isotope({
            filter: '*',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
        $('.project-menu li a').on("click", function() {
            $('.project-menu li a.active').removeClass('active');
            $(this).addClass('active');
            var selector = $(this).attr('data-filter');
            $container.isotope({
                filter: selector,
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false
                }
            });
            return false;
        });

    });
    //portfolio end
	
/*--------------------------
     preloader
---------------------------- */	
$(window).on('load',function(){
	$('#preloader').fadeOut('slow',function(){$(this).remove();});
});	

})(jQuery); 