<?php
use Core\Library\Session;
?>

<?= formTitulo($titulo, true) ?>

<?php if (!empty($lista)): ?>

    <table class="table table-sm m-3">
        <thead>
            <tr>
                <th>Id</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($lista as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['descricao'] ?></td>
                    <td><?= $aStatus[$item['statusRegistro']] ?></td>
                    <td>
                        <?= buttons('view'  , $item['id']) ?>
                        <?= buttons('update', $item['id']) ?>
                        <?= buttons('delete', $item['id']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
        
<?php else: ?>
    <p class="text-muted">Nenhuma <?= $titulo ?> encontrada.</p>
<?php endif;?>

<a href="/" class="btn btn-secondary mt-3">Voltar</a>