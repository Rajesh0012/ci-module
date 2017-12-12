//we're going to run form validation on the #validate-form element
$("#login_form").validate({
//specify the validation rules

    //errorClass: 'form-field-wrap commn-animate-error',
    rules: {

        email: {
            required: true,
            email: true

            //email is required AND must be in the form of a valid email address
        },
        password: {
            required: true,
            minlength:5
        }
    },

//specify validation error messages
    messages: {
        firstname: "First Name field cannot be blank!",
        lastname: "Last Name field cannot be blank!",
        password: {
            required: "Password field cannot be blank!",
            minlength: "Your password must be at least 6 characters long"
        },
        email: "Please enter a valid email address"
    },



    submitHandler: function(form){
        form.submit();
    }

});