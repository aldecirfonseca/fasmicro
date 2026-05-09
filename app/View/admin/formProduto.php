<?= jsQuillEditor('complemento_editor', 'complemento', setValue('complemento')) ?>

<?= formTitulo($titulo) ?>

<div class="m-3">
    <form method="POST" action="/produto/<?= $action ?>">

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
                    placeholder="Descrição do Produto"
                    maxlength="60"
                    value="<?= setValue('descricao') ?>"
                    required
                    autofocus>
                <?= setMsgFilderError('descricao') ?>
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
                <?= setMsgFilderError('statusRegistro') ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <label for="complemento_editor">Complemento</label>
                <div id="complemento_editor" style="height: 150px;"><?= setValue('complemento') ?></div>
                <input type="hidden" name="complemento" id="complemento">
                <?= setMsgFilderError('complemento') ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6">
                <label for="categoria_id">Categoria</label>
                <select class="form-control" name="categoria_id" id="categoria_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($aCategorias as $cat): ?>
                        <?php $catSelected = $cat['id'] == setValue('categoria_id'); ?>
                        <option value="<?= $cat['id'] ?>" <?= $catSelected ? "selected" : '' ?>><?= $cat['descricao'] ?></option>
                    <?php endforeach; ?>
                </select>
                <?= setMsgFilderError('categoria_id') ?>
            </div>
            <div class="col-6">
                <label for="unidademedida_id">Unidade de Medida</label>
                <select class="form-control" name="unidademedida_id" id="unidademedida_id" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($aUnidadesMedida as $um): ?>
                        <?php $umSelected = $um['id'] == setValue('unidademedida_id'); ?>
                        <option value="<?= $um['id'] ?>" <?= $umSelected ? "selected" : '' ?>>
                            <?= $um['sigla'] ?> - <?= $um['descricao'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= setMsgFilderError('unidademedida_id') ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-4">
                <label for="saldoEstoque_display">Saldo em Estoque</label>
                <input
                    type="text"
                    class="form-control"
                    id="saldoEstoque_display"
                    placeholder="0,000"
                    required>
                <input type="hidden" name="saldoEstoque" id="saldoEstoque" value="<?= setValue('saldoEstoque', '0.000') ?>">
                <?= setMsgFilderError('saldoEstoque') ?>
            </div>
            <div class="col-4">
                <label for="precoVenda_display">Preço de Venda</label>
                <input
                    type="text"
                    class="form-control"
                    id="precoVenda_display"
                    placeholder="R$ 0,00"
                    required>
                <input type="hidden" name="precoVenda" id="precoVenda" value="<?= setValue('precoVenda', '0.00') ?>">
                <?= setMsgFilderError('precoVenda') ?>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <?= formButton() ?>
            </div>
        </div>

    </form>
</div>

<?= jsCleaveNumeral('saldoEstoque_display', 'saldoEstoque', 3) ?>
<?= jsCleaveNumeral('precoVenda_display', 'precoVenda', 2, 'R$ ') ?>
<?= jsFormHandler() ?>
