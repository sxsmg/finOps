<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" id="login-form" action="{{ route('login') }}">
        @csrf
        
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>
    <button id="logout-button" style="display: none;">Logout</button>
    <div id="messageContainer" style="display: none;">
        <p id="message"></p>
    </div>
    <div id="ledger-container"></div>
    <script>
        $(document).ready(function() {
            $('#login-form').submit(function(event) {
                event.preventDefault();

                // Serialize the form data
                var formData = $(this).serialize();
                
                // Send a POST request to the login endpoint
                $.post('/api/login', formData, function(response) {
                    localStorage.setItem('auth_token', response.token);
                    // Set up headers with the access token
                    const accessToken = localStorage.getItem('auth_token');
                    const headers = {
                        'Authorization': `Bearer ${accessToken}`,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    };
                    $.ajaxSetup({
                        headers: headers
                    });
                
                    $.get('/api/ledger', function(data) {
                        $.ajaxSetup({
                            headers: headers
                        });
                        // Process data and update the ledger page
                        $('#ledger-container').html(data);
                        // Update the ledger page with received data
                        // ...
                    }).fail(function(xhr, status, error) {
                            // Handle any error during the API request
                            console.error('API Error:', error);
                        });
                    
                    // Show logout button and hide login form
                    $('#login-form').hide();
                    $('#logout-button').show();
                   
                })
                .fail(function(xhr, status, error) {
                    // Handle failed login request (display error message, etc.)
                    console.error('Login Error:', error);
                    $('#message').text('Login failed. Please check your credentials and try again.');
                    $('#messageContainer').show();
                });
            });

            $('#logout-button').click(function() {
                // Remove the token from localStorage
                localStorage.removeItem('auth_token');

                // Hide the logout button and show the login form
                $('#logout-button').hide();
                $('#login-form').show();

                // Clear any messages
                $('#message').empty();
                $('#messageContainer').hide();
            });
        });
    </script>

</body>
</html>
