<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script type="module">
        import '../js/bundle.js';
    </script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../css/home.css" rel="stylesheet">
</head>

<body>

    <div class="sign-in-container">
        <div class="sign-in-header">
            Sign In
        </div>
        <form id="login-form" method="post">
            <md-outlined-text-field class="input-field" id="email-field" label="Email" type="email" required></md-outlined-text-field>
            <md-outlined-text-field class="input-field" id="password-field" label="Password" type="password" required oninvalid="setCustomValidity('Password must be a minimum of 6 characters. At least 1 uppercase letter, 1 lowercase letter, and 1 number. No spaces.')" oninput="setCustomValidity('')" pattern="^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$">
            </md-outlined-text-field>

            <p id="error-message"></p>

            <!-- Hidden input fields to store the data because of UI -->
            <input type="hidden" name="email" id="email-hidden">
            <input type="hidden" name="password" id="password-hidden">

            <div class="button-container">
                <md-filled-button type="submit">Sign In</md-filled-button>
            </div>
        </form>
        <p class="register-link">New to this site? <a href="sign-up.php">Create an account</a></p>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                e.preventDefault();

                // Get values from custom fields
                var email = $('#email-field').val();
                var password = $('#password-field').val();

                // Set the values of hidden input fields
                $('#email-hidden').val(email);
                $('#password-hidden').val(password);

                // Submit form data via AJAX
                $.ajax({
                    url: '../actions/login_user_action.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '../views/verify.php';
                        } else {
                            $('#error-message').html(response.message);
                        }
                    },
                    error: function() {
                        $('#error-message').html('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>

</body>
</html>
