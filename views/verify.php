<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
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
    <div class="verify-container">
        <div class="verify-header">
            OTP Verification
        </div>
        <p>A code has been sent to <?=$_SESSION['email'];?></p>
        <form id="otp-form" method="post">
            <md-outlined-text-field class="input-field" id="otp-field" label="Enter OTP" type="number" maxlength=6 required></md-outlined-text-field>
            <p id="error-message"></p>
            <div class="button-container">
                <md-filled-button type="submit">Verify OTP</md-filled-button>
            </div>
            <div class="button-container">
                <md-outlined-button id="resend-button" disabled>Resend OTP</md-outlined-button>
            </div>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            let resendTimer = 90; // 1.5 minutes in seconds

            function updateTimer() {
                const minutes = Math.floor(resendTimer / 60);
                const seconds = resendTimer % 60;
                $('#resend-button').text(`Resend in ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);

                if (resendTimer > 0) {
                    resendTimer--;
                    setTimeout(updateTimer, 1000);
                } else {
                    $('#resend-button').prop('disabled', false).text('Resend OTP');
                }
            }

            updateTimer();

            $('#otp-form').on('submit', function(e) {
                e.preventDefault();
                const otp = $('#otp-field').val();

                $.ajax({
                    url: '../actions/verify_otp.php',
                    type: 'POST',
                    data: {
                        otp: otp
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#error-message').html('<span style="color: green;">' + response.message + '</span>');
                            setTimeout(function() {
                                window.location.href = 'success.php'; 
                            }, 2000);
                        } else {
                            $('#error-message').html(response.message);
                        }
                    },
                    error: function() {
                        $('#error-message').html('An error occurred. Please try again.');
                    }
                });
            });

            $('#resend-button').on('click', function(e) {
                e.preventDefault();
                if ($(this).prop('disabled')) return;

                $.ajax({
                    url: '../actions/resend_otp.php',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#error-message').html(response.message);
                            resendTimer = 180;
                            updateTimer();
                            $('#resend-button').prop('disabled', true);
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