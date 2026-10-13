<?php 
require_once("verificar.php");
$pag = 'contratos';

//verificar se ele tem a permissão de estar nessa página
if(@$contratos == 'ocultar'){
    echo "<script>window.location='index'</script>";
    exit();
}


 ?>
<div class="justify-content-between" style="margin-top: 20px">
    <nav style="margin-bottom: 20px">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">

            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                type="button" role="tab" aria-controls="nav-home" aria-selected="true"
                onclick="mudarTextoBotao('novo')">Listar Contratos
            </button>
            <button onclick="limparCampos()" class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                aria-selected="false">
                <i id="icone-botao" class="fa fa-plus"></i>
                <span id="texto-botao">Adicionar Novo Contrato</span>
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
        <form id="form_contrato" method="POST">
            <div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label>Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label>TAGS DO CONTRATO</label>
                        <select class="form-select" id="tag" name="tag" onchange="adicionarTag(this.value)">
                            <option value="">Selecione uma Tag</option>

                            <optgroup label="DADOS DO CLIENTE">
                            	<option value="{{TEXTO DADOS}}">Todos os Dados</option>
                                <option value="{{NOME}}">Nome</option>
                                <option value="{{NOME_RESPONSAVEL}}">Nome do Responsável</option>
                                <option value="{{EMAIL}}">Email</option>
                                <option value="{{TELEFONE}}">Telefone</option>                                
                                <option value="{{NACIONALIDADE}}">Nacionalidade</option>
                                <option value="{{DATA DE NASCIMENTO}}">Data de Nascimento</option>
                                <option value="{{PROFISSÃO}}">Profissão</option>
                                <option value="{{ESTADO CÍVIL}}">Estado Cívil</option>
                                <option value="{{ASSINATURA}}">Assinatura Imagem</option>
                                <option value="{{TEXTO DADOS OCULTO}}">Dados sem Telefone e Email</option>
                               
                            </optgroup>

                            <optgroup label="ENDEREÇO DO CLIENTE">
                            	<option value="{{ENDERECO_COMPLETO}}">Endereço Completo</option>
                                <option value="{{CEP}}">CEP</option>
                                <option value="{{LOGRADOURO}}">Logradouro</option>
                                <option value="{{NUMERO}}">Número</option>
                                <option value="{{COMPLEMENTO}}">Complemento</option>
                                <option value="{{BAIRRO}}">Bairro</option>
                                <option value="{{CIDADE}}">Cidade</option>
                                <option value="{{ESTADO}}">Estado</option>
                                
                            </optgroup>
                          

                            <optgroup label="DADOS DO SISTEMA">
                                <option value="{{LOCAL}}">Cidade Sistema</option>
                                <option value="{{DATA}}">Data Contrato</option>
                                <option value="{{NOME EMPRESA}}">Nome da Empresa</option>
                                <option value="{{CNPJ EMPRESA}}">Cnpj Empresa</option>
                                <option value="{{ENDERECO EMPRESA}}">Endereço da Empresa</option>
                                <option value="{{ASSINATURA_SISTEMA}}">Assinatura Sistema (Config)</option>
                            </optgroup>

                                <optgroup label="VALORES">
                                <option value="{{VALOR}}">Valor</option>
                                <option value="{{FORMA DE PAGAMENTO}}">Forma de Pagamento</option>
                                <option value="{{PODERES ESPECÍFICOS}}">Poderes Específicos</option>
                                <option value="{{VALOR_PAGAMENTO}}">Valor Pagamento</option>
                               
                            </optgroup>

                            <optgroup label="PROFISSIONAIS NO CONTRATO">
                                <option value="{{PROFISSIONAL}}">Informações dos Profissionais</option>
                                <option value="{{ASSINATURA_PROFISSIONAL}}">Assinatura do Profissional</option>
                               
                            </optgroup>

                             <optgroup label="DEMAIS OPÇÕES">
                                <option value="{{DEMAIS CONTRATANTES}}">Mais Clientes</option>
                               <option value="{{DEMAIS CONTRATADOS}}">Mais Profissionais</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2" style="margin-top: 26px">
                        <button type="submit" id="btn_salvar" class="btn btn-primary">Salvar<i
                                class="fa fa-check ms-2"></i></button>
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
                    <div class="col-md-9 mb-2" style="font-weight: normal !important">
                        <label>Texto</label>
                        <textarea class="form-control" id="trumbowyg-editor" name="texto" style="font-weight: normal !important">
                        </textarea>
                    </div>
                </div>
                <input type="hidden" class="form-control" id="id" name="id">
            </div>
        </form>
    </div>
</div>


<!-- Modal Dados -->
<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" id="exampleModalLabel">MOSTRAR DADOS</h4>
                <button id="btn-fechar-dados" aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                    type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <small>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tile">
                                <div class="table-responsive">
                                    <table id="" class="text-left table table-bordered">
                                        <span align="center">
                                            <h5 id="titulo_dados"></h5>
                                        </span>
                                        <tr>
                                            <td><span id="mensagem_dados"></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </small>
                <div id="listar_servicos" style="margin-top: 15px; "></div>
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



