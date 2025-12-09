<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Theme Styles -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    
</head>
<script>
  // Calcula altura real del header + su margen inferior y el padding-top del contenido
  (function syncOffsets(){
    const header = document.querySelector('.page-header');
    const content = document.querySelector('.page-content');

    function update(){
      const h = header ? header.offsetHeight : 64;
      const mb = header ? parseFloat(getComputedStyle(header).marginBottom) || 0 : 0;
      const pt = content ? parseFloat(getComputedStyle(content).paddingTop) || 0 : 16;

      document.documentElement.style.setProperty('--header-h', (h + mb) + 'px');
      document.documentElement.style.setProperty('--content-gap', pt + 'px');
    }

    window.addEventListener('load', update);
    window.addEventListener('resize', update);
  })();
</script>


<div class="page-header" data-aos="zoom-in-down">
    <nav class="navbar navbar-expand-lg d-flex justify-content-between" >
        <div class="" id="navbarNav">
            <ul class="navbar-nav" id="leftNav">
                <li class="nav-item">
                    <a class="nav-link" id="sidebar-toggle" href="#"><i data-feather="arrow-left"></i></a>
                </li>
            </ul>
        </div>
        <div class="logo">
            <a class="navbar-brand" href=""></a>
        </div>
        <div class="" id="headerNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <?php use App\Models\User; ?> 
                    <a class="nav-link profile-dropdown" href="#" id="profileDropDown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="display: flex; align-items: center;"> @auth @php
                            if (Auth::check()) {
                                $usuario = User::find(Auth::id());
                                $nombreCompleto = $usuario->name;
                                $palabras = explode(' ', $nombreCompleto);
                                $nombreAbreviado = count($palabras) > 1 ? $palabras[0] . ' ' . $palabras[1] : $palabras[0];
                            } else {
                                $nombreAbreviado = 'Invitado';
                            }
                        @endphp <span
                                style="color: #fff; margin-right: 5px;">{{ $nombreAbreviado }}</span>
                        @else
                        <p>No estás autenticado.</p> @endauth <div
                            style="background-color: #ffb900; width: 50px; height: 50px; border: 3px solid white; border-radius: 50%; font-size: 19px; color: white; display: flex; align-items: center; justify-content: center; text-transform: capitalize; letter-spacing: -1px;">
                            <span style="margin-top:-1px;"> {{ substr($nombreAbreviado, 0, 1) }} </span> </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                        <a class="dropdown-item" href=""><i data-feather="user-plus"></i>Perfil</a>
                        <form method="POST" action="{{ route('logout') }}" style="padding: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item" href="#"><i data-feather="log-out"
                                    style="    width: 20px;
                            margin-right: 8px;"></i>Salir</button>

                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>

<div class="page-sidebar intro">              <!-- mantiene tu toggle (.is-hidden) -->
        <ul class="list-unstyled accordion-menu">
            @php
                $user = Auth::user();
            @endphp
            @if ($user && in_array($user->role, ['admin', 'mercadeo']))
            <li class="sidebar-title">
                INICIO
            </li>
            
            <li @yield('dashboard')>
                <a href="{{ route('dashboard') }}"><i data-feather="home"></i>Dashboard</a>
            </li>

            <li class="sidebar-title">
                GESTION ADMINISTRATIVA
            </li>

            <li @yield('usuarios')>
                <a href="{{ route('usuarios.index') }}"><i data-lucide="user-round-plus"></i>Usuarios</a>
            </li>

            <li @yield('sedes')>
                <a href="{{ route('sedes.index') }}"><i data-lucide="store"></i>Sedes</a>
            </li>
            @endif
    
            @if ($user && in_array($user->role, ['admin', 'mercadeo']))
            <li class="sidebar-title">
                GESTION DE ACTIVOS
            </li>

            <li @yield('categorias')>
                <a href="{{ route('categorias.index') }}"><i data-lucide="folder-tree"></i>Categorias</a>
            </li>

            <li @yield('activos')>
                <a href="{{ route('activos.index') }}"><i data-lucide="package-plus"></i>Activos</a>
            </li>

            <li @yield('stock')>
                <a href="{{ route('stock.index') }}"><i data-lucide="box"></i>Stock</a>
            </li>

            @endif   
        </ul>
    </div>

