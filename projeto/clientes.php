
<?php   do_shortcode('[start-all]' );?>
<section id="cli_pag">
    <div class="style-box" id="cli_div">
        <h4>Cadastrar Cliente</h4>
        <form id="cad_cli" onsubmit="prevent(event, 'add_cli', 'cad_cli')">

        <div>
        <label for="nom">Nome</label>
        <input type="text" name="nome" id="nom" required>
        </div><div>
        <label for="tel">Telefone</label>
        <input type="tel" name="fone" id="tel" required maxlength="14" oninput="limitarTelefone(this), formatarTelefone(this)"
        onchange="validarTelefone(this)">
        </div><div>
        <label for="nasc">nascimento</label>
        <input type="date" name="nascimento" id="nasc">
        </div><div>
 
        <label for="ru">Rua</label>
        <input type="text" name="rua" id="ru">
        </div><div>
        <label for="num">N°</label>
        <input type="number" name="numero" id="num" min="1">
        </div><div>
        <label for="bar">Bairro</label>
        <input type="text" name="bairro" id="bar">
        </div>
        <button type="submit">Adicionar Cliente</button>
</form>
    </div>

    <div id="lista_div">
        <div class="style-box">
            <input type="text" name="pesquisar" id="pesquisa" placeholder="Nome">
            <button type="button" onclick="clientes()">Pesquisar</button>
        </div>
        <div id="lista-clientes">

        </div>
    </div>

    <dialog id="msg"> </dialog>
    <dialog id="excluir_modal">
        <p>Deseja mesmo excluir ?</p>
        <button id="sim" >SIM </button>
        <button   onclick="fecha_modal('excluir_modal')"> NÃO </button>
    </dialog>

    <dialog id="pacote_modal">
        <form id="add_pacote" onsubmit="prevent(event, 'add_pctcli', 'add_pacote')">

        <input type="hidden" name="id" id="hidden">
        
            <label for="pacote">Pacote</label>
            <select name="pacotes" id="pacote"></select>


            <label for="pag">Pagamento</label>
            <select name="pagamento" id="pag">
                <option value="A VISTA">A VISTA</option>
                <option value="CRÉDITO">CRÉDITO</option>
                <option value="X2">CRÉDITO X2</option>
                <option value="X3">CRÉDITO X3</option>
                <option value="X4">CRÉDITO X4</option>
                <option value="X5">CRÉDITO X5</option>
                <option value="X6">CRÉDITO X6</option>
                <option value="X7">CRÉDITO X7</option>
                <option value="X8">CRÉDITO X8</option>
                <option value="X9">CRÉDITO X9</option>
                <option value="X10">CRÉDITO X10</option>
                <option value="X11">CRÉDITO X11</option>
                <option value="X12">CRÉDITO X12</option>
            </select>
            
            <P id="vlr">Valor: </P>

            <label for="desc">Desconto</label>
            <input type="number" name="desconto" id="desc" oninput="att_pct()" min=0>

            <p id="ttl_pct">Total:</p>
            <input type="hidden" name="total" id="ttl_vlr">
            <button type="submit" id="adicionar">Adicionar</button>
            <button type="button" onclick="fecha_modal('pacote_modal')">Cancelar</button>
        </form>
    </dialog>
</section>

<script>


document.addEventListener("DOMContentLoaded", function(){

armazena_local();
});


</script>