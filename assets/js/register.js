jQuery(document).ready(function ($) {
    $('#cool-kids-register').on('submit', function (e) {
        e.preventDefault();

        const email = $(this).find('input[name="email"]').val();

        $.post(coolKidsAjax.ajaxUrl, {
            action: 'register_user',
            email: email,
        })           
        .done(function (response) {
            if (response.success) {
                // Success: Update success message and apply the success class
                $('#register-result').html('<p>' + response.data.message + '</p>').addClass('bg-green-500').removeClass('bg-red-500');
                // Redirect after a brief delay to give the user feedback
                setTimeout(function() {
                    window.location.href = '/profile';
                }, 2000); // Redirect after 2 seconds
            } else {
                // Error: Update error message and apply the error class
                $('#register-result').html('<p>' + response.data.message + '</p>').addClass('bg-red-500').removeClass('bg-green-500');
            }
        })
        .fail(function () {
            // If the AJAX request fails, show a generic error message
            $('#register-result').html('<p>An error occurred. Please try again.</p>').addClass('bg-red-500').removeClass('bg-green-500');
        });
    });
});
