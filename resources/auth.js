$(document).ready(function () {

    $('#loginForm').on('submit', function (e) {
        e.preventDefault();
        var $msg = $('#login-message');
        $msg.text('').removeClass('error success');

        $.ajax({
            url: 'db/auth_request.php',
            method: 'POST',
            data: {
                login_user: true,
                user_email: $('#login-email').val(),
                user_password: $('#login-password').val()
            },
            success: function (result) {
                var data = JSON.parse(result);

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    $msg.text(data.message).addClass('error');
                }
            },
            error: function () {
                $msg.text('Something went wrong. Please try again.').addClass('error');
            }
        });
    });

    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        var $msg = $('#register-message');
        $msg.text('').removeClass('error success');

        $.ajax({
            url: 'db/auth_request.php',
            method: 'POST',
            data: {
                register_user: true,
                user_name: $('#register-name').val(),
                user_email: $('#register-email').val(),
                user_password: $('#register-password').val(),
                confirm_password: $('#register-confirm-password').val()
            },
            success: function (result) {
                var data = JSON.parse(result);

                if (data.success) {
                    $msg.text(data.message + ' Redirecting to log in...').addClass('success');
                    $('#registerForm')[0].reset();
                    setTimeout(function () {
                        window.location.href = 'login.php';
                    }, 1500);
                } else {
                    $msg.text(data.message).addClass('error');
                }
            },
            error: function () {
                $msg.text('Something went wrong. Please try again.').addClass('error');
            }
        });
    });

});