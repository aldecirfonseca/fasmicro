<?= jsQuillEditor('complemento_editor', 'complemento', setValue('complemento')) ?>

<?= formTitulo($titulo) ?>

<div class="m-3">
    <form method="POST" action="/produto/<?= $action ?>" enctype="multipart/form-data">

        <?= csrfField() ?>

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
                <div id="complemento_editor" style="height: 150px;"></div>
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

        <?php if ($action === 'insert'): ?>
            <div class="row mt-3">
                <div class="col-12">
                    <label for="anexos_form">
                        Anexos
                        <small class="text-muted fw-normal">
                            — opcional, múltiplos arquivos permitidos (máx. <?= FILE_MAXSIZE ?>MB cada)
                        </small>
                    </label>
                    <input type="file"
                        class="form-control"
                        name="anexos[]"
                        id="anexos_form"
                        multiple>
                </div>
            </div>
        <?php endif; ?>

        <div class="row mt-3">
            <div class="col-12">
                <?= formButton() ?>
            </div>
        </div>

    </form>
</div>

<?php if ($produto_id > 0): ?>
    <div class="m-3">
        <div class="row bg-secondary text-white p-2 mx-0 mb-3">
            <div class="col-12">
                <h5 class="mb-0">Anexos</h5>
            </div>
        </div>

        <?php if ($action !== 'view'): ?>
        <form method="POST"
            action="/Produtoanexo/upload/insert/<?= $produto_id ?>"
            enctype="multipart/form-data"
            class="mb-3">
            <?= csrfField() ?>
            <div class="row align-items-end">
                <div class="col-8">
                    <label for="anexos">Selecione os arquivos (máx. <?= FILE_MAXSIZE ?>MB cada)</label>
                    <input type="file"
                        class="form-control"
                        name="anexos[]"
                        id="anexos"
                        multiple>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-upload"></i> Enviar Arquivo(s)
                    </button>
                </div>
            </div>
        </form>
        <?php endif; ?>

        <?php if (!empty($aAnexos)):
            $_files = new \Core\Library\Files();
            $_pasta = 'produtos' . DIRECTORY_SEPARATOR . $produto_id;
            $_icones = [
                'pdf'  => '📄', 'doc'  => '📝', 'docx' => '📝',
                'xls'  => '📊', 'xlsx' => '📊', 'ppt'  => '📋', 'pptx' => '📋',
                'jpg'  => '🖼', 'jpeg' => '🖼', 'png'  => '🖼', 'gif'  => '🖼',
                'webp' => '🖼', 'svg'  => '🖼', 'zip'  => '🗜', 'rar'  => '🗜',
                'txt'  => '📃', 'csv'  => '📃', 'mp4'  => '🎬', 'mp3'  => '🎵',
            ];
        ?>
        <table class="table table-sm table-bordered table-hover" id="tblAnexos">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th style="width:70px;" class="text-center">Prévia</th>
                    <th>Arquivo</th>
                    <th class="text-center" style="width:120px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $_extsImagem = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                    foreach ($aAnexos as $idx => $anexo):
                        $nomeOriginal  = \Core\Library\Files::getNomeOriginal($anexo['nomearquivo']);
                        $ext           = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
                        $icone         = $_icones[$ext] ?? '📎';
                        $urlVisualizar = '/Produtoanexo/download/view/' . $anexo['id'];
                        $isImagem      = in_array($ext, $_extsImagem);
                ?>
                <tr>
                    <td class="align-middle"><?= $idx + 1 ?></td>
                    <td class="text-center align-middle">
                        <?php if ($isImagem): ?>
                        <img src="<?= $urlVisualizar ?>"
                             alt="<?= htmlspecialchars($nomeOriginal) ?>"
                             style="width:50px;height:50px;object-fit:cover;cursor:pointer;border-radius:4px;"
                             onclick="abrirImagemModal('<?= $urlVisualizar ?>', '<?= htmlspecialchars($nomeOriginal, ENT_QUOTES) ?>')"
                             title="Clique para ampliar">
                        <?php else: ?>
                        <span style="font-size:1.5rem;" title="<?= strtoupper($ext) ?>"><?= $icone ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle"><?= htmlspecialchars($nomeOriginal) ?></td>
                    <td class="text-center align-middle text-nowrap">
                        <a href="<?= $urlVisualizar ?>"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm"
                           title="Visualizar">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="<?= $urlVisualizar ?>?dl=1"
                           class="btn btn-outline-secondary btn-sm"
                           title="Download">
                            <i class="bi bi-download"></i>
                        </a>
                        <?php if ($action !== 'view'): ?>
                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                title="Excluir"
                                onclick="confirmarExclusao(<?= $anexo['id'] ?>, <?= $produto_id ?>)">
                            <i class="bi bi-trash"></i>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-muted">Nenhum anexo cadastrado.</p>
        <?php endif; ?>
    </div>

    <form id="formExcluirAnexo" method="POST" action="">
        <?= csrfField() ?>
        <input type="hidden" name="produto_id" id="excluir_produto_id" value="">
    </form>

    <div class="modal fade" id="modalImagem" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title mb-0" id="modalImagemTitulo"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-2">
                    <img id="modalImagemSrc" src="" alt="" style="max-width:100%;max-height:80vh;">
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmarExclusao(anexoId, produtoId) {
            if (!confirm('Confirma a exclusão deste anexo?')) return;
            const form = document.getElementById('formExcluirAnexo');
            form.action = '/Produtoanexo/excluir/delete/' + anexoId;
            document.getElementById('excluir_produto_id').value = produtoId;
            form.submit();
        }

        function abrirImagemModal(url, nome) {
            document.getElementById('modalImagemSrc').src = url;
            document.getElementById('modalImagemTitulo').textContent = nome;
            new bootstrap.Modal(document.getElementById('modalImagem')).show();
        }
    </script>
<?php endif; ?>

<?= jsCleaveNumeral('saldoEstoque_display', 'saldoEstoque', 3) ?>
<?= jsCleaveNumeral('precoVenda_display', 'precoVenda', 2, 'R$ ') ?>
<?= jsFormHandler() ?>
