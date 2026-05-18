<?= formTitulo($titulo, true) ?>

<?php if (!empty($lista)): ?>

    <table class="table table-sm m-3" id="tbLista">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Nível</th>
                <th>Status</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($lista as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['nome'] ?></td>
                    <td><?= $item['email'] ?></td>
                    <td><?= $aNiveis[$item['nivel']] ?? $item['nivel'] ?></td>
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
