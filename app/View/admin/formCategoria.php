<?=  formTitulo('Categoria') ?>

<?php
use Core\Library\Session;

var_dump(Session::get('formErrors'), Session::get('formInputs'));
?>

<div class="m-3">
    <form method="POST" action="/categoria/<?= $action ?>">

        <input
            type="hidden"
            name="id"
            id="id"
            value="<?= setValue('id', 0) ?>">

        <div class="row">
            <div class="col-9">
                <label for="descricao">Descrição</label>
                <input
                    type="text"
                    class="form-control"
                    name="descricao"
                    id="descricao"
                    placeholder="Descrição da Categoria"
                    maxlength="50"
                    value="<?= setValue('descricao') ?>"
                    required
                    autofocus>
            </div>
            <div class="col-3">
                <label for="statusRegistro">Status</label>
                <select class="form-control" name="statusRegistro" id="statusRegistro">
                    <option value="">...</option>
                    <?php foreach ($aStatus as $key => $value): ?>
                        <?php $statusSelected = $key == setValue('statusRegistro'); ?>
                        <option value="<?= $key ?>" <?= $statusSelected ? "selected" : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <?= formButton() ?>
            </div>
        </div>

    </form>
</div>
