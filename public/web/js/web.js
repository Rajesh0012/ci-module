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
        
        function checkLogin(){
            var ldata=$('#loginForm').serializeArray();
           
            var cvalue=ldata[1].value;
            var cemail=ldata[0].value;
            var cpass=ldata[2].value;
            var ctype=ldata[3].value;
            
            $.ajax({

                type:'POST',
                url: siteurl + '/web/auth/index',
                data:{email:cemail,password:cpass,login_type:ctype,"csrf_test_name":cvalue},
                success:function (data) {
                   data= JSON.parse(data);
                   //console.log(data);
                if(data.code=='101')
                    $('#login_failure').html(data.message);
                if(data.code=='102')
                    $('#login_failure').html(data.message);
                if(data.code=='200')
                    window.location.href=siteurl+'/web';
            //$('#eventsport').html(data);
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
        