<style>
    .dropdown-menu .dropdown-item {
        display: flex;
        align-items: center;
        gap: 6px; /* espacio entre ícono y texto */
        padding: 10px 15px;
        font-size: 14px;
    }
    .dropdown-item i {
        width: 20px;
        height: 20px;
        stroke-width: 2;
    }

    .dropdown-item.active,
    .dropdown-item:active {
        color: #e06d2a !important;
        background: 0 0;
    }

    /* Your existing toggle (keep as-is) */
    .page-sidebar{
      transition: transform .3s ease, opacity .3s ease;
      will-change: transform, opacity;
    }
    .page-sidebar.is-hidden{
      transform: translateX(-105%) !important;
      opacity: 0 !important;
      pointer-events: none;
    }
    
    /* === Exact AOS zoom-in-right replica ===
       - starts slightly left (-100px) and small (scale .6)
       - fades/zooms into place
       - 400ms, easing 'ease' (AOS default)
    */
    @keyframes aosZoomInRight {
      from {
        opacity: 0;
        transform: translate3d(-100px,0,0) scale(0.6);
      }
      to {
        opacity: 1;
        transform: translate3d(0,0,0) scale(1);
      }
    }
    
    /* Run once on first paint (desktop example) */
    .page-sidebar.intro:not(.is-hidden){
      animation: aosZoomInRight 0.4s ease both;
    }
    
    /* Respect users that prefer reduced motion */
    @media (prefers-reduced-motion: reduce){
      .page-sidebar{ transition: none; }
      .page-sidebar.intro{ animation: none; }
    }


</style>

<script>
    var time;
    var logoutUrl = "{{ route('login') }}";// Genera la URL dinámica desde Laravel
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;

    function logout() {
        location.href = logoutUrl; // Usa la URL generada
    }

    function resetTimer() {
        clearTimeout(time);
        time = setTimeout(logout, 3600000); // 1 hora en milisegundos (3600000 ms)
    }
</script>

<script>
  // Calcular altura real del header para el offset del sidebar
  (function(){
    const header = document.querySelector('.page-header');
    function setH(){
      const h = header ? header.offsetHeight : 64;
      document.documentElement.style.setProperty('--header-h', h + 'px');
    }
    setH(); window.addEventListener('resize', setH);
  })();

  const mql = window.matchMedia('(max-width: 991.98px)');
  const sidebar = document.querySelector('.page-sidebar');
  const btn = document.getElementById('sidebar-toggle');

  function setDefault(){
    if (mql.matches){
      document.body.classList.remove('sidebar-open');
      sidebar.classList.add('is-hidden');   // cerrado en móvil
    }else{
      document.body.classList.remove('sidebar-open');
      sidebar.classList.remove('is-hidden'); // abierto en desktop
    }
  }
  window.addEventListener('load', setDefault);
  mql.addEventListener('change', setDefault);

  btn?.addEventListener('click', e => {
    e.preventDefault();
    if (mql.matches){
      // móvil: push del contenido
      const open = document.body.classList.toggle('sidebar-open');
      sidebar.classList.toggle('is-hidden', !open);
    }else{
      // desktop: margen sí/no
      const hidden = sidebar.classList.toggle('is-hidden');
      document.body.classList.toggle('sidebar-open', !hidden);
    }

    // Cambiar icono feather
    const i = e.currentTarget.querySelector('i[data-feather]');
    if (i && window.feather){
      i.setAttribute('data-feather',
        (mql.matches ? (document.body.classList.contains('sidebar-open') ? 'arrow-left' : 'arrow-right')
                     : (sidebar.classList.contains('is-hidden') ? 'arrow-right' : 'arrow-left'))
      );
      feather.replace();
    }
  });
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 500, offset: 0, once: true });

  // Cuando AOS termine de mostrar el sidebar, le quitamos lo de AOS
  document.addEventListener('aos:in', function (e) {
    const el = e.detail;
    if (!el || !el.classList.contains('page-sidebar')) return;

    // espera un pelín más que la duración para asegurar que terminó
    setTimeout(() => {
      el.removeAttribute('data-aos');
      el.classList.remove('aos-init','aos-animate');
      // MUY importante: limpiar inline styles que mete AOS
      el.style.transform = '';
      el.style.opacity = '';
    }, 520);
  });
</script>


<script>
  lucide.createIcons();
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Javascripts -->
<script src="{{ asset('assets/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
{{--     <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script> --}}
<script src="https://unpkg.com/feather-icons"></script>
<script src="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/main.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>