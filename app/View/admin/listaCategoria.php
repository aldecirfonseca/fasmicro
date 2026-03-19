<h1><?= $titulo ?? "Lista Categoria" ?></h1>

<?php if (!empty($categorias)): ?>
    <ul class="list-group">
        <?php foreach ($categorias as $categoria): ?>
            <li class="list-group-item"><?= $categoria ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="text-muted">Nenhuma Categoria encontrada.</p>
<?php endif;?>

<a href="/" class="btn btn-secondary mt-3">Voltar</a>