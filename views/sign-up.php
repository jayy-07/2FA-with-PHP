<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <script type="module">
    import '../js/bundle.js'; // Make sure bundle.js contains your components
  </script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="../css/home.css" rel="stylesheet">
</head>

<body>
  <div class="sign-in-container">
    <div class="sign-in-header">
      Register
    </div>
    <form id="sign-up-form" method="post">
      <md-outlined-text-field class="input-field" label="Name" id="name-field" name="name" required
        oninvalid="setCustomValidity('Enter a name')" oninput="setCustomValidity('')">
      </md-outlined-text-field>

      <md-outlined-text-field class="input-field" label="Email" id="email-field" name="email" type="email" required
        oninvalid="setCustomValidity('Enter an email address')" oninput="setCustomValidity('')">
      </md-outlined-text-field>

      <md-outlined-text-field class="input-field" id="password" name="password" label="Password" placeholder="Create your password"
        type="password" required
        oninvalid="setCustomValidity('Password must be a minimum of 6 characters. At least 1 uppercase letter, 1 lowercase letter, and 1 number. No spaces.')"
        oninput="setCustomValidity('')" pattern="^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$">
      </md-outlined-text-field>

      <md-outlined-text-field class="input-field" name="confirm-password" id="confirm-password" label="Confirm Password"
        placeholder="Confirm your password" type="password" required>
      </md-outlined-text-field>

      <p id="error-message"></p>

      <!-- Hidden inputs to hold the actual form values -->
      <input type="hidden" name="name" id="name-hidden">
      <input type="hidden" name="email" id="email-hidden">
      <input type="hidden" name="password" id="password-hidden">

      <div class="button-container">
        <md-filled-button type="submit">Sign up</md-filled-button>
      </div>
    </form>

    <p class="register-link">Already have an account? <a href="sign-in.php">Sign in</a></p>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#sign-up-form').on('submit', function(e) {
        e.preventDefault();

        // Get values from custom fields
        var name = $('#name-field').val();
        var email = $('#email-field').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm-password').val();

        // Check if passwords match
        if (password !== confirmPassword) {
          $('#error-message').text("Passwords do not match");
          return false;
        }
        $('#error-message').empty();

        // Set the values of hidden input fields
        $('#name-hidden').val(name);
        $('#email-hidden').val(email);
        $('#password-hidden').val(password);

        // Submit form data via AJAX
        $.ajax({
          url: '../actions/register_user_action.php',
          type: 'POST',
          data: $(this).serialize(), // Send form data
          success: function(data) {
            if (data) {
              $('#error-message').empty();
              $('#error-message').append(data);
            } else {
              window.location.href = '../views/sign-in.php';
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            // Handle any unexpected errors from the server
            $('#error-message').html('An error occurred while processing your request. Please try again.');
          }
        });
      });
    });
  </script>
</body>

</html>