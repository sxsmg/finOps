<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h2>Registration</h2>
    <form id="registrationForm" action="{{ route('register') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Name">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="password_confirmation" placeholder="Confirm Password">
        <button type="submit">Register</button>
    </form>
    <div id="messageContainer" style="display: none;">
    Message: <span id="message"></span>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#registrationForm').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();
            $.post('/api/register', formData, function(response) {
                console.log(response);
                localStorage.setItem('auth_token', response.auth_token);
                $('#message').text(response.message);
                $('#messageContainer').show();
                // Redirect to login page
                window.location.href = '{{ route("login") }}'; // This generates the correct route URL            });
        })});

    </script>
    <a href="{{ route('login') }}">Go to Login Page</a>
</body>
</html>
