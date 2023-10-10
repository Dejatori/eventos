    <nav class="navbar navbar-expand bg-light navigation-clean navbar-light">
        <div class="container"><a class="navbar-brand" href="#" style="font-family: 'Noto Serif Dogra', serif;font-size: 26px;font-weight: bold;">ADSO</a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item" style="margin: 0px 20px 0px 0px;"><a class="nav-link <?php if(strpos($url, '/eventos/index.php') !== false) echo 'active'; ?>" href="index.php" style="font-family: 'Noto Serif Dogra', serif;font-size: 16px;font-weight: bold;">Inicio</a></li>
                    <li class="nav-item" style="margin: 0px 20px 0px 0px;"><a class="nav-link <?php if(strpos($url, '/eventos/novedades.php') !== false) echo 'active'; ?>" href="novedades.php" style="font-family: 'Noto Serif Dogra', serif;font-size: 16px;font-weight: bold;">Novedades</a></li>
                    <li class="nav-item" style="margin: 0px 20px 0px 0px;"><a class="btn btn-primary ms-auto <?php if(strpos($url, '/eventos/iniciar-sesion.php') !== false) echo 'active'; ?>" role="button" href="iniciar-sesion.php" style="font-family: 'Noto Serif Dogra', serif;font-size: 16px;">Iniciar sesión</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-auto <?php if(strpos($url, '/eventos/registrarse.php') !== false) echo 'active'; ?>" role="button" href="registrarse.php" style="font-family: 'Noto Serif Dogra', serif;font-size: 16px;">Registrarse</a></li>
                </ul>
            </div>
        </div>
    </nav>