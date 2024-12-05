
<?php   do_shortcode('[start-all]' ); ?>
    <section id="agenda">
        
        <div   id="form_box">
        <div id="link" class="style-box">
        <label for="agendamento">Agendamento:</label>
        <input type="text" id="agendamento">
    </div>
    <div class="style-box">
    <h4>Agendar</h4>
   
    <form id="meuform" onsubmit="prevent(event,'', 'meuform')"> 

        <div>
        <label for="data">Dia</label>
        <input type="date" name="data" id="data" min="<?php echo date('Y-m-d'); ?>" required>
        </div><div>
        <label for="cliente">Cliente</label>
        <div style="position: relative">
        <input type="text" name="cliente" id="cliente" oninput="validarInput(this)" required>
        <button type="button" onclick="modal_cli()" id="busca_cli"><img src=".\wp-content\plugins\projeto\images\clientes.png" alt="Procurar" width="16px" height="16px"></button>
        </div>
        </div><div>
        <label for="cli_tel">Telefone</label>
        <input type="text" name="cli_tel" id="cli_tel" maxlenght="11" placeholder="(00)99999-9999" oninput="limitarTelefone(this), formatarTelefone(this)"
        onchange="validarTelefone(this)">
        </div><div>
        <label for="servico">Serviço</label>
        <select name="servico" id="servico" required>
        </select>
        </div><div>
        <label for="horario">Horário</label>
        <select name="horario" id="horario" required>

        </select>
        </div><div>
        <label for="hora_fim">Até: </label>
       <select name="hora_fim" id="hora_fim" required>

       </select>
       </div><div>
        <label for="valor">Valor</label>
        <input type="number" name="valor" id="valor" min="0">
        </div><div>
        <label for="desconto">Desconto</label>
        <input type="number" name="desconto" id="desconto" min="0" value=0>
        </div>
        <button type="submit">Agendar</button>
    </form>
</div>
    </div>
    <section   class="style-box" id="horarios_box">
        <div id="cabecalho">
            <input type="date" name="dia" id="dia" oninput="att_data(value)" value="<?php echo date('Y-m-d'); ?>">

        </div>
        <div id="container">
        <ul id="horarios">
           
</ul>
</div>
    </section>

    <dialog id="msg">
     
    </dialog>
    <dialog id="modal">
        <form method="dialog" id="form2">
            <span id="nome"></span>
            <span id="telefone"></span>
            <span id="servico_modal"></span>
            <input type="hidden" name="id_agenda" id="id_a">
            <input type="date" name="data" id="data_ag">
            <select name="horario" id="hora"></select>
            <select name="hora_f" id="hora_f"></select>
            <button type="button" onclick="reagenda()">Reagendar</button>
            <button type="button" onclick="fecha_modal('modal')">Cancelar</button>
        </form>
    </dialog>

    <dialog id="modal_C">
        <p>Deseja Mesmo Cancelar ?</p>
        <button type="button" id="apaga">SIM</button>
        <button type="button" onclick="fecha_modal('modal_C')">NÃO</button>
    </dialog>

    <dialog id="modal_busca">

        <form id="buscar">
            <input type="text" name="busca" id="campo_buscar" placeholder="Buscar cliente">
        </form>
        
        <div id="cards"></div>
        <button type="button" onclick="fecha_modal('modal_busca')">Cancelar</button>
    </dialog>
    <!-- Adicione aqui o código HTML e PHP para exibir a agenda -->

</body>
</html>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function(){

        const membro_t = "<?php echo $_SESSION['tipo']; ?>";
        const membro = "<?php echo $_SESSION['user_id'] ?>";


membro_t == 3 ? funcio() : null;
membro_t == 1 ? ocultar() : null;

criaLink(membro);


armazena_local();

    });
  
</script>