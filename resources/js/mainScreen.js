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
        const { email, password } = result.value;

        // Usar Firebase para autenticar al usuario
        firebase
            .auth()
            .signInWithEmailAndPassword(email, password)
            .then((userCredential) => {
                // Usuario autenticado
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Your work has been saved",
                    showConfirmButton: false,
                    timer: 1500,
                });
                setTimeout(() => {
                    window.location.href = "/users"; // Redirecciona a '/users' después de 2 segundos
                }, 2000); // Espera 2 segundos antes de redirigir
            })
            .catch((error) => {
                var errorCode = error.code;
                var errorMessage = error.message;
                Swal.showValidationMessage(`Login failed: ${errorMessage}`);
            });
    });
});

// Escuchador para el botón de Register
document.getElementById("registerBtn").addEventListener("click", function () {
    Swal.fire({
        title: "Register",
        html: `
                    <input type="text" id="username" class="swal2-input" placeholder="Username">
                    <input type="email" id="email" class="swal2-input" placeholder="Email">
                    <input type="password" id="newPassword" class="swal2-input" placeholder="Password">
                    <input type="password" id="password_confirmation" class="swal2-input" placeholder="Confirm Password">`,
        confirmButtonText: "Sign Up",
        focusConfirm: false,
        preConfirm: () => {
            const username = document.getElementById("username").value;
            const email = document.getElementById("email").value;
            const newPassword = document.getElementById("newPassword").value;
            const password_confirmation = document.getElementById(
                "password_confirmation"
            ).value;

            // Validar que los campos no están vacíos y que las contraseñas coinciden
            if (
                !username ||
                !email ||
                !newPassword ||
                newPassword !== password_confirmation
            ) {
                Swal.showValidationMessage(
                    "Please enter all fields correctly and ensure passwords match."
                );
                return false;
            }
            return {
                username: username,
                email: email,
                password: newPassword,
                password_confirmation: password_confirmation,
            };
        },
    }).then((result) => {
        console.log(result.value);

        console.log(result.value.username);
        console.log(result.value.email);

        const { username, email, password, password_confirmation } =
            result.value;
        // Realizar la solicitud AJAX para registrar el administrador
        return $.ajax({
            url: "/admin/register",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                name: username,
                email: email,
                password: password,
                password_confirmation: password_confirmation,
            },
        })
            .done(function (response) {
                Swal.fire(
                    "Registered!",
                    "Please verify your account by clicking on the link we have just emailed you!.",
                    "success"
                );
            })
            .fail(function (jqXHR, textStatus) {
                if (jqXHR.status === 409) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: jqXHR.responseJSON.message,
                    });
                } else {
                    Swal.showValidationMessage(`Request failed: ${textStatus}`);
                }
            });
    });
});
