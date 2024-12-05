
<?php   do_shortcode('[start-all]' ); ?>

<section id="func_pag">
    <div class="style-box" id="cad_div">
        <h4>Cadastrar Colaborador</h4>
        <form id="cad_fun" onsubmit="prevent(event, 'add_fun', 'cad_fun')">
        <div>
        <label for="email">Email:</label>
        <input type="text" name="ema" id="email" required>
        </div><div>
        <label for="senha">Senha:</label>
        <input type="password" name="pass" id="senha" required>
        </div><div>
        <label for="nom">Nome: </label>
        <input type="text" name="nome" id="nom" required>
        </div><div>
        <label for="nasc">nascimento: </label>
        <input type="date" name="nascimento" id="nasc" required>
        </div><div>
        <label for="tel">Telefone: </label>
        <input type="tel" name="fone" id="tel" required oninput="limitarTelefone(this), formatarTelefone(this)"
        onchange="validarTelefone(this)">
        </div><div>
        <label for="tel2">Telefone 2: </label>
        <input type="tel" name="fone2" id="tel2" value="" oninput="limitarTelefone(this), formatarTelefone(this)"
        onchange="validarTelefone(this)">
        </div><div>
        <label for="ru">Rua: </label>
        <input type="text" name="rua" id="ru" required>
        </div><div>
        <label for="num">N°: </label>
        <input type="number" name="numero" id="num" min="1" required>
        </div><div>
        <label for="bar">Bairro: </label>
        <input type="text" name="bairro" id="bar" required>
        </div><div>
        <label for="funca">Função: </label>
            <input type="text" name="funcao" id="funca">
            </div><div>
        <label for="sala">Salário: </label>
        <input type="number" name="salario" id="sala" min="0">
        </div><div>
        <label for="comic">Comissão: </label>
        <input type="number" name="comicao" id="comic" min="0">
        </div>
        <button type="submit">Adicionar</button>
</form>
    </div>


        <div id="lista-funcionarios">
        <!--  <div>
            <input type="text" name="pesquisar" id="pesquisa" placeholder="Nome">
            <button type="button" onclick="funcionarios()">Pesquisar</button>
        </div> -->
        </div>

    <dialog id="msg"> </dialog>
    <dialog id="excluir_modal">
        <p>Deseja mesmo excluir ?</p>
        <button id="sim" >SIM </button>
        <button   onclick="fecha_modal('excluir_modal')"> NÃO </button>
    </dialog>

</section>

<script>
    document.addEventListener("DOMContentLoaded", function(){

        funcionarios();

    });
</script>