(function($){
 $('#mobile-owl').owlCarousel({
    items: 8,
    dots: false,
    loop:false,
    autoplay: false,
    nav:false,

    
     navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"],
     
     responsive:{
        0:{
            items:2
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
 });



 $('#homebanner-owl').owlCarousel({
    items: 1,
    loop:false,
    dots: true,
    autoplay: true,
    nav:true,
 	navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"]
 });




})(jQuery);