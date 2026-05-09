<div class="login-split">

    <!-- Painel esquerdo: branding -->
    <div class="login-brand-panel">

        <div class="brand-logo">Fas<span>Micro</span></div>

        <h1 class="brand-headline">Framework PHP<br>Minimalista e<br>Poderoso</h1>

        <p class="brand-description">
            FasMicro é um micro-framework PHP desenvolvido para ensinar
            os fundamentos do padrão MVC de forma prática e objetiva,
            com roteamento, templates, validação e muito mais.
        </p>

        <div class="brand-tags">
            <span class="brand-tag">PHP 8+</span>
            <span class="brand-tag">MVC</span>
            <span class="brand-tag">Open Source</span>
            <span class="brand-tag">UI Design</span>
        </div>

    </div>

    <!-- Painel direito: formulário -->
    <div class="login-form-panel">

        <div class="login-card">

            <h2>Login</h2>
            <p class="login-subtitle">Preencha os campos abaixo com suas dados de acesso</p>

            <?= exibeAlerta() ?>

            <form method="POST" action="<?= baseUrl() ?>Login/signIn">

                <div class="mb-3">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        class="form-control"
                        name="email"
                        id="email"
                        placeholder="Digite seu e-mail"
                        value="<?= setValue('email') ?>"
                        required
                        autofocus>
                    <?= setMsgFilderError('email') ?>
                </div>

                <div class="mb-4">
                    <label for="senha">Senha</label>
                    <input
                        type="password"
                        class="form-control"
                        name="senha"
                        id="senha"
                        placeholder="Digite sua senha"
                        required>
                    <?= setMsgFilderError('senha') ?>
                </div>

                <div class="mb-2">
                    <button type="submit" class="btn btn-login">Entrar</button>
                </div>

                <div class="login-footer-links">
                    <a href="<?= baseUrl() ?>">← Voltar</a>
                    <a href="<?= baseUrl() ?>Login/esqueciASenha">Esqueci minha senha</a>
                </div>

            </form>

        </div>

    </div>

</div>
