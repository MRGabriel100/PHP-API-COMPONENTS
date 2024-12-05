
<?php   do_shortcode('[start-all]' ); ?>
<section id="financeiro">
<div>

<div id="relatorio"  class="style-box">
<h4>RELATÓRIO</h4>
        <form id="relat_form" > 
            <div>
            <label for="de">De:</label>
            <input type="date" name="de" id="de">
</div><div>
            <label for="ate">Até:</label>
            <input type="date" name="ate" id="ate" value="<?php echo date("Y-m-d"); ?>">
            </div><div>
            <label for="tipo_rel">Tipo:</label>
            <select name="tipo_relatorio" id="tipo_rel">
                <option value="SAIDA">Saída</option>
                <option value="ENTRADA">Entrada</option>
                <option value="SIMPLES">Simples</option>
                <option value="COMPLETO">Completo</option>
            </select>
            </div>
            <button type="button" onclick="gerar_relatorio()">Gerar Relatório</button>
           
        </form>
        </div>
   
</div>
<div>

<div id="gastos"  class="style-box">
<h4>ENTRADAS/SAÍDAS</h4>
    <form id="form_finan" onsubmit="prevent(event,'add_said', 'form_finan')">
        <div>
    <label for="gasto">Nome:</label>
    <input type="text" name="gasto" id="gasto" required>
    </div><div>
    <label for="valor">Valor:</label>
    <input type="number" name="valor" id="valor" required>
    </div><div>
    <label for="data"> Data:</label>
    <input type="date" name="data" id="data" required>
    </div><div>
    <label for="tipo">Tipo:</label>
    <select name="tipo" id="tipo">
        <option value="SAIDA">SAÍDA</option>
        <option value="ENTRADA">ENTRADA</option>
    </select>
    </div>
    <button type="submit">ADICIONAR</button>

    </form>
</div>
</div>

<div class="oculto">
<table id="tabela"  class="style-box">

    </table>
    </div>
<dialog id="msg">
     
     </dialog>
</section>

<script>
</script>