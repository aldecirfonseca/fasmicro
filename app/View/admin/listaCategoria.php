<?php
use Core\Library\Session;
?>

<?= formTitulo('Lista Categoria', true) ?>

<?php if (!empty($categorias)): ?>

    <table class="table table-sm">
        <thead>
            <tr>
                <th>Id</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?= $categoria['id'] ?></td>
                    <td><?= $categoria['descricao'] ?></td>
                    <td><?= $aStatus[$categoria['statusRegistro']] ?></td>
                    <td>
                        <?= buttons('view'  , $categoria['id']) ?>
                        <?= buttons('update', $categoria['id']) ?>
                        <?= buttons('delete', $categoria['id']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
        
<?php else: ?>
    <p class="text-muted">Nenhuma Categoria encontrada.</p>
<?php endif;?>

<a href="/" class="btn btn-secondary mt-3">Voltar</a>