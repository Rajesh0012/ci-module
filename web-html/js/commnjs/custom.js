   $(".next-btn1").click(function(){
              $(".signup-form-wrapper").addClass("animate-form")  
              $(".back-btn").show();  
              $(".dt2").addClass("active");
              $(".dt1").removeClass("active");  
         });

         function dotrest(){
            $(".dots-circle-wrap ul li span").removeClass("active");
         };

        //  $(".dt2").click(function(){
        //     $(".signup-form-wrapper").addClass("animate-form")  
        //       $(".back-btn").show(); 
        //       $(".dt2").addClass("active");
        //       $(".dt1").removeClass("active");  
        //  });

        //  $(".dt1").click(function(){
        //     $(".signup-form-wrapper").removeClass("animate-form")  
        //       $(".back-btn").hide();
        //       $(".dt2").removeClass("active");
        //       $(".dt1").addClass("active");  
        //  });

         $(".user-clk").click(function(e){
           e.stopPropagation();
            $(".setting-top-wrap").slideToggle();
         });
         $("body").click(function(){
          $(".setting-top-wrap").slideToggle();
       });


         $(".back-btn").click(function(){
              $(".signup-form-wrapper").removeClass("animate-form")  
              $(".back-btn").hide();
              $(".dt1").addClass("active");
              $(".dt2").removeClass("active");  
         });

         $(".toggle-top-nave").click(function(){
            $("body").addClass("overlay-wrap");
              $(".top-menu-wrap").toggleClass("top-menu-wrap-show");
         });
         $(".close-nave-top").click(function(){
          $(".top-menu-wrap").removeClass("top-menu-wrap-show");
          $("body").removeClass("overlay-wrap");
         
         })

          $(".search-header").click(function(e){
            e.stopPropagation();  
            $(".search-header").toggleClass("search-header-close");
            $(".search-result-wrap").slideToggle();
           })

           $("body").click(function(e){

            if (!$(e.target).is('.search-result-wrap, .search-result-wrap *')) {
              $(".search-header").removeClass("search-header-close");
            $(".search-result-wrap").slideUp();
        }

            
           });

           $('.selectpicker').selectpicker({
});

$('body').on('hidden.bs.modal', '.modal', function () {
    $(this).removeData('bs.modal');
});
