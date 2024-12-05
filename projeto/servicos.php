
<?php   do_shortcode('[start-all]' ); ?>
<section id="services">

<section id="cad-services">
    <div   class="style-box">
        <h4>Adicionar Serviço / Pacote</h4>
<form id="formServ" onsubmit="prevent(event, 'add_serv', 'formServ')">

    <div>
    <label for="servico_nome">Nome:</label>
    <input type="text" name="servico" id="servico_nome" required>
</div><div>
    <label for="valor">Valor:</label>
    <input type="number" name="valor" id="valor" min="0" required>
    </div><div>
    <label for="hora">Tempo:</label>
    <select name="tempo" id="hora"></select>
    </div>
    <div>
        <label for="tipo_serv">Tipo:</label>
        <select name="tipo" id="tipo_serv">
            <option value="serviço">Serviço</option>
            <option value="pacote">Pacote</option>
        </select>
    </div>
    <div>
    <label for="qtd">Sessões:</label>    
    <input type="number" name="quantidade" id="qtd" value="1" min="1"></div>
    <button type="submit">Adicionar Serviço</button>
</form>
</div>
<div>
<div class="scroll" style="max-height: 288px">
<div id="servicos">
</div>
</div>
<button onclick="salvar_mud('servicos', 'alt_serv')" style="margin-top: 8px">Salvar Mudanças</button>
</div>
</section>
<dialog id="msg"> </dialog>
<dialog id="excluir_modal">
    <p>Deseja mesmo excluir o serviço ? </P>
    <button type="button" id="apaga">SIM</button>
        <button type="button" onclick="fecha_modal('excluir_modal')">NÃO</button>
</dialog>

</section>

<script>

document.addEventListener('DOMContentLoaded', function(){

armazena_local();

    const tipo_usuario = "<?php 
        echo $_SESSION['tipo']; ?>";
    const sele =document.getElementById('tipo_serv');
    const ele = document.getElementById('qtd');

    sele.addEventListener('change',function(){
        const sess =document.getElementById('qtd');
        const vlr = sele.value;
        if(vlr == 'pacote'){
            sess.setAttribute('readOnly', true);
        } else {
            sess.removeAttribute('readOnly');
        }
    });
    if(tipo_usuario == 'Gratuito'){

        ele.setAttribute('readOnly', true);
        sele.innerHTML = '<option value="serviço">Serviço</option>';
    }});
</script>