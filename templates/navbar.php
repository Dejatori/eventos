<div id="wrapper">
    <nav class="navbar navbar-expand bg-light navigation-clean navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="inicio.php" style="font-family: 'Noto Serif Dogra', serif;font-size: 26px;font-weight: bold;">ADSO</a>
            <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"></button>
            <div class='collapse navbar-collapse' id='navcol-1'>
                <ul class='navbar-nav ms-auto'>
                    <li class='nav-item' style='margin: 0 20px 0 0;'>
                        <a class="nav-link <?php $url = $_SERVER['REQUEST_URI'];
                        if (str_contains($url, '/eventos/inicio.php') || $url == '/eventos/') echo 'active'; ?>" href='inicio.php' style="font-family: 'Noto Serif Dogra', serif; font-size: 16px; font-weight: bold;">Inicio</a>
                    </li>
                    <?php if (isset($_SESSION['usuario_id'])) { ?><li class="nav-item" style="margin: 0 20px 0 0;">
                        <a class="nav-link <?php if (str_contains($url, '/eventos/novedades.php')) echo 'active'; ?>" href="novedades.php" style="font-family: 'Noto Serif Dogra', serif; font-size: 16px; font-weight: bold;">Novedades</a>
                    </li><?php echo "\n"; } ?><?php if (empty($_SESSION['usuario_id'])) { ?><li class="nav-item" style="margin: 0 20px 0 0;">
                        <a class="btn btn-primary ms-auto <?php if (str_contains($url, '/eventos/iniciar-sesion.php')) echo 'active'; ?>" role="button" href="iniciar-sesion.php" style="font-family: 'Noto Serif Dogra', serif; font-size: 16px;">Iniciar sesión</a>
                    </li><?php echo "\n"; } ?>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-auto <?php if (str_contains($url, '/eventos/registrarse.php')) echo 'active'; ?>" role="button" href="registrarse.php" style="font-family: 'Noto Serif Dogra', serif; font-size: 16px;">Registrarse</a>
                    </li>
                    <?php if (isset($_SESSION['usuario_id'])) { ?><li class="nav-item" style="margin: 0 0 0 20px;">
                        <form method="POST">
                            <button type="submit" class="btn btn-primary border rounded ms-auto" name="cerrar_sesion">Cerrar sesión<i class="fas fa-sign-out-alt"></i></button>
                        </form>
                    </li>
                    <?php } echo "\n";  ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <div class="container-fluid">
