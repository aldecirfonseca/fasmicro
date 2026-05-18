<div class="row bg-primary text-white m-2">
    <div class="col-10 p-2">
        <h2>Trocar Senha</h2>
    </div>
    <div class="col-2 text-end p-2">
        <a href="/" class="btn btn-outline-info text-white">Voltar</a>
    </div>
</div>

<?= exibeAlerta() ?>

<div class="d-flex justify-content-center mt-4">
    <div style="width: 100%; max-width: 420px;">

        <p class="text-center text-muted mb-4">
            Alterando senha de <strong><?= htmlspecialchars($_SESSION['userNome'] ?? '') ?></strong>
        </p>

        <form method="POST" action="<?= baseUrl() ?>Login/atualizarSenha">

            <div class="mb-3">
                <label for="senhaAtual">Senha Atual</label>
                <input
                    type="password"
                    class="form-control"
                    name="senhaAtual"
                    id="senhaAtual"
                    placeholder="Digite sua senha atual"
                    maxlength="20"
                    required
                    autofocus>
                <?= setMsgFilderError('senhaAtual') ?>
            </div>

            <div class="mb-3">
                <label for="novaSenha">Nova Senha</label>
                <input
                    type="password"
                    class="form-control"
                    name="novaSenha"
                    id="novaSenha"
                    placeholder="Mínimo 8 caracteres"
                    maxlength="100"
                    required>
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

            <div class="mt-4 d-flex gap-2">
                <a href="/" class="btn btn-outline-secondary flex-fill">Cancelar</a>
                <button type="submit" class="btn btn-primary flex-fill">Alterar Senha</button>
            </div>

        </form>

    </div>
</div>
