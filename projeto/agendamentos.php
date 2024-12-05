

    <section id="agendamentos">
    <div  class="style-box" id="form_box2">
    <h4>Agendar</h4>
   
    <form id="meuform" onsubmit="prevent(event,'', 'meuform')"> 

        <div>
            <input type="hidden" value="<?php echo $_GET['id'];?>" name="tipo">
        <label for="data">Dia:</label>
        <input type="date" name="data" id="data" min="<?php echo date('Y-m-d'); ?>" required>
        </div><div>
        <label for="cliente">Nome:</label>
        <div style="position: relative">
        <input type="text" name="cliente" id="cliente" oninput="validarInput(this)" required>
        </div>
        </div><div>
        <label for="cli_tel">Telefone:</label>
        <input type="text" name="cli_tel" id="cli_tel" maxlenght="11" placeholder="(00)99999-9999" oninput="limitarTelefone(this), formatarTelefone(this)"
        onchange="validarTelefone(this)">
        </div><div>
        <label for="servico">Serviço:</label>
        <select name="servico" id="servico" required>
        </select>
        </div><div>
        <label for="horario">Horário</label>
        <select name="horario" id="horario" required>

        </select>
        </div><div>
        <label for="hora_fim">Até: </label>
       <select name="hora_fim" id="hora_fim" required style="pointer-events: none; background-color: #e9ecef;
       color: #6c757d">

       </select>
       </div><div>
        <label for="valor">Valor</label>
        <input type="number" name="valor" id="valor" min="0" readonly>
        </div>
        <input type="number" name="desconto" id="desconto" min="0" value=0 hidden>
   
        <button type="submit">Agendar</button>
    </form>
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
   
</body>
</html>
</section>

<script>
    
  document.addEventListener('DOMContentLoaded', function( ){
    
    window['id_ag'] = <?php echo $_GET['id']; ?>;
    let dia =document.getElementById('dia').value;
    lista_disponivel();

  });


</script>