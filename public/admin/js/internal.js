$.validate({
    scrollToTopOnError: false
});

function setRemember() {
    var checkval = $('#rem_value').val();
    //var remember = $('#flowers').val();
    //alert(checkval);
    if (checkval) {
        if (checkval == 'off')
        {
            $('#rem_value').val('on');
            $('#flowers').attr('checked', true);

        }
        else
        {
            $('#rem_value').val('off');
            $('#flowers').attr('checked', false);
        }

    }
    else {
        $('#rem_value').val('on');
        $('#flowers').attr('checked', true);
    }
}

$('#match_type').click(function () {
    var mtype = $('input[name=tipslect]:checked').val();
    if (typeof mtype == "undefined") {
        $('#msid').html('Please select atleast one.');
        return false;
    }
    else {
        $('#msid').html('');

        if (mtype == 'Game') {
            window.location = siteurl + "/admin/games";
        }

        if (mtype == 'Featured Match') {
            window.location = siteurl + "/admin/featurematch";
        }
    }
    //alert(mtype); 
});

$('.select-filter').click(function () {
    var seltype = $('input[name=select_filter]:checked').val();
    if (typeof seltype == 'undefined') {

        $('#bid').css('display', 'none');
        $('#tid').css('display', 'none');
    }
    else {

        $('#bid').css('display', 'inline-block');
        $('#tid').css('display', 'inline-block');
    }

});

$('#bid').css('display', 'none');
$('#tid').css('display', 'none');

/*$('.lbl-check').click(function(){
 var chtype = $('input[name=filter]:checked').val();
 alert(chtype);
 });*/

tinymce.init({
    selector: "textarea",
    height: 300,
    plugins: [
        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
    ],
    toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
    // toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

    menubar: false,
    toolbar_items_size: 'small',
    style_formats: [{
            title: 'Bold text',
            inline: 'b'
        }, {
            title: 'Red text',
            inline: 'span',
            styles: {
                color: '#ff0000'
            }
        }, {
            title: 'Red header',
            block: 'h1',
            styles: {
                color: '#ff0000'
            }
        }, {
            title: 'Example 1',
            inline: 'span',
            classes: 'example1'
        }, {
            title: 'Example 2',
            inline: 'span',
            classes: 'example2'
        }, {
            title: 'Table styles'
        }, {
            title: 'Table row 1',
            selector: 'tr',
            classes: 'tablerow1'
        }],
    templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        }, {
            title: 'Test template 2',
            content: 'Test 2'
        }],
    content_css: [
    ]
});

function Logout(){

    window.location = siteurl + "/admin/auth/logout";
}



$('#cms-doc').on('change', function () {
    var xVal = $(this).val();
    $('#upload-doc').val(xVal);
    // alert('ok');
});

function createTip(tour_id,event_id){
   window.location = siteurl + "/admin/tips/add_tips_feature_match?tid="+btoa(tour_id)+'&eid='+btoa(event_id);
}


function Category(str) {

    $.ajax({
        
        type:'GET',
        url:siteurl +'/admin/Sports/getCategories',
        data:{id:str},

        success:function (data) {

            $('#category').html(data);
        },
        error:function (jqXHR,exception) {

            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'URL page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            alert(msg);
        }
        
        
    })

}

function getTournament(str) {


    $.ajax({

        type:'GET',
        url:siteurl + '/admin/Sports/getTournament',
        async: true,
        data:{id:str},

        success:function (data) {

            $('#tournament').html(data);
        },
        error:function (jqXHR,exception) {

            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'URL page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            alert(msg);
        }


    })

}



function getEvent() {

    var tournamentId= $('#tournament').find(":selected").val();

    $.ajax({

        type:'GET',
        url: siteurl + '/admin/Sports/getEvent',
        async: true,
        data:{id:tournamentId},

        success:function (data) {

            $('#eventhere').html(data);
        },
        error:function (jqXHR,exception) {

            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'URL page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            alert(msg);
        }


    })

}

function getEventDetails(str) {


    $.ajax({

        type:'GET',
        url: siteurl + '/admin/Sports/getSportEvent',
        async: true,
        data:{id:str},

        success:function (data) {

            $('#sporteventhere').html(data);
        },
        error:function (jqXHR,exception) {

            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'URL page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            alert(msg);
        }


    })

}

function add(str) {

    $('#addedOdds').show();
    $.ajax({

        type:'GET',
        url: siteurl + '/admin/Home/addComptitors',
        data:{id:str},

        success:function (data) {

            $('#addedOdds').html(data);
            setTimeout(function(){

                $('#addedOdds').fadeOut('1000');

            },1000)
        },
        error:function (jqXHR,exception) {

            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'URL page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            alert(msg);
        }


    })

}

function getMatch(str) {

    var tval=$('#get_type').val();

    $('#addedOdds').show();
    $.ajax({

        type:'GET',
        url: siteurl + '/admin/Home/eventMatches',
        data:{id:str,'type':tval},

        success:function (data) {

            $('#eventeventhere').html(data);
        },
        error:function (jqXHR,exception) {

            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'URL page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }

            alert(msg);
        }


    })

}


function resetsearch() {

    window.location.href=siteurl + "/admin/users/user_list";

}

