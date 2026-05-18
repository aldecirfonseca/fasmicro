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

            <h2>Redefinir Senha</h2>
            <p class="login-subtitle">Crie uma nova senha segura para sua conta.</p>

            <?= exibeAlerta() ?>

            <form method="POST" action="<?= baseUrl() ?>Login/salvarNovaSenha">

                <input type="hidden" name="chave" value="<?= htmlspecialchars(setValue('chave'), ENT_QUOTES) ?>">

                <div class="mb-3">
                    <label for="novaSenha">Nova Senha</label>
                    <input
                        type="password"
                        class="form-control"
                        name="novaSenha"
                        id="novaSenha"
                        placeholder="Mínimo 8 caracteres"
                        maxlength="100"
                        required
                        autofocus>
                    <?= setMsgFilderError('novaSenha') ?>
                </div>

                <div class="mb-3">
                    <label for="confirmacaoSenha">Confirmação da Nova Senha</label>
                    <input
                        type="password"
                        class="form-control"
                        name="confirmacaoSenha"
                        id="confirmacaoSenha"
                        placeholder="Repita a nova senha"
                        maxlength="100"
                        required>
                    <?= setMsgFilderError('confirmacaoSenha') ?>
                </div>

                <?= jsPasswordStrength('novaSenha', 'confirmacaoSenha', true) ?>

                <div class="mt-4 mb-2">
                    <button type="submit" class="btn btn-login">Redefinir Senha</button>
                </div>

                <div class="login-footer-links">
                    <a href="<?= baseUrl() ?>Login">← Voltar ao Login</a>
                </div>

            </form>

        </div>

    </div>

</div>
