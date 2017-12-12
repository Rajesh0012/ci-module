//Toggle Button js 

$('.toggle-btn-wrap').click(function(){

         $('aside').toggleClass('left-panel-show'); 
});
 $('.toggle-btn-wrap').click(function(){
            $('body').toggleClass('body-sm');
});
//Toggle Button js Close 

//Action Tool tip js Start
$(".user-td").click(function(e){
                    e.stopPropagation();
                    $(".user-call-wrap").hide();
                    $(this).find(".user-call-wrap").show();
               });
                $("body").click(function(){
                        $(".user-call-wrap").hide();
                });

//Action Tool tip js Close  


//Filter Show or hide JS
$("#filter-side-wrapper").click(function(e){
		  e.stopPropagation();
	$(".filter-wrap").addClass("active");
});
$("body").click(function(e){
   if (!$(e.target).is('.filter-wrap, .filter-wrap *')) {
            $(".filter-wrap").removeClass("active");
        }
});
$(".ftr-btn").click(function(e){
   $(".filter-wrap").removeClass("active");
});
//Filter Show or hide JS Close


//Select Picker Js Start
$('.selectpicker').selectpicker({
});
//Select Picker Js Close




$(".srch-box").keyup(function(){
	var char_length = $(this).val().length;
	if(char_length>0)
	{
		$(".srch-close-icon").addClass("show-srch");	
	}
	else{
	$(".srch-close-icon").removeClass("show-srch");
	}
});

$(".srch-close-icon").click(function(){
	$(".srch-close-icon").removeClass("show-srch");
	$(".srch-box").val('');

})




//  edit  text  box  onclick
      
$(".edit-userprofile").click(function(){
        $('.usp-input').addClass('focus').prop('readonly', false).focus();
        $(".usp-btn-up").show();
        $('.edit-userprofile').hide();
      });


      $(".usp-btn-up").click(function(){
          $('.usp-input').removeClass('focus').prop('readonly', true).focus();
          $('.usp-btn-up').hide();
          $('.edit-userprofile').show();
      });

         
    $(".edit-userpasswrd").click(function(){
         $('.usp-inputpss').addClass('focus').prop('readonly', false).focus();
        $(".btnuser").show();
        $(".edit-userpasswrd").hide();
    });

    $(".btnuser").click(function(){
        $('.usp-inputpss').removeClass('focus').prop('readonly', true);
        $(".btnuser").hide();
        $(".edit-userpasswrd").show();
    });

 //edit  text  box  onclick close


 $(".tble-th").click(function () {
        $(".tbl-trip").toggle();
  });
  
  $(".login_filed").keypress(function (e) {
        if(e.which === 32) 
        return false;
});
  
  