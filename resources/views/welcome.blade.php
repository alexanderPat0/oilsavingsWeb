<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome to Oilsavings</title>
    @vite(['resources/css/welcomeScreen.css', 'resources/js/app.js'])
    <!-- CSS de SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- JS de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body class="antialiased body-background">
    <div class="header-container">
        <div class="logo"><img src="{{ asset('imgs/oilSavingsIcon/oilsavingsIcon.png') }}" alt="Gasolineras en España">
        </div>
        <h1 class="main-title">Welcome to Oilsavings</h1>


        <!-- Botones de Login y Register -->
        <div class="authentication-buttons">
            <button id="loginBtn" class="auth-button login">Login</button>
            <button id="registerBtn" class="auth-button register">Register</button>
        </div>
    </div>


    </div>

    <div class="content-container max-width-setter center-align">
        <section class="info-section">
            <div class="info-card">
                <!-- <img src="{{ asset('imgs/icon1.png') }}" alt="Feature Icon" class="feature-icon" /> -->
                <h2 class="section-title">Why Choose Oilsavings?</h2>
                <p class="info-text">
                    Explore the extensive benefits of Oilsavings, your premier partner in fuel management. Our platform
                    not only simplifies tracking and managing your fuel expenditures but also provides real-time updates
                    and personalized alerts tailored to your preferences and usage patterns. Whether you are an
                    individual looking to cut costs or a business aiming to optimize operational efficiency, Oilsavings
                    offers customized solutions to meet your needs. Save significantly with each fill-up, enjoy
                    exclusive deals, and stay informed with the latest fuel price trends directly through our
                    user-friendly app. Start making smarter fuel choices with Oilsavings today!
                </p>

            </div>
            <div class="info-card">
                <h2 class="section-title">Our Promise</h2>
                <p class="info-text">
                    Our commitment extends beyond just providing services; we are dedicated to delivering
                    excellence. By choosing us, you gain access to top-quality services that comprehensively cater to
                    all your fuel needs. We ensure that you always receive the best possible rates and have access to a
                    wide network of service stations. Our platform is designed to provide you with detailed reviews and
                    ratings, helping you make informed decisions based on reliable user feedback. Join Oilsavings today
                    to experience a seamless and enriched fuel purchasing journey, benefit from our expert customer
                    support, and leverage the full potential of our innovative fuel management system.
                </p>
            </div>
        </section>
    </div>

    <script>
        // Escuchador para el botón de Login
        document.getElementById("loginBtn").addEventListener("click", function () {
            Swal.fire({
                title: "Login",
                html: `
                <input type="email" id="email" class="swal2-input" placeholder="Email">
                <input type="password" id="password" class="swal2-input" placeholder="Password">`,
                confirmButtonText: "Login",
                focusConfirm: false,
                preConfirm: () => {
                    const email = document.getElementById("email").value;
                    const password = document.getElementById("password").value;

                    if (!email || !password) {
                        Swal.showValidationMessage(
                            "Please enter all fields correctly."
                        );
                        return false;
                    }
                    return { email: email, password: password };
                },
            }).then((result) => {
                if (!result.isConfirmed) return;

                const { email, password } = result.value;

                $.ajax({
                    url: '/admin/login',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        email: email,
                        password: password,
                    },
                    success: function (response) {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Welcome!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Login',
                            text: jqXHR.responseJSON.message || 'There was a problem while logging in.'
                        });
                    }
                });
            });
        });
        // Escuchador para el botón de Register
        document.getElementById('registerBtn').addEventListener('click', function () {
            Swal.fire({
                title: 'Register',
                html: `
            <input type="text" id="username" class="swal2-input" placeholder="Username">
            <input type="email" id="email" class="swal2-input" placeholder="Email">
            <input type="password" id="newPassword" class="swal2-input" placeholder="Password">
            <input type="password" id="password_confirmation" class="swal2-input" placeholder="Confirm Password">`,
                confirmButtonText: 'Sign Up',
                focusConfirm: false,
                preConfirm: () => {
                    const username = document.getElementById("username").value;
                    const email = document.getElementById("email").value;
                    const newPassword = document.getElementById("newPassword").value;
                    const password_confirmation = document.getElementById("password_confirmation").value;

                    if (!username || !email || !newPassword || newPassword !== password_confirmation) {
                        Swal.showValidationMessage('Please enter all fields correctly and ensure passwords match.');
                        return false;
                    }

                    return { username: username, email: email, password: newPassword, password_confirmation: password_confirmation };
                }
            }).then((result) => {
                if (!result.isConfirmed) return;

                const { username, email, password, password_confirmation } = result.value;

                $.ajax({
                    url: '/admin/register',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        name: username,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registered!',
                            text: 'Please verify your account by clicking on the link we have just emailed you!'
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Register',
                            text: jqXHR.responseJSON.message || 'There was a problem with your registration.'
                        });
                    }
                });
            });
        });


    </script>
</body>

</html>