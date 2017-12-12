
$().ready(function() {

		// validate signup form on keyup and submit
		$("#loginForm").validate({
			rules: {
				password: {
					required: true,
					minlength: 6
				},
				email: {
					required: true,
					email: true
				}
			},
			messages: {
					password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long"
				},
					email: "Please enter a valid email address"
			},
                   submitHandler: function(form) {
                              checkLogin();  
                        }
		});


	});

$(function () {

    $('#nextt').click(function (event) {
        var phone=$('#phone').val();
        var name=$('#name').val();
        var email=$('#email').val();
        var country_code= $('#country_code').val();

            event.preventDefault();

            var x = document.forms["userSignUp"]["email"].value;
            var atpos = x.indexOf("@");
            var dotpos = x.lastIndexOf(".");

            if(name.trim().length <3){

                $('#namemsg').html('Enter Full Name');

            }
           else if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
                $('#namemsg').html('');
                $('#emailmsg').html('Not a valid e-mail address');

                return false;
            } else if(phone === '')
            {
                $('#emailmsg').html('');

                $('#phonelmsg').html('Enter phone number');

                return false;

            }else if(phone.trim().length <= 5){

                $('#namemsg').html('');
                $('#emailmsg').html('');
                $('#phonelmsg').html('Enter correct phone number');
                return false;

            }
            else if(country_code.trim().length == ''){

                $('#namemsg').html('');
                $('#emailmsg').html('');
                $('#phonelmsg').html('Country code is required');
                return false;

            }
            else{

                $('#namemsg').html('');
                $('#emailmsg').html('');
                $('#phonelmsg').html('');
                $('#procesing1').show();
                $('#nextt').attr('disabled','disabled');
                $.ajax({

                    url: siteurl +"/web/Auth/preValidation",
                    type: "POST",
                    data:{'csrf_test_name':csrf_value,'phone':phone,'name':name,'email':email,'country_code':country_code},

                    success: function(data)
                    {
                        if(data === '200'){

                            $(".signup-form-wrapper").addClass("animate-form")
                            $(".back-btn").show();
                            $(".dt2").addClass("active");
                            $(".dt1").removeClass("active");
                            $('#servererror').html('');
                            $('#procesing1').hide();
                            $('#nextt').removeAttr('disabled','disabled');

                        }else{

                            $('#servererror').html(data);
                            $('#nextt').removeAttr('disabled','disabled');
                            $('#procesing1').hide();

                        }

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
                });


            }


    });


    });
    
    $(function () {

        $("#userSignUp").on('submit',(function(e) {
            e.preventDefault();

            var password=$('#password').val();
            var confirmpass=$('#confirmpass').val();

            if(password.trim() === ''){
                $('#privacy_policydmsg').html('');
                $('#passwordmsg').html('Password is required');


            }else if(password.trim().length<=4){

                $('#privacy_policydmsg').html('');
                $('#passwordmsg').html('Enter strong password');


            }else if(confirmpass != password){

                $('#privacy_policydmsg').html('');
                $('#passwordmsg').html('password must be same in both field');


            }else if($("#privacy_policy").is(":checked") === false){

                $('#passwordmsg').html('');
                $('#privacy_policydmsg').html('Accept Privacy Policy');



            } else{

                    $('#namemsg').html('');
                    $('#phonelmsg').html('');
                    $('#passwordmsg').html('');
                    $('#privacy_policydmsg').html('');
                    $('#emailmsg').html('');
                $('#procesing').show();
                $('#submitt').attr('disabled','disabled');

                    $.ajax({
                        url: siteurl +"/web/Auth/SignUp",
                        type: "POST",
                        data:  new FormData(this),
                        contentType: false,
                        processData:false,
                        success: function(data)
                        {
                            if(data === '200'){

                                $('#signup-failmsg').hide(data,function () {

                                    $('#signup-success').html('To login please verify Your Account we have sent a link in your email');
                                });

                                setTimeout(function () {
                                    window.location.href= siteurl+ "/web";
                                },3000)

                            }else{

                                $('#signup-failmsg').html(data);

                                $('#procesing').hide();
                                $('#submitt').removeAttr('disabled','disabled');
                            }

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
                    });

            }


        }));

    });


    function showPreview(objFileInput) {
    
    if (objFileInput.files[0]) {
        var fileReader = new FileReader();
        fileReader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
            $("#targetLayer").html('<img src="'+e.target.result+'" width="100px" height="100px" class="upload-preview " />');
            $("#targetLayer").css('opacity','0.7');
            //$(".show-upload-pic").hide();
        }
        fileReader.readAsDataURL(objFileInput.files[0]);
    }
}

