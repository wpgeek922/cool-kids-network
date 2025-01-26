jQuery(document).ready(function ($) {
    $('#cool-kids-login').on('submit', function (e) {
        e.preventDefault();

        const email = $(this).find('input[name="email"]').val();

        $.post(coolKidsAjax.ajaxUrl, {
            action: 'login_user',
            email: email,
        })
        .done(function (response) {
            if (response.success) {              
                window.location.href = '/profile';              
            } else {
                // Error: Update error message and apply the error class
                $('#login-result').html('<p>' + response.data.message + '</p>').addClass('bg-red-500').removeClass('bg-green-500');
            }
        })
        .fail(function () {
            // If the AJAX request fails, show a generic error message
            $('#login-result').html('<p>An error occurred. Please try again.</p>').addClass('bg-red-500').removeClass('bg-green-500');
        });
    });
});
