
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
			}
		});


	});

$(function () {

    $('#nextt').click(function (event) {
        var phone=$('#phone').val();

            event.preventDefault();

            var x = document.forms["userSignUp"]["email"].value;
            var atpos = x.indexOf("@");
            var dotpos = x.lastIndexOf(".");
            if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
                $('#emailmsg').html('Not a valid e-mail address');

                return false;
            } else if(phone === '')
            {
                $('#emailmsg').html('');

                $('#phonelmsg').html('Enter phone number');

                return false;

            }else if(phone.trim().length <= 5){

                $('#phonelmsg').html('Enter correct phone number');
                return false;

            }
            else{
                $('#phonelmsg').html('');
                $(".signup-form-wrapper").addClass("animate-form")
                $(".back-btn").show();
                $(".dt2").addClass("active");
                $(".dt1").removeClass("active");
            }


    })


    })

    $(function () {

        $('#submitt').click(function (event) {

            event.preventDefault();


            var password=$('#password').val();
            var confirmpass=$('#confirmpass').val();

            if(password.trim() === ''){
                passwordmsg
                $('#passwordmsg').html('Password is required');


            }else if(password.trim().length<=4){


                $('#passwordmsg').html('Enter strong password');


            }else if(confirmpass != password){

                $('#passwordmsg').html('password must be same in both field');


            }else if($("#privacy_policy").is(":checked") === false){

                $('#passwordmsg').html('');
                $('#privacy_policydmsg').html('Accept Our Privacy Policy');



            }else{

                $('#privacy_policydmsg').html('');

                var file_data = $('#signup-pic').prop('files')[0];
                var form_data = new FormData();
                form_data.append('file', file_data);


                $.ajax({

                    type:"POST",
                    url: siteurl + '/web/Auth/SignUp',
                    data:form_data,
                    success: function (data) {

                      alert(data);
                       // location.reload();

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
            }


        })

    })

function showPreview(objFileInput) {
    if (objFileInput.files[0]) {
        var fileReader = new FileReader();
        fileReader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
            $("#targetLayer").html('<img src="'+e.target.result+'" width="100px" height="100px" class="upload-preview" />');
            $("#targetLayer").css('opacity','0.7');
            $(".show-upload-pic").hide();
        }
        fileReader.readAsDataURL(objFileInput.files[0]);
    }
}