function checkLogin() {

    var ldata = $('#loginForm').serializeArray();

    var cvalue = ldata[1].value;
    var cemail = ldata[0].value;
    var cpass = ldata[2].value;
    var ctype = ldata[3].value;

    $.ajax({

        type: 'POST',
        url: siteurl + '/web/auth/index',
        data: {email: cemail, password: cpass, login_type: ctype, "csrf_test_name": cvalue},
        success: function (data) {
            data = JSON.parse(data);
            //console.log(data);
            if (data.code == '101')
                $('#login_failure').html(data.message);
            if (data.code == '102')
                $('#login_failure').html(data.message);
            if (data.code == '200')
                window.location.href = siteurl + '/web';
            //$('#eventsport').html(data);
        },
        error: function (jqXHR, exception) {

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


    });
}

$(document).ready(function() {
    $(document).on('keypress', '#password', function(e){
        return !(e.keyCode == 32);
    });
});

$(document).ready(function() {
    $(document).on('keypress', '#confirmpass', function(e){
        return !(e.keyCode == 32);
    });
});

var increment=2;
function loadMore(thisId) {

    $.ajax({
        type:'GET',
        url:siteurl +'/web/Auth/loadMore',
        data:{'page':increment,'sport_id':thisId},
        success:function (data) {
            if(data.trim().length == ''){

                $('#appends').append('<li class="clearfix"><span style="margin-left:380px;font-size:medium">No More Results</span></li>');
                $('#loaddata').hide();
            }else{
                $('#appends').append(data).fadeIn('slow');
            }



        },
        error: function (jqXHR, exception) {

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
    increment++;
}


$('#myModal-signup').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
})

function getData(tour_id,tour_name,sport_id)
{
    if(tour_name=='Basketball')
              window.location.href=siteurl + '/web/home/basketball?id='+sport_id+'&tour_id='+tour_id;
    if(tour_name=='Tennis')
              window.location.href=siteurl + '/web/home/tennis?id='+sport_id+'&tour_id='+tour_id;
    if(tour_name=='Golf')
              window.location.href=siteurl + '/web/home/golf?id='+sport_id+'&tour_id='+tour_id;
    if(tour_name=='Boxing')
              window.location.href=siteurl + '/web/home/boxing?id='+sport_id+'&tour_id='+tour_id;
    if(tour_name=='Rugby')
              window.location.href=siteurl + '/web/home/rugby?id='+sport_id+'&tour_id='+tour_id;
    if(tour_name=='Football')
              window.location.href=siteurl + '/web/home/football?id='+sport_id+'&tour_id='+tour_id;
    if(tour_name=='Cricket')
              window.location.href=siteurl + '/web/home/cricket?id='+sport_id+'&tour_id='+tour_id;
}

var inc=2;
function loadMoreData(str) {


    $.ajax({
        type:'GET',
        url:siteurl +'/web/Home/loadMoredata',
        data:{'page':inc,'sport_id':str},
        success:function (data) {
            if(data.trim().length == ''){

               // $('#dataappendhere').append('<li class="clearfix"><span style="margin-left:380px;font-size:medium">No More Results</span></li>');
               // $('#dataappendhere').hide();
            }else{
               // $('#dataappendhere').append(data);
            }



        },
        error: function (jqXHR, exception) {

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
    inc++;
}

