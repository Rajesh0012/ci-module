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
        690:{
            items:3
        },
        768:{
            items:4
        },
        1100:{
            items:6
        },
        1200:{
            items:8
        }
    }
 });



 $('#homebanner-owl').owlCarousel({
    items: 1,
    loop:true,
    dots: true,
    autoplay: true,
    nav:true,
 	navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"]
 });





 $('#latesttips-owl').owlCarousel({
    items: 5,
    loop:false,
    dots: false,
    autoplay: true,
    nav:false,
 	navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"]
 });



 $('#rugby-owl').owlCarousel({
    items: 5,
    loop:false,
    dots: false,
    autoplay: true,
    nav:false,
 	navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"]
 });


 $('#tennis-owl').owlCarousel({
    items: 5,
    loop:false,
    dots: false,
    autoplay: true,
    nav:false,
 	navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"]
 });


 $('#basketball-owl').owlCarousel({
    items: 5,
    loop:false,
    dots: false,
    autoplay: true,
    nav:false,
 	navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"]
 });


 $('#cricket-owl').owlCarousel({
    items: 5,
    loop:false,
    dots: false,
    autoplay: true,
    nav:false,
 	navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"]
 });

 $('#golf-owl').owlCarousel({
    items: 5,
    loop:false,
    dots: false,
    autoplay: true,
    nav:false,
 	navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"]
 });


 $('#boxing-owl').owlCarousel({
    items: 5,
    loop:false,
    dots: false,
    autoplay: true,
    nav:false,
 	navText:["<i class='fa fa-angle-left' class='sas'></i>","<i class='fa fa-angle-right'></i>"]
 });


})(jQuery);