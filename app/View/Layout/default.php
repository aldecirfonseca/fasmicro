<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= \Core\Library\Csrf::getToken() ?>">
    <title><?= $titulo ?></title>

    <link rel="stylesheet" href="/assests/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">FasMicro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/Home/contato">Contato</a>
                    </li>

                    <?php if (!isset($_SESSION['userId'])): ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/Login">Login</a>
                        </li>
                    
                    <?php else: ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $_SESSION["userNome"] ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/Login/signOut">Sair</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/Login/trocarSenha">Trocar Senha</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/UnidadeMedida">Unidade de Medida</a></li>
                                <li><a class="dropdown-item" href="/Categoria">Categoria</a></li>
                                <li><a class="dropdown-item" href="/Produto">Produto</a></li>

                                <?php if ((int)$_SESSION['userNivel'] <= 20): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/Usuario">Usuários</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <main class="m-1">
        <?= $content ?>
    </main>

    <script src="/assests/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
            new bootstrap.Tooltip(el);
        });
    </script>
    <script>
        document.querySelectorAll('select').forEach(function (el) {
            if (el.closest('.ql-toolbar')) return;
            new TomSelect(el, {
                allowEmptyOption: true,
                plugins: ['clear_button'],
            });
        });
    </script>

</body>

</html>