<?= formTitulo($titulo) ?>

<div class="m-3">
    <form method="POST" action="/Home/enviarEmailcontato">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input
                    type="text"
                    class="form-control"
                    name="nome"
                    id="nome"
                    placeholder="Seu nome completo"
                    maxlength="100"
                    value="<?= setValue('nome') ?>"
                    autofocus>
                <?= setMsgFilderError('nome') ?>
            </div>
            <div class="col-md-6 mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input
                    type="text"
                    class="form-control"
                    name="telefone"
                    id="telefone"
                    placeholder="(00) 00000-0000"
                    maxlength="20"
                    value="<?= setValue('telefone') ?>">
                <?= setMsgFilderError('telefone') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input
                    type="email"
                    class="form-control"
                    name="email"
                    id="email"
                    placeholder="seu@email.com"
                    maxlength="100"
                    value="<?= setValue('email') ?>">
                <?= setMsgFilderError('email') ?>
            </div>
            <div class="col-md-6 mb-3">
                <label for="assunto" class="form-label">Assunto</label>
                <input
                    type="text"
                    class="form-control"
                    name="assunto"
                    id="assunto"
                    placeholder="Assunto da mensagem"
                    maxlength="150"
                    value="<?= setValue('assunto') ?>">
                <?= setMsgFilderError('assunto') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <label for="mensagem" class="form-label">Mensagem</label>
                <textarea
                    class="form-control"
                    name="mensagem"
                    id="mensagem"
                    rows="6"
                    placeholder="Digite sua mensagem..."><?= setValue('mensagem') ?></textarea>
                <?= setMsgFilderError('mensagem') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
            </div>
        </div>

    </form>
</div>
