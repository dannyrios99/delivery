<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags Circl -->

    <!-- Title -->
    <title>iniciar sesión | Punto Multiple</title>
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
    {{-- icono --}}
    <link rel="icon" href="{{ asset('assets/images/LogoIco.png') }}" type="image/x-icon">
    <!-- Theme Styles -->
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Incluir Bootstrap CSS -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .form-control:focus {
            color: #212529;
            background-color: #fff;
            border-color: #ffaf0fc9;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgb(255 175 15 / 25%);
        }

        .form-check-input:checked {
            background-color: #ffaf0f;
            border-color: #ffaf0f;
        }

        .form-check-input:focus {
            border-color: #ffaf0f;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(255 175 15 / 20%);
        }


        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f5f5f5;
            overflow: hidden;
        }
        .background-container {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
        }
        .background-image {
            width: 70%;
            height: 100%;
            background: url('../assets/images/login.jpg') no-repeat center center;
            background-size: cover;
            clip-path: polygon(0% 0%, 100% 0%, 85% 100%, 0% 100%);
        }
        .background-color {
            width: 50%;
            height: 100%;
            background-color: #f5f5f5;
        }
        .login-container {
            display: flex;
            width: 900px;
            background: white;
            border-radius: 20px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            padding: 10px;
            z-index: 2;
        }
        .login-image-wrapper {
            width: 50%;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        .login-image {
            width: 100%;
            height: 100%;
            background: url('../assets/images/login.jpg') no-repeat center center;
            background-size: cover;
            clip-path: polygon(0% 0%, 100% 0%, 85% 100%, 0% 100%);
        }
        .login-form {
            width: 50%;
            padding: 50px;
            text-align: center;
        }
        .login-form h3 {
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-login {
            background-color: #ff4d4d;
            border: none;
            border-radius: 10px;
            color: white;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-login:hover {
            background-color: #e63c3c;
        }
    </style>

</head>

<body class="login-page">
    <div class="background-container" data-aos="fade-right">
        <div class="background-image"></div>
        <div class="background-color"></div>
    </div>
    <div class="login-container" data-aos="fade-right">
        <div class="login-image-wrapper">
            <div class="login-image"></div>
        </div>
        <div class="login-form">
            <div class="authent-logo">
                <a href="{{ url('/') }}"><img src="{{ asset('assets/images/LogoIco.png') }}"
                        alt="" style="width: 34% !important;"></a>
            </div>
            <h3>Iniciar Sesión</h3>
            <p>Bienvenido de nuevo, ingresa tus datos</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInput"
                            placeholder="name@example.com" name="username">
                        <label for="floatingInput">Nombre Usuario</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword"
                            placeholder="Contraseña" name="password" required>
                        <label for="floatingPassword">Contraseña</label>
                    </div>
                </div>
                <div class="mb-3 form-check" style="text-align: left; align-items: center;">
                    <input type="checkbox" class="form-check-input" id="showPasswordCheck">
                    <label class="form-check-label" for="showPasswordCheck">Mostrar Contraseña</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-info m-b-xs"
                        style="background-color: #ffaf0f; border-color: #ffaf0f; color: #fff;">
                        Iniciar Sesión
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!--Modal-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @if (Session::has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                text: "{{ Session::get('error') }}",
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        </script>
    @endif

    <script>
        let timeout;

        function resetTimer() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                location.reload();
            }, 60000); // 30 segundos
        }

        // Eventos que reinician el temporizador
        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onclick = resetTimer;
        document.onscroll = resetTimer;
    </script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 200
        });
    </script>
</body>

</html>
<style>
    /* Estilos para el contenedor del loader */
    /* Estilos para el contenedor del loader */
    .loader-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        /* Esto centrará el loader verticalmente */
        background-color: rgb(255, 255, 255);
        /* Fondo semitransparente */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 2000;
        /* Coloca el loader sobre todo el contenido */
    }

    /* Estilos para la imagen */
    .loader1 {
        width: 100px;
        /* Ajusta el tamaño de la imagen según tus necesidades */
        height: 100px;
        animation: breathe 2s ease-in-out infinite;
    }

    @keyframes breathe {
        0% {
            transform: scale(0.7);
        }

        50% {
            transform: scale(1.2);
        }

        /* Cambia el tamaño a 120% en la mitad del ciclo */
        100% {
            transform: scale(0.7);
        }
    }

    /* Estilos para ocultar el loader cuando no esté en uso */
    .loader-container.hidden {
        display: none;
    }
</style>
{{-- <script>
    // Esperar a que la página se cargue completamente
    window.addEventListener("load", function() {
        // Ocultar el loader
        document.querySelector(".loader-container").classList.add("hidden");
    });
</script> --}}
<script>
    document.getElementById('showPasswordCheck').addEventListener('change', function() {
        var passwordInput = document.getElementById('floatingPassword');
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
</script>