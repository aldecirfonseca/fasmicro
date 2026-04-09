<div class="row bg-primary text-white m-2">
    <div class="col-10 p-2">
        <h2><?= $titulo ?></h2>
    </div>
    <div class="col-2 text-end p-2">
        <a href="/categoria" class="btn btn-outline-info text-white" title="Voltar">Voltar</a>
    </div>
</div>

<div class="m-2">
    <form method="POST" action="/categoria/<?= $data['action'] ?>">

        <input 
            type="hidden" 
            name="id" 
            id="id" 
            value="<?= $data['data']['id'] ?? 0 ?>">

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
                    value="<?=  $data['data']['descricao'] ?? '' ?>"
                    required
                    autofocus>
            </div>
            <div class="col-3">
                <label for="statusRegistro">Status</label>
                <select class="form-control" name="statusRegistro" id="statusRegistro">
                    <option value="">...</option>
                    <?php foreach ($data['aStatus'] as $key => $value): ?>
                        <?php $statusSelected = $key == $data['data']['statusRegistro']; ?>
                        <option value="<?= $key ?>" <?= $statusSelected ? "selected" : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <a href="/categoria" class="btn btn-outline-info" title="Voltar">Voltar</a>
                <?php if ($data['action'] != "view"): ?>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                <?php endif; ?>
            </div>
        </div>


    </form>
</div>

