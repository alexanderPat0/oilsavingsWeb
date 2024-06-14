<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome to Oilsavings</title>
    <!-- CSS de SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- JS de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        /* General Styles */
        .body-background {
            background: url('https://imgs.search.brave.com/7_03ZMe2wbDuAY2Z3_36LkquMQ2s8lS16GjXW0jmX80/rs:fit:500:0:0/g:ce/aHR0cHM6Ly9zZWF0/anJ2YWxsZS5jb20v/d3AtY29udGVudC91/cGxvYWRzLzIwMjMv/MDQvZ2Fzb2xpbmVy/YS1ub2N0dXJuYS5q/cGc') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: 'Figtree', sans-serif;
            display: flex;
            flex-direction: column;
            /* Organiza el contenido verticalmente */
            align-items: center;
            /* Centra horizontalmente todo el contenido */
        }

        .header-container {
            text-align: center;
            padding: 20px;
            width: 100%;
            /* Asegura que el contenedor ocupe todo el ancho */
        }

        .logo img {
            height: 100px;
            /* Ajusta la altura del logo */
            width: auto;
        }

        .main-title {
            font-size: 2.5rem;
            color: black;
        }

        .content-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            /* Asegura que el contenedor ocupe todo el ancho */
        }

        .max-width-setter {
            max-width: 1200px;
            width: 100%;
            /* Utiliza todo el ancho disponible hasta 1200px */
        }

        .center-align {
            text-align: center;
        }

        .info-section {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            /* Distribuye el espacio uniformemente entre los elementos */
            align-items: flex-start;
            /* Alinea los elementos al inicio */
            flex-wrap: wrap;
            /* Permite envolver los elementos si es necesario */
            width: 100%;
            /* Asegura que la sección utilice todo el ancho disponible */
        }

        .info-card {
            flex: 1 1 45%;
            /* Flexibilidad y mínimo del 45% del contenedor */
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 10px;
            /* Añade margen para evitar la unión de tarjetas */
        }

        .section-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .info-text {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
            text-align: justify;
            max-width: 400px;
            /* Limita el ancho del texto para mejorar la legibilidad */
        }

        .authentication-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            width: 100%;
            /* Asegura que los botones estén centrados en la pantalla */
        }

        .auth-button {
            background-color: #ffffffb9;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .maybelater {
            margin-top: 30px;
        }

        .auth-button:hover {
            background-color: #4b4b4bc0;
        }

        @media (min-width: 768px) {
            .info-section {
                flex-direction: row;
                justify-content: space-between;
            }
        }
    </style>
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
        <div>
            <button id="maybelater" class="auth-button maybelater">Maybe later</button>
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
                        if (response.success && response.redirect_url) {
                            console.log("Setting sessionStorage:", response);
                            sessionStorage.setItem('justLoggedIn', 'true');
                            console.log("Redirecting to:", response.redirect_url);
                            window.location.href = response.redirect_url;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Login',
                            text: jqXHR.responseJSON.message || 'There was a problem while logging in.',
                            customClass: {
                                popup: 'swal-wide'  // Clase personalizada para controlar el tamaño
                            },
                            width: '80%', // Puedes ajustar este valor según necesidades
                            maxHeight: 600, // Máximo altura en píxeles
                            overflowY: 'auto' // Habilitar desplazamiento si es necesario


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
                            text: jqXHR.responseJSON.message || 'There was a problem with your registration.',
                            customClass: {
                                popup: 'swal-wide'
                            },
                            width: '80%',
                            maxHeight: 600,
                            overflowY: 'auto'
                        });
                    }
                });
            });
        });
        document.getElementById('maybelater').addEventListener('click', function () {
            window.location.href = '/reviews';
        });
    </script>
</body>
</html>