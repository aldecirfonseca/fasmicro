<?php
use Core\Library\Session;
?>

<?= formTitulo($titulo, true) ?>

<?php if (!empty($lista)): ?>

    <table class="table table-sm m-3" id="tbLista">
        <thead>
            <tr>
                <th>Id</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Un.</th>
                <th class="text-end">Estoque</th>
                <th class="text-end">Preço Venda</th>
                <th>Status</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($lista as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['descricao'] ?></td>
                    <td><?= $item['nomeCategoria'] ?></td>
                    <td><?= $item['siglaUnidade'] ?></td>
                    <td class="text-end"><?= nfValor($item['saldoEstoque'], 3) ?></td>
                    <td class="text-end"><?= nfValor($item['precoVenda']) ?></td>
                    <td><?= $aStatus[$item['statusRegistro']] ?></td>
                    <td>
                        <?= buttons('view',   $item['id']) ?>
                        <?= buttons('update', $item['id']) ?>
                        <?= buttons('delete', $item['id']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <?= datatables("tbLista") ?>

<?php else: ?>
    <p class="text-muted m-3">Nenhum <?= $titulo ?> encontrado.</p>
<?php endif; ?>

<a href="/" class="btn btn-secondary m-3">Voltar</a>
