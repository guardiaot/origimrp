<?php 
require_once("verificar.php");
$pag = 'prompts';

//verificar se ele tem a permissão de estar nessa página
if(@$prompts == 'ocultar'){
    echo "<script>window.location='index'</script>";
    exit();
}


 ?>
<div class="justify-content-between" style="margin-top: 20px">
    <nav style="margin-bottom: 20px">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">

            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                type="button" role="tab" aria-controls="nav-home" aria-selected="true"
                onclick="mudarTextoBotao('novo')">Listar Prompts
            </button>
            <button onclick="limparCampos()" class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                aria-selected="false">
                <i id="icone-botao" class="fa fa-plus"></i>
                <span id="texto-botao">Adicionar Novo Prompt</span>
            </button>

        </div>
    </nav>

</div>


<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card custom-card">
                    <div class="card-body" id="listar" style="font-weight: normal;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="row">
    <!-- Coluna esquerda: formulário -->
    <div class="col-md-6">
        <form id="form_contrato" method="POST">
            <div>
                <div class="row">
                    <div class="col-md-9 mb-2">
                        <label>Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título" required>
                    </div>
                   
                    <div class="col-md-3 mb-2" style="margin-top: 26px">
                        <button type="submit" id="btn_salvar" class="btn btn-primary">
                            Salvar <i class="fa fa-check ms-2"></i>
                        </button>
                        <button class="btn btn-primary" type="button" id="btn_carregando" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Carregando...
                        </button>
                    </div>
                </div>

                <small>
                    <div id="mensagem" align="center"></div>
                </small>

                <div class="row">
                    <div class="col-md-12 mb-2" style="font-weight: normal !important">
                        <label>Texto</label>
                        <textarea class="form-control" id="texto" name="texto" rows="15"></textarea>
                    </div>
                </div>

                <input type="hidden" class="form-control" id="id" name="id">
            </div>
        </form>
    </div>

    <!-- Coluna direita: explicação -->
    <div class="col-md-6">
        <div class="alert alert-info" style="font-size: 14px; line-height: 1.5;">
            <p>
                <strong>🧠 Instruções para Editar o Prompt</strong><br><br>
                Este é o texto que a inteligência artificial utilizará para gerar o <strong>parecer jurídico</strong> com base nas informações do caso.
                Você pode <strong>editar livremente</strong> o conteúdo, o tom e a estrutura do texto, mas <strong>não deve alterar, apagar nem modificar</strong>
                as palavras que estão <strong>entre chaves { }</strong>, pois elas serão substituídas automaticamente pelos dados do caso que foram preenchidos no formulário pelo cliente.<br><br>

                Essas chaves representam variáveis que o sistema preencherá com as informações específicas de cada processo:<br><br>

                <ul>
                    <li><strong>{tipo_acao}</strong> – Tipo de ação jurídica (ex: Ação de Cobrança, Divórcio, Inventário)</li>
                    <li><strong>{descricao}</strong> – Descrição detalhada do caso informada pelo cliente ou advogado</li>
                    <li><strong>{partes}</strong> – Partes envolvidas no processo (ex: Autor, Réu, Reclamante, etc.)</li>
                    <li><strong>{processo_existente}</strong> – Informação sobre a existência de processo anterior</li>
                    <li><strong>{objetivo}</strong> – Objetivo ou expectativa do cliente com o processo</li>
                </ul>

                <strong>✅ Exemplo de prompt correto:</strong><br>
                <pre style="background:#f8f9fa; padding:10px; border-radius:5px;">
Você é um advogado especialista em orientar processos jurídicos.
Analise o caso abaixo e indique os melhores caminhos possíveis:

📌 Tipo de Ação: {tipo_acao}
📝 Descrição: {descricao}
👥 Partes: {partes}
📂 Processo Existente: {processo_existente}
🎯 Objetivo: {objetivo}

Forneça um parecer jurídico com sugestões de próximos passos.
                </pre>

                <strong>🚫 O que <u>não fazer</u>:</strong><br>
                – ❌ Apagar ou renomear as variáveis (ex: {tipo_acao})<br>
                – ❌ Colocar espaços dentro das chaves (ex: { tipo_acao })<br>
                – ❌ Repetir variáveis sem necessidade<br><br>

                <strong>💡 O que <u>pode fazer</u>:</strong><br>
                – ✅ Alterar o texto livre (antes, entre ou depois das variáveis)<br>
                – ✅ Adicionar instruções extras à IA<br>
                – ✅ Personalizar a linguagem (mais técnica, resumida ou detalhada)
            </p>
        </div>
    </div>
</div>

    </div>
</div>





<script type="text/javascript">
    $("#form_contrato").submit(function (event) {
        event.preventDefault();
        $('#btn_salvar').hide();
        $('#btn_carregando').show();
        var formData = new FormData(this);
        $.ajax({
            url: 'paginas/' + pag + "/salvar.php",
            type: 'POST',
            data: formData,
            success: function (mensagem) {
                $('#mensagem').text('');
                $('#mensagem').removeClass()
                if (mensagem.trim() == "Salvo com Sucesso") {
                    alertsucesso(mensagem)
                    $('#nav-home-tab').click();
                    listar();
                    $('#mensagem').text('')
                } else {
                    $('#mensagem').addClass('text-danger')
                    $('#mensagem').text(mensagem)
                }
                $('#btn_salvar').show();
                $('#btn_carregando').hide();
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>

<script type="text/javascript">
    function adicionarTag(tag) {
        var editor = $('#trumbowyg-editor');

        // Insere a tag na posição atual do cursor
        editor.trumbowyg('execCmd', {
            cmd: 'insertHTML',
            param: ' ' + tag + ' ',
            forceCss: false
        });

        $('#tag').val('');
    }
</script>

<script type="text/javascript">
    function mudarTextoBotao(modo) {
        const textoBotao = document.getElementById('texto-botao');
        const iconeBotao = document.getElementById('icone-botao');

        if (modo === 'editar') {
            textoBotao.textContent = 'Editar Contrato';
            iconeBotao.className = 'fa fa-edit';
        } else {
            textoBotao.textContent = 'Adicionar Novo Contrato';
            iconeBotao.className = 'fa fa-plus';
        }
    }
</script>

<script type="text/javascript">var pag = "<?= $pag ?>"</script>
<script src="js/ajax.js"></script>



