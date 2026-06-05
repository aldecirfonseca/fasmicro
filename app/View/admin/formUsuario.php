<?= formTitulo($titulo) ?>

<div class="m-3">
    <form method="POST" action="/Usuario/<?= $action ?>">

        <?= csrfField() ?>

        <input type="hidden" name="id" id="id" value="<?= setValue('id', 0) ?>">

        <div class="row">
            <div class="col-9">
                <label for="nome">Nome</label>
                <input
                    type="text"
                    class="form-control"
                    name="nome"
                    id="nome"
                    placeholder="Nome do usuário"
                    maxlength="60"
                    value="<?= setValue('nome') ?>"
                    required
                    autofocus>
                <?= setMsgFilderError('nome') ?>
            </div>
            <div class="col-3">
                <label for="statusRegistro">Status</label>
                <select class="form-control" name="statusRegistro" id="statusRegistro">
                    <option value="">Selecione...</option>
                    <?php foreach ($aStatus as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $key == setValue('statusRegistro', 1) ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= setMsgFilderError('statusRegistro') ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-8">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    class="form-control"
                    name="email"
                    id="email"
                    placeholder="E-mail do usuário"
                    maxlength="150"
                    value="<?= setValue('email') ?>"
                    required>
                <?= setMsgFilderError('email') ?>
            </div>
            <div class="col-4">
                <label for="nivel">Nível</label>
                <select class="form-control" name="nivel" id="nivel">
                    <option value="">Selecione...</option>
                    <?php foreach ($aNiveis as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $key == setValue('nivel') ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= setMsgFilderError('nivel') ?>
            </div>
        </div>

        <?php if ($action === 'insert' || $action === 'update'): ?>
        <div class="row mt-3">
            <div class="col-5">
                <label for="senha">
                    Senha
                    <?php if ($action === 'update'): ?>
                        <small class="text-muted">(deixe em branco para manter a atual)</small>
                    <?php endif; ?>
                </label>
                <input
                    type="password"
                    class="form-control"
                    name="senha"
                    id="senha"
                    placeholder="Senha"
                    maxlength="100"
                    <?= $action === 'insert' ? 'required' : '' ?>>
                <?= setMsgFilderError('senha') ?>
            </div>
            <div class="col-5">
                <label for="confirmarSenha">Confirmar Senha</label>
                <input
                    type="password"
                    class="form-control"
                    name="confirmarSenha"
                    id="confirmarSenha"
                    placeholder="Repita a senha"
                    maxlength="100"
                    <?= $action === 'insert' ? 'required' : '' ?>>
                <?= setMsgFilderError('confirmarSenha') ?>
            </div>
        </div>

        <?= jsPasswordStrength('senha', 'confirmarSenha', $action === 'insert') ?>
        <?php endif; ?>

        <div class="row mt-3">
            <div class="col-12">
                <?= formButton() ?>
            </div>
        </div>

    </form>
</div>
