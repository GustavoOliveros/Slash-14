<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark sticky-top navbar-expand-lg">
    <div class="container-fluid max">
        <a class="navbar-brand fw-bold" href="">SLASH</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex align-items-end justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#inicioSesion">Iniciar sesión</button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal de inicio de sesión -->
<div class="modal fade" id="inicioSesion" tabindex="-1" aria-labelledby="inicioSesion" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="fw-5 text-center m-3">Member Login</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="TP2_EJ3_Resultado.php" method="post" class="needs-validation" name="form" id="form" novalidate>
                    <div class="row">
                        <div class="col-12 col-md-9 mx-auto">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </span>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required />
                                <div class="invalid-feedback" id="feedback-username">
                                    Obligatorio
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-9 mx-auto">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                                    </svg>
                                </span>
                                <input type="password" name="password" id="password" placeholder="Password" class="form-control" pattern="^(?=.*\d).{8,16}$" required />
                                <div class="invalid-feedback" id="feedback-password">
                                    Contraseña inválida
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary mx-auto">
            </div>
            </form>
        </div>
    </div>
</div>