function validateEmail(email) {

    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


function validate() {

    var pass= $('#password').val()

    var email = $("#email").val();
    if (validateEmail(email)) {

        $('#emailerror').removeClass('commn-animate-error');

    } else {
        $('#emailerror').addClass('commn-animate-error');

        return false;
    }

    if(pass.length <=3){

        $('#passworderror').addClass('commn-animate-error');
        return false;

    }else{

        $('#passworderror').removeClass('commn-animate-error');
        return true;

    }

    return false;


}

function createLinks(str) {

   var url = siteurl + "/admin/Home/getEvents?tid=" + str;

   $('#set_link').val(url);

}

function get_details(){
   var  lurl= $('#set_link').val();
   var esel=$('#event_select').val();
   var ssel=$('#select_sports').val();
  
  if(ssel=='novs'){

      alert('Please select a sport.');
      return false;
  }
  else if(esel=='noev'){
      alert('Please select a event.');
      return false;
  }
  else{
        window.location.href=lurl;
  }
    
}



$("#flowers").change(function(){  //"select all" change
    var status = this.checked; // "select all" checked status
    $('.checked').each(function(){ //iterate all listed checkbox items
        this.checked = status; //change ".checkbox" checked status

        $('#showtrash').fadeIn('slow');
        $('#blok').fadeIn('slow');
        $('#removebttn').fadeIn('slow');

    });

    if($('.checked:checked').length == false){


        $('#showtrash').fadeOut('slow');
        $('#blok').fadeOut('slow');
        $('#removebttn').fadeOut('slow');

    }

});

$('.checked').change(function(){ //".checkbox" change
    //uncheck "select all", if one of the listed checkbox item is unchecked
    if(this.checked == false){ //if this item is unchecked
        $("#flowers")[0].checked = false;
        //change "select all" checked status to false
    }

        $('#showtrash').fadeIn('slow');
        $('#blok').fadeIn('slow');
        $('#removebttn').fadeIn('slow');

    //check "select all" if all checkbox items are checked
    if ($('.checked:checked').length == $('.checked').length ){
        $("#flowers")[0].checked = true;

        //change "select all" checked status to true
    }

    if($('.checked:checked').length == false){


        $('#showtrash').fadeOut('slow');
        $('#blok').fadeOut('slow');
        $('#removebttn').fadeOut('slow');

    }
});

$('.select_tour').click(function(){
    $('.match_class').hide();
    $('#get_type').val('1');
});

$('.select_match').click(function(){
    $('.match_class').show();
    $('#get_type').val('2');
});


function showPreview(objFileInput) {


    if (objFileInput.files[0]) {
        var fileReader = new FileReader();
        fileReader.onload = function (e) {
            //$('#blah').attr('src', e.target.result);
            $("#targetLayer").html('<img src="'+e.target.result+'" width="100px" height="150px" class="upload-preview " />');
            $("#targetLayer").css('opacity','0.7');
            $("#placeholder").hide();
        }
        fileReader.readAsDataURL(objFileInput.files[0]);
    }
}


var selected;
function selectedValue(ids) {

    var dropdown1 = document.getElementById(ids);
    var textbox = document.getElementById(ids);
    selected = dropdown1.selectedIndex;




}


function setpriorities(thisId,value,changeId)
{

    if(confirm('Are you Sure Want Change Priorities'))
    {
            $('#process').show();

        $.ajax({

            type:'GET',
            url: siteurl + '/admin/Tips/change_priorities',
            data:{id:changeId,'value':value},

            success:function (data) {
                


                    $('#msgarea').show();
                    $('#msgarea').html(data);
                    $('#process').hide();
                    
                  setTimeout(function () {
                          $('#msgarea').fadeOut('slow');
                  },2000)

                window.location.href=siteurl +'/admin/freetips';



               // $('#eventeventhere').html(data);
            },
            error:function (jqXHR,exception) {

                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'URL page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                alert(msg);
            }


        })



    }else{

        document.getElementById(thisId).selectedIndex= selected;
        return false;
    }

}


function addTipUrl(market,book){
    
    window.location.href=siteurl + '/admin/Tips/add_tips_feature_match?mid='+market+'&book='+book;
}

$(function() {
    // Multiple images preview in browser
    var imagesPostPreview = function(input, placeToInsertImagePostPreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).attr('width','100%').attr('height','100%;').attr('id', 'placeholder').appendTo(placeToInsertImagePostPreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

   $('#upload-img').on('change', function() { 
              $('#placeholder').remove('img');
              imagesPostPreview(this, 'div.post_gallery');
    });
    
  });
  
  
   $().ready(function() {

		// validate signup form on keyup and submit
		$("#add_tip").validate({
			rules: {
				title: {
					required: true,
					maxlength:50
				},
				short_desc: {
					required: true,
					maxlength:100
				}
                                ,
				status: {
					required: true
				}
                                
			},
			messages: {
					title: {
					required: "Please enter a title",
					maxlength: "Title coul be 50 character long"
				},
                                short_desc: {
					required: "Please enter a s'/admin/Tips/add_tips_feature_matchhort description",
					maxlength: "Discription coul be 100 character long"
				},
                                status: {
					required: "Status is required"
				}
                                
			},
                   submitHandler: function(form) {
                              return true; 
                        }
		});


	});
  
  $().ready(function() {

		// validate signup form on keyup and submit
		$("#edit_tip").validate({
			rules: {
				title: {
					required: true,
					maxlength:50
				},
				short_desc: {
					required: true,
					maxlength:100
				}
                                ,
				status: {
					required: true
				}
                                
			},
			messages: {
					title: {
					required: "Please enter a title",
					maxlength: "Title coul be 50 character long"
				},
                                short_desc: {
					required: "Please enter a short description",
					maxlength: "Discription coul be 100 character long"
				},
                                status: {
					required: "Status is required"
				}
                                
			},
                   submitHandler: function(form) {
                              return true; 
                        }
		});


	});
        
        function setTip(id){
            $('#del_tip_id').val(id);
        }


$(".website-banner").show();
$(".application-banner").hide();

