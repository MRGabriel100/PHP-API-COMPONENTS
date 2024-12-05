
function att_banco(funcao, form) {
    
    let date = new Date;
    date = date.toLocaleDateString('pt-BR').split('/').reverse().join('-');
    const formData = typeof form === 'object' ? form : new FormData(document.getElementById(form));

    const formobj = {};
    formData.forEach((value, key) => {
        formobj[key] = value;
    });

    fetch("AQUI VAI A URL" + funcao + ".php", {
        method: 'POST', // Especifique o método como POST para enviar dados
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formobj),
    })
    .then(response => {
        // Retorna o conteúdo da resposta como texto
        return response.text();
        
    })
    .then(data => {
        // Exibe o conteúdo da resposta no console
      //  console.log(data);
        let msg = JSON.parse(data);
        alerta(msg['response']);
 
    }).then(() => {

        switch(funcao){

            case 'add_serv' :
                armazena_servicos();
                limpa(form);
            break;

            case 'add_agenda': 
            armazena_agenda(date, '');
            limpa(form);
            break;

            case "reagenda":
                armazena_agenda(date, '');
                break;

            case "paga_fun": 
                break;

            case "alt_cli": 
                break;

            case "alt_fun" :
                break;
            
            case "add_pctcli": 
                clientes();
                fecha_modal('pacote_modal');
                break;

            default: limpa(form);
        }
        
    })
    if (formData['data'] && formData['data'] == date){
        att_data(date);
        armazena_agenda(date, '');

    }
   
}

function chama_banco(funcao, filtro1, filtro2, inicio, fim) {
  return fetch("AQUI VAI A URL" + funcao + ".php?"  +
   encodeURIComponent('filtro1') + '=' + encodeURIComponent(filtro1) + '&' +
   encodeURIComponent('filtro2') + '=' + encodeURIComponent(filtro2) + '&' +
   encodeURIComponent('inicio') + '=' + encodeURIComponent(inicio) + '&' +
   encodeURIComponent('fim') + '=' + encodeURIComponent(fim) + '&' +
    {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
        // Chama armazena_local após receber os dados
        // o return data retorna os dados para o fetch
        // o return fetch retorna os dados do data para aonde for chamado
      //  console.log(typeof data); mostra o tipo de dados (objeto, json)

        return data;
    });
}

function remove(funcao, aux1, aux2, aux3){
    fetch("AQUI VAI A URL" + funcao + ".php" ,{
         method: 'POST',
         headers: {
            'Content-Type': 'application/json'
         },
         body: JSON.stringify({aux1: aux1, aux2: aux2, aux3: aux3})
     })
     .then(response =>  response.json())
     .then(data => {
   
        alerta(data['response']);
     }).then(function(){
        let date = document.getElementById('dia').value;
        funcao == "del_serv" ? armazena_servicos() : null;
        funcao == 'cancelar' ? armazena_agenda(date, '') : null;
    })

}


// PARTE DE ARMAZENAGEM LOCAL

function armazena_horarios(tipo) {
    return new Promise(resolve => {
        if (!localStorage.getItem('horarios')) {
            chama_banco("get_horarios", tipo).then(dados_recebidos => {
                localStorage.setItem('horarios', JSON.stringify(dados_recebidos));
                resolve(); // Resolve a Promise quando os dados são armazenados
            }).catch(error => {
                console.error('Erro ao buscar os horários do banco de dados:', error);
                resolve(); // Resolve mesmo em caso de erro para não bloquear o fluxo
            });
        } else {
            resolve(); // Resolve imediatamente se os horários já estiverem armazenados
        }
    });
}
// PARTE DE ARMAZENAGEM LOCAL
async function armazena_local() {
    let filtro1, filtro2;
    // console.log('armazena');
   await armazena_horarios();
    let att = localStorage.getItem('ultima_atualizacao');
    let data  = new Date;
    filtro1 = data.toLocaleDateString('pt-BR').split('/').reverse().join('-');
    //armazena_agenda(filtro1, '');
    if(att != filtro1 || !localStorage.getItem('agenda')){
        localStorage.setItem("ultima_atualizacao", filtro1);
       armazena_agenda(filtro1, '');
    }else {
    // Chama a função horarios para atualizar o elemento select
    agenda(JSON.parse(localStorage.getItem('agenda')));

    }

    document.getElementById('agenda') ? horarios() : null;
    document.getElementById('services') ? armazena_servicos() : null;
    document.getElementById('pacotes') ? pacotes() : null;
    
}

function armazena_servicos(){

    chama_banco("get_servicos", 'completo').then(dados_recebidos => {

    localStorage.setItem('servicos', JSON.stringify(dados_recebidos));
    servicos();
})

}

function armazena_pacotes(){

    chama_banco("get_servicos", 'pacotes').then(dados => {
        localStorage.setItem('pacotes', JSON.stringify(dados));
        pacotes()
    })
  
}

function armazena_agenda(filtro1, filtro2){

    try {
        chama_banco("get_agenda", filtro1, filtro2).then(dados_recebidos => {
            agenda(dados_recebidos);
            localStorage.setItem('agenda', JSON.stringify(dados_recebidos));
        })
        
    } catch(error){
        console.error('erro ao buscar agenda: ', error);
    }
    get_serv('simples');
}

function armazena_config(){
    try {
        chama_banco("get_config")
        .then(dados_recebidos => JSON.stringify(dados_recebidos))
        .then(dados =>  localStorage.setItem('config', dados));
    } catch(error){
        console.error('erro ao buscar configurações: ', error);
    }
}
    //FIM DA PARTE DE ARMAZENAMENTO
    // PARTE DA AGENDA
function att_data(data){
    let id;
    if(typeof id_ag !== 'undefined'){
        id = id_ag;
    }
   chama_banco("get_agenda", data, id).then(dados => {

    agenda(dados);

    typeof id_ag !== 'undefined' ? cria_lista() : null;
       
   })
}

function agenda(array){
     let horarios = document.getElementById('horarios') 
    let lista = JSON.parse(localStorage.getItem('horarios'));

     if(horarios){
       
        horarios.innerHTML = '';

        const horas = lista.filter(horario => {
            const [hora, minutos] = horario.split(':');

            return minutos === '00';
        });

        const hora2 = lista.filter(horario => {
            const [hora, minutos] = horario.split(':');
            return minutos === '30';
        })
        horas.forEach((hora, index) => {
            let li = document.createElement('li');
            //li.id = hora;
            li.className = "lista";
            li.innerHTML = `<div>${hora}</div><div class="box"></div>`;
            
            li.dataset.value = `${hora}`;


            horarios.appendChild(li);
        });

       const lista2 = document.querySelectorAll('.lista');
       const box = document.querySelectorAll('.box');
        const element = document.createElement('div');

       lista2.forEach((item, index) => {

        for(x = index * 2; x < (index + 1) * 2 && x < lista.length; x++){
            element.classList.add('item', 'item-border');
            el_clone = element.cloneNode(true);
            el_clone.dataset.value = lista[x ];
            box[index].appendChild(el_clone);
            
        }
          
       // box[index].appendChild(el_clone2)
       })
       const pagina = document.getElementById('agendamentos');
      pagina ? null : agenda2(array, lista, hora2);
     }
   
}

//CRIA O BOX COM OS DADOS DO AGENDAMENTO
function agenda2(agendados, lista, hora2){
    let box = document.querySelectorAll('.item');
    let todosHorarios = Array.from(box).map(item => item.dataset.value);
    let add = document.createElement('button');
    let horaDisp = [];
    let horaDisp2 = [];
    add.textContent = 'Adicionar';
    const dado = document.createElement('p');
    box.forEach(item => {
        item.classList.remove('item-border');
        
    });
    agendados.forEach((item, index) =>{

        let ex = document.createElement('button');
        ex.setAttribute ('onclick', `cancelar(${item['id']}, "${item['tipo_serv']}",
         ${item['id_pct']})`);
        ex.textContent = 'Cancelar';
        ex.classList.add('botao-item');

        let alt = document.createElement('button');
        alt.setAttribute ('onclick', 'reagenda_modal(' + item['id'] + ')');
        alt.textContent = 'Reagendar';
        alt.classList.add('botao-item');
       const inicioIndex = todosHorarios.findIndex(element => element === item.hora);
       const fimIndex = todosHorarios.findIndex(element => element === item.ate);
       const remover = todosHorarios.findIndex(element => element > item.hora && element < item.ate);
       horaDisp = horaDisp.concat(todosHorarios.slice(inicioIndex, fimIndex));
        const alturaBox = 198;
        const boxAgendado = document.createElement('div');
        boxAgendado.classList.add('marcado');
        boxAgendado.id = item.id;
      // box[inicioIndex].style.height = `${alturaBox}px`;
        box[inicioIndex].classList.add('marcado_item');
        boxAgendado.innerHTML = 
        `<p class="tempo">${item.hora} - ${item.ate}</p>
        <div>
        <p>Cliente: </p><p>${item.cliente}</p>
        </div><div>
        <p>Telefone:  </p><p>${item.telefone}</p>
        </div><div>
        <p>Serviço:  </p><p>${item.serviço}</p>
        </div><div>
        ${item.profissional != null ?  `<p>Profissional: </p><p> ${item.profissional}</p></div><div>` : ''}
        <p>Valor: </p><p> ${item.valor}</p></div><div>
        <p>Desconto: </p><p> ${item.desconto}</p></div><div>
        <p>Total:  </p><p>${item.valor - item.desconto}</p></div>
        `;

        boxAgendado.appendChild(alt);
        boxAgendado.appendChild(ex);
        box[inicioIndex].appendChild(boxAgendado);
        
        horaDisp.forEach((hora, x) => {

         
            if(hora > item.hora && hora < item.ate){
                const teste = document.querySelectorAll(`[data-value="${hora}"]`);

                if(teste){
                    teste.forEach(teste => {
                        teste.remove();
                    })
                }
        
            }
         
        })

       
        
         
}); 
    const exclusivos = todosHorarios.filter(element => !horaDisp.includes(element));
    const exclusivo = exclusivos.map(item => {
        return todosHorarios.findIndex(element => element === item);
    });

    //CRIA O BOTÃO DE AGENDAR
    exclusivo.forEach((item, index) => {
       const valores = box[item];
                const p = document.createElement('span');
                p.innerText = valores.dataset.value;
                p.classList.add('marcador');
                const botao = document.createElement('button');
                botao.innerText = 'Agendar';
                botao.className = 'agen';
                botao.onclick = function(){ botao_agenda(valores.dataset.value)};
                valores.appendChild(p);
                valores.appendChild(botao);
    })

  
    //    console.log(inicioIndex);
      //  console.log(fimIndex);
       /* for(x = index * 2; x < (index + 1) * 2 && x < lista.length; x++){

            let div = document.createElement('div');

            let agendado = agendados.find(agendado => agendado && agendado.hora === lista[x]);
          //  let hor_fim = agendados.find(agendado => agendado.ate = lista[x]);

                if(agendado){
                    
                div.dataset.value = lista[x]; 
                div.className = 'item';
                  div.dataset.value = agendado.id;
                  div.innerHTML = `<p>${agendado.data}</p>
                  <p>${agendado.cliente}</p>
                  <p>${agendado.serviço}</p>
                  <p>${agendado.valor}</p>
                  <p>${agendado.hora}</p>
                  <p>${agendado.ate}</p>`;
                  item.classList.add("marcado");
                  item.appendChild(div);
                } else if (hor_fim){
                       console.log(hor_fim);
                    } 
                   else{
                        let clone = add.cloneNode(true);
                        clone.value = lista[x];
                        item.appendChild(clone);
                    }
                
        }*/



}
function cancelar(id, tipo, id2, id3){

    let element = document.getElementById('modal_C');
    element.showModal();
   
    let botao = document.getElementById('apaga');
    botao.addEventListener('click', function(){
        apagar(id, tipo, id2, id3);  
    });
}


function apagar(id, tipo, id2, id3){
    const element = document.getElementById(id);
    element.remove();
    remove("cancelar", id, tipo, id2, id3);
    let dados = localStorage.getItem('agenda');
    let agendas = JSON.parse(dados);
    agendas = agendas.filter(item => item.id !== id);
    localStorage.setItem('agenda', JSON.stringify(agendas));
    agenda(agendas);
    fecha_modal('modal_C');
}
function reagenda_modal(id){

    const modal = document.getElementById('modal');
    modal.showModal();

    const nome = document.getElementById('nome');
    const telefone = document.getElementById('telefone');
    const servico = document.getElementById('servico_modal');
    const id_agenda = document.getElementById('id_a');
    const data = document.getElementById('data_ag');
    const select = document.getElementById('hora')
    const select2 = document.getElementById('hora_f')

    let dados = localStorage.getItem('agenda');
    let dados_JSON = JSON.parse(dados);

    dados = dados_JSON.find(chave => chave.id == id);
   // console.log(dados);
    nome.innerText = dados['cliente'];
    telefone.innerText = dados['telefone'];
    servico.innerText = dados['serviço'];
    id_agenda.value = dados['id'];
    data.value = dados['data'];

    for (let i = 0; i < select.options.length; i++){
        let option = select.options[i];
        let option2 = select2.options[i];

        option2.value === dados['ate'] ? option2.selected = true : null;
        option.value === dados['hora'] ? option.selected = true : null;
    }
  
}


function reagenda(){

checa_horarios('#hora', '#hora_f', '#data_ag')
.then(aux => {

    if(aux){
        att_banco('reagenda', 'form2');
        
        fecha_modal('modal');}
        else {
            alerta('Erro: horário não disponível')
        }
})
}

function fecha_modal(id){
    const modal = document.getElementById(id);
    modal.close();
}
function horarios() {
    let hora = JSON.parse(localStorage.getItem('horarios'));
    let select = document.getElementById('horario');
    let select2 = document.getElementById('hora_fim');
 

    hora.forEach(function(valor) {
        let option = document.createElement('option');
        option.value = valor;
        option.textContent = valor;
        select.appendChild(option);

        let option2 = document.createElement('option');
        option2.value = valor;
        option2.textContent = valor;
        select2.appendChild(option2);
    });
}
function alerta(msg){

    const id = document.getElementById('msg');
    id.showModal();
    id.innerHTML = `<p> ${msg} </p>`;

    setTimeout(function(){
        id.close();
    }, 1000);
}


const titulo1 = document.getElementById('agenda');

titulo1 ? document.addEventListener("DOMContentLoaded", function(){
    let horas = JSON.parse(localStorage.getItem('horarios'));
    let select = document.getElementById('hora');
    let select2 = document.getElementById('hora_f');

 

    horas.forEach(function(valor) {
        let option = document.createElement('option');
        let option2 = document.createElement('option');

        option.value = valor;
        option.textContent = valor;
        select.appendChild(option);

        if(select2 !== null){
            option2.value = valor;
            option2.textContent = valor;
            select2.appendChild(option2);
    
    }});

    get_serv('simples');
}) : null;
function limitarTelefone(input) {
    // Obter o valor atual do campo
    let valor = input.value;
    // Verificar se o valor excede o limite máximo
    if (valor.length > 14) {
        // Se exceder, definir o valor do campo para os primeiros 14 caracteres
        input.value = valor.slice(0, 14);
    }
}

function formatarTelefone(input) {
    // Remove todos os caracteres que não são dígitos
    let numero = input.value.replace(/\D/g, '');
    
    // Formata o número conforme a quantidade de dígitos
    if (numero.length >= 7) {
        numero = '(' + numero.substring(0, 2) + ')' + numero.substring(2);
    }
    if (numero.length > 9) {
        numero = numero.substring(0, 9) + '-' + numero.substring(9);
    }
    
    // Atualiza o valor do input com o número formatado
    input.value = numero;
}

function validarInput(input) {
    // Remove qualquer caractere que não seja uma letra
    input.value = input.value.replace(/[^A-Za-z]/g, '');
}

function validarTelefone(input) {
    // Expressão regular para validar números de telefone
    let regexTelefone = /^(\(\d{2}\)\s?)?\d{4,5}-\d{4}$/


    // Verifica se o valor do input corresponde à expressão regular
    if (!regexTelefone.test(input.value)) {
        // Se não corresponder, exibe uma mensagem de erro
        alert("Por favor, insira um número de telefone válido.");
        // Limpa o valor do input
        input.select();
       // input.value = "";
    }
}

// DAQUI PRA FRENTE FICA A PARTE DE SERVIÇOS

let idServ = [];
let idPct = [];
function servicos(){

   const ol = document.getElementById("servicos"); 
   const tempo = document.getElementById("hora");
 
    const tmp = JSON.parse(localStorage.getItem('horarios'))
    const dados = JSON.parse(localStorage.getItem('servicos'));
    const tipos = ['pacote', 'serviço'];
    tmp.forEach(hora => {
        const opt = document.createElement('option');
        opt.value = hora;
        opt.innerText = hora;
        tempo.appendChild(opt);
    })
    dados.forEach(valor => {
       const li = document.createElement('div');
       li.classList.add('servico-item');
        let htm = monta_servicos(valor, tmp, tipos);
      

        li.id = valor['id'];
        li.innerHTML = htm;
        li.addEventListener('input', (event) => {
            const target = event.target;
             if (target.tagName === 'INPUT' || target.tagName === 'SELECT')  {
                insere_id(valor['id']);
            }
        });

        ol.appendChild(li);
    });


}

function monta_servicos(valor, tmp, tipos){
    let htm;
    if(valor['funcionario'] != null || valor['nivel'] != 5){
        htm = `<div><label for="servico">Serviço: </label><br>
        <input type="text" value="${valor['servico']}" name="servico">
        </div><div>
        <label for="valor">Valor: </label><br>
        <input type="number" value="${valor['valor']}" name="valor" min="0">
        </div><div>
        <label for="horario">Tempo: </label><br>
        <select name="horario" class="hora">`
htm += tmp.map(val => `<option ${valor['tempo'] === val ? 'selected' : ''} value = ${val}>${val}</option>`).join('');
htm += `</select>
</div>
<div>
<label for="tipo">Tipo: </label><br>
<select name="tipo" class="tipo_">`
htm += tipos.map(tipo => 
`<option ${tipo == valor['tipo'] ? 'selected' : ''} value=${tipo}>${tipo}</option>`
).join('');
htm += `  </select>
</div>
<div>
<label for="quantidade">Qtd: </label><br>
<input type="number" value= ${valor['quantidade']} name="quantidade"></div>
<div>
<button onclick="del_serv_modal(${valor['id']}, 'del_serv')">Excluir</button>
</div>`;
    } else {

        htm = `<div>
        <span>Serviço:</span><br>
        <p>${valor['servico']}</p>
        </div><div>
        <span>Valor:</span><br>
        <p>${valor['valor']}</p>
        </div><div>
        <span>Tempo:</span><br>
        <p>${valor['tempo']}</p>
       
</div>
<div>
<span>Tipo:</span><br>
<p>${valor['tipo']}</p>
</div>
<div>
<span>Qtd:</span><br>
<p>${valor['quantidade']}</p></div>`;
    }
    return htm;
}
function insere_id(id){
    if(!idServ.includes(id)){
        idServ.push(id);
    }
}
function apaga_serv(id, funcao){

    const element = document.getElementById(id);
    element.remove();

    let dados = JSON.parse(localStorage.getItem('servicos'));
    dados = dados.filter(item => item.id !== id);
    localStorage.setItem('servicos', JSON.stringify(dados));
    remove(funcao, id);
 
}

function salvar_mud(nome, funcao){
    let servicos = JSON.parse(localStorage.getItem(nome));
    let ids = [];
    let ipts;
    nome == "servicos" ? ids = idServ : ids = idPct;
    nome == "servicos" ? ipts = 'input, select' : ipts = 'input';

    const obj = ids.map(id => {
        let element = document.getElementById(id);
        let objeto = {};
        let inputs = element.querySelectorAll(ipts);
        objeto["id"] = id;
        inputs.forEach(input => {
            objeto[input.name] = input.value;

        });

      let correspond = servicos.find(serv => serv.id === id);
      Object.assign(correspond, objeto);
      localStorage.setItem(nome, JSON.stringify(servicos));
        return objeto;
});
    att_banco(funcao, obj);

    localStorage.setItem(nome, JSON.stringify(servicos));
    idServ = [];
    idPct = [];
}

function limpa(formulario) {
    let form = document.getElementById(formulario);
    let input = form.querySelectorAll("input, select");
    
    input.forEach(inputs => {

        switch (inputs.type){

            case "number": inputs.value = 0;
            break;

            case "select-one": inputs.selectedIndex = 0;
            break;

            case "checkbox": inputs.checked = false;
            break;

            case "hidden":
                break;

            default: inputs.value = "";
        }
        inputs.readOnly = false;

    });

    formulario == "formServ" ? document.getElementById('qtd').value = 1 : null;
    formulario == "meuform" ?  (() => {

        document.getElementById('id_cliente').remove()
        document.getElementById('valor').classList.remove('readonly');
        document.getElementById('desconto').classList.remove('readonly');
    })() : null;

}

function get_serv(tipo, id_cli){

    chama_banco('get_servicos', tipo, id_cli).then(servicos => {
        localStorage.setItem('servicos', JSON.stringify(servicos));
        const el = document.getElementById('servico');
        el.innerHTML = '';
        
        el.appendChild(document.createElement('option'));
        servicos.forEach((servico) => {
           
           let novo_el = document.createElement('option');
           novo_el.textContent = servico.servico;
           novo_el.id = servico.id;
           novo_el.value = servico.id + ', ' + servico.tipo + ', ' + servico.id_pct;
    
           el.appendChild(novo_el);
        })

    })
  
}

function del_serv_modal(id, funcao){
    const modal = document.getElementById('excluir_modal');
    const botao = document.getElementById('apaga');
    botao.onclick = function(){
        apaga_serv(id, funcao)
        fecha_modal('excluir_modal')};
    modal.showModal();
}

//PARTE DE PACOTES

function pacotes(){
    const dados = JSON.parse(localStorage.getItem('pacotes'));
    const el = document.getElementById('lista-pacotes');
    el.innerHTML = '';
    dados.forEach(dado => {
        let tr = document.createElement('tr');
        tr.innerHTML = `<td><input type="text" value="${dado['nome']}" name="nome"></td>
        <td><input type="number" value="${dado['valor']}" name="valor"></td>
        <td><input type="number" value="${dado['quantidade']}" name="quantidade"></td>
        <td>
        <button onclick="del_serv_modal(${dado['id']}, 'del_pacote')">Excluir</button>
        </td>`

        tr.addEventListener('input', (event) => {
            const target = event.target;
             if (target.tagName === 'INPUT')  {
              inclui_pacote(dado['id']);
            }
        });

        tr.id = dado['id'];
        el.appendChild(tr);
    })

}

 function inclui_pacote(id){
    idPct.includes(id) ? null: idPct.push(id);
 }


//daqui para baixo agenda

const hora = document.querySelector('#horario');
hora ? hora.addEventListener('change', att) : null;

function auto_complete(retorno){
    let opt = document.querySelector('#servico');
    const dados_serv = JSON.parse(localStorage.getItem('servicos'));
    const id_serv = opt.value.split(', ');
    const serv = dados_serv.find(servico => servico['id'] == id_serv[0]);
    const el1 = document.getElementById('valor');
    const el2 = document.getElementById('desconto');
    if(id_serv[1] == 'pacote'){


        el1.readOnly = true;
        el2.readOnly = true;
        el1.classList = 'readonly';
        el2.classList = 'readonly';
    } else {

       if (el1.classList.contains('readonly')){
        el1.classList.remove('readonly');
        el1.readOnly = false;

        el2.classList.remove('readonly');
        el2.readOnly = false;
       }
    }
   return serv[retorno];
}
    //Traz o valor do horario final, baseado no tempo de serviço
 function att(){
const tempo = auto_complete('tempo');
    const hora_f = document.querySelector('#hora_fim');
    let [h1, m1] = hora.value.split(':').map(Number);
    let [h2, m2] = tempo.split(':').map(Number);
    const vlr = (h1 * 60 + m1) + (h2 * 60 + m2);
    let horas = Math.floor(vlr / 60);
    let minutos = vlr % 60;
    let hora_formt = ((horas < 10) ? '0' : '') + ((horas > 24) ? (horas - 24) : '') + 
    ((horas == 24) ? '00' : horas) + ':' + (minutos < 10 ? '0' : '') + minutos;
    hora_f.value = hora_formt;
    //console.log(hora_formt);

}
    //Traz o valor do serviço
const serv = document.querySelector('#servico');
    serv ? serv.addEventListener('change', att_serv) : null;
function att_serv(){
    const valor_obj = document.querySelector('#valor');
    const valor = auto_complete('valor');
    valor_obj.value = valor;
}

//VERIFICA SE O HORÁRIO ESTÁ DISPONÍVEL
function checa_horarios(hora_ini, hora_fi, dia){
    const tempo_ini = document.querySelector(hora_ini).value;
    const tempo_f = document.querySelector(hora_fi).value;
    const data = document.querySelector(dia).value;
    let id = '';
    if(typeof id_ag !== 'undefined'){
        id = id_ag;
    } else { id = ''}
    return chama_banco('checa_horarios', data, id, tempo_ini, tempo_f)
    .then(dados => {

      return dados[0]['agendamentos'] > 0 ? false : true;

         //return check;      
    })

   
}

function carrega(){
    
    checa_horarios('#horario', '#hora_fim', '#data').then(aux => {
        
        aux ? att_banco('add_agenda', 'meuform') : alerta('Erro: horário não disponível');
    })
}

/*function limpa_campos(){

    const form = document.querySelector('#meuform');
    const campos = form.querySelectorAll('input, select');

    campos.forEach(campo => {
        campo.name == "horario" || campo.name == "hora_fim" ? campo.selectedIndex = 0 : '';
        campo.name == "servico" ? campo.selectedIndex = 0 : ''; 

    })
}*/

function botao_agenda(valor){
    const horas = document.querySelector('#horario');
    horas.value = valor;
    const vlr_dia = document.getElementById('dia').value;
    const dia = document.getElementById('data');
    dia.value = vlr_dia;
}


//FINANCEIRO

const check_fi = document.getElementById('recorre');
check_fi ? 
check_fi.addEventListener('change', function(){
    this.checked ? this.value = 1 : this.value = 0;
}) : null

//PARTE DE GERAÇÃO DO RELATÓRIO

let idFin = [];
function gerar_relatorio(){
        let data = new FormData(document.getElementById("relat_form"));
        data = new URLSearchParams(data).toString();
        let totais = {total_saida: 0, total_entrada: 0, total_comicao: 0};
        const tipo_rel = document.getElementById('tipo_rel').value;
        const tabela = document.getElementById("tabela");
        let tipo_r = tipo_rel == 'SIMPLES' ? '<th>QUANTIDADE</th>' : "";

        const scro = document.querySelector('.oculto');
        scro ? scro.classList = "ativo scroll div_tab" : null;
 
        chama_banco("get_financeiro", data)
        .then(dados => {
            
            tabela.innerHTML = '';
            const relatorios = new Map();
            dados.forEach(dado=> {

                const profissional =  dado.profissional == '' || dado.profissional == null ? 'Geral' : dado.profissional + ' - ' + dado.fone;
                if(!relatorios.has(profissional)){
                    relatorios.set(profissional, []);
                } 
                relatorios.get(profissional).push(dado);

            })

            relatorios.forEach((relatorio, chave) => {
                let total_fun = 0;
                let comicao_vlr = 0;
                let entradaGerada = 0;
                const titulo = document.createElement("th");
                const campos = document.createElement("tr");
                titulo.className = 'titulo_relatorio';
                titulo.innerText = chave;
                titulo.colSpan = 7;

                campos.className = 'campos_relatorio'
                campos.innerHTML = `
                <th>NOME</th>
                <th>CLIENTE</th>
                <th>DATA</th>
                <th>TIPO</th>`
                + tipo_r + `
                <th>VALOR</th>
                <th>DESCONTO</th>
                <th>TOTAL</th>
                `;
                tabela.appendChild(titulo);
                tabela.appendChild(campos);
                relatorio.forEach(dado => {
                    const linha = document.createElement("tr");
                    const total_ = Number(dado['total']);
                    tipo_r = tipo_r != "" ? `<td>${dado['quantidade']}</td>` : "";
                    linha.innerHTML = `
                        <td>${dado['servico']}</td>
                        <td>${dado['cliente']}</td>
                        <td>${dado['dia']}</td>
                        <td>${dado['tipo']}</td>`
                        + tipo_r +`
                        <td>${dado['valor']}</td>
                        <td>${dado['desconto']}</td>
                        <td>${total_}</td>
                    `;
                    total_fun = total_;
                    comicao_vlr = Number(dado['comicao']) / 100;
                    switch(dado['tipo']){
                        case "SAIDA":totais.total_saida += total_;
                        break;

                        case "ENTRADA":totais.total_entrada += total_;
                        entradaGerada += total_;
                        break;

                        default: null;
                    }
                    tabela.appendChild(linha);
                });

                comicao_vlr = entradaGerada * comicao_vlr;
                totais.total_comicao += comicao_vlr;
                chave != "Geral" ? (() => {
                    const comicao = document.createElement("tr");
                    const entradaG = document.createElement("tr");
                    comicao.innerHTML = `
                        <th>Total de Comissão</th>
                        <td>R$ ${(comicao_vlr).toFixed(2)}</td>
                    `;

                    entradaG.innerHTML = `<th>Entrada Gerada</th>
                    <td>R$ ${entradaGerada.toFixed(2)}</td>`;
                    tabela.appendChild(comicao);
                    tabela.appendChild(entradaG);
                })() : null;
            })

         let html =   `<tr><td colSpan = 7;>TOTAL SAÍDA : R$ ${(totais.total_saida).toFixed(2)}</td></tr>
            <tr><td colSpan = 7;>TOTAL ENTRADA: R$ ${(totais.total_entrada).toFixed(2)}</td></tr>`;
            relatorios.size > 1 ? html += `<tr><td colSpan = 7;>TOTAL DE COMISSÕES: R$ ${(totais.total_comicao).toFixed(2)}</td></tr>` : null;
            tabela.insertAdjacentHTML('beforeend', html );
    
            
            idFin = [];

           // console.log(relatorios);
        })
}


function salva_fin(){
    const obj = idFin.map(id => {
        let element = document.getElementById(id);
        let objeto = {};
        let inputs = element.querySelectorAll('input');
        objeto["id"] = id;
        inputs.forEach(input => {
            objeto[input.name] = input.value;

        });
        idFin = [];
        return objeto;
        
});

    att_banco('alt_fin', obj);
}

function excluir_fin(id){

    const element = document.getElementById(id);
    element.remove();
    let obj = {id: id, status: 'CANCELADO'};
    att_banco('cancel_fin', obj);
    idFin = [];
}

//PARTE DE CLIENTES

function clientes(){
    const pesquisa = document.getElementById('pesquisa').value;
    chama_banco('get_cli', pesquisa).then(dados => {
  
        const lista = document.getElementById('lista-clientes');

        lista.innerHTML = "";
        dados.forEach(dado => {
            let card = document.createElement('form');
            card.id = dado['id'];
            card.classList.add('style-box', 'cli_card');
            const pcts = dado['PACOTES'];
    
            card.innerHTML = `
           <input type="hidden" name="id" value="${dado['id']}" readonly>
           <div>
            <label for="nome_cli">Nome:</label>
             <input type="text" name="nome" id="nome_cli" value="${dado['nome']}">
            </div><div>
            <label for="tel_cli">Telefone:</label>
            <input type="tel" name="telefone" id="tel_cli" value="${dado['telefone']}"  oninput="limitarTelefone(this), formatarTelefone(this)"
            onchange="validarTelefone(this)">
    </div><div>
            <p>Nascimento:</p>
            <p> ${dado['nascimento']}</p>
    </div><div>
            <label for="rua_cli">Rua: </label>
            <input type="text" name="rua" id="rua_cli" value="${dado['rua']}">
    </div><div>
            <label for="rua_num">N°: </label>
            <input type="number" name="rua_nu" id="rua_num" value="${dado['numero_casa']}" min=1>
    </div><div>
            <label for="bairro_cli">Bairro: </label>
            <input type="text" name="bairro" id="bairro_cli" value="${dado['bairro']}">
    </div>
           <h4>Pacotes Ativos</h4> `;
           
            const div = pcts[0]['id_pct'] ? monta_pacote(pcts) : monta_aviso();
  
            
    
            const bots = `<button type="button" onclick="modal_pacote(${dado['id']})">Ativar Pacote</button>
            <button type="button" onclick="att_banco('alt_cli', ${dado['id']})">Salvar Alterações</button>
            <button type="button" onclick="del_cliente_modal(${dado['id']})">Excluir</button>`;

            card.insertAdjacentElement( 'beforeend', div);
            card.insertAdjacentHTML( 'beforeend', bots);
    
            lista.appendChild(card);

        })
        //console.log(dados);
    }).catch(erro => {
        console.error('erro ao buscar', erro);
    })
   
}

function monta_pacote(pcts){
    const div = document.createElement('table');
    div.classList.add('pcts_ativos');
    div.innerHTML = `
        <tr><th>Pacote</th>
        <th>Quantidade</th>
        <th>Contratação</th>
        <th>Pagamento</th>
        </tr>
        `;

        pcts.forEach(pct =>{
                
            let html;
            html =
            `
                <td>${pct['nome_pct']}</td>
                <td>${pct['qtd_pct'] + '/' + pct['total_pct']}</td>
                <td>${pct['data_pct']}</td>
                <td>${pct['paga_pct']}</td>
            `
           
// html = '<p>Esse cliente não possui nenhum pacote ativo</p>';
            let pacotes = document.createElement('tr');
            pacotes.className = 'pacotes-card';
            pacotes.innerHTML = html;

            div.appendChild(pacotes);
     
        });
        return div;
}

function monta_aviso(){
    const div = document.createElement('p');
    div.innerText = 'Nenhum Pacote Ativo';
    return div;

}
function del_cliente_modal(id){
    const modal = document.getElementById('excluir_modal');
    const sim = document.getElementById('sim');
    sim.setAttribute('onclick', `apaga_cli(${id})`);
    modal.showModal();
}

function apaga_cli(id){
    const el = document.getElementById(id);
    el.remove();
    fecha_modal('excluir_modal');

    remove('del_cli', id);
}

function modal_pacote(id_cli){
   
    const modal = document.getElementById('pacote_modal');
    let hid = document.getElementById('hidden');
    const select = document.getElementById('pacote');

    chama_banco('get_servicos', 'pacotes').then(pacotes => {


        localStorage.setItem('pacotes', JSON.stringify(pacotes));
        hid.value = id_cli;


        select.innerHTML = "";
    
        pacotes.forEach(pacote => {

            const opt = document.createElement('option');
            opt.value = pacote['id'];
            opt.innerText = pacote['servico'];
    
            select.appendChild(opt);
        })

        select.addEventListener('change', att_pct);

        att_pct();
        modal.showModal();
    })
   
}

function att_pct(){

    const pcts = JSON.parse(localStorage.getItem('pacotes'));
    const vlr =document.getElementById('vlr');
    const total =document.getElementById('ttl_pct');
    const desconto = document.getElementById('desc').value;
    const pct_nome = document.getElementById('pacote').value;
    const ttl_vlr = document.getElementById('ttl_vlr');

    const vlrs = pcts.find((pct) => {
    return pct['id'] == pct_nome ? pct['valor'] : null;

    });
    vlr.innerText = 'Valor: ' + vlrs['valor'];
    total.innerText = 'Total: ' + (vlrs['valor'] - desconto);
    ttl_vlr.value = vlrs['valor'] - desconto;

}

//PARTE DE FUNCIONÁRIOS

function funcionarios(){
const lista = document.getElementById('lista-funcionarios');
chama_banco('get_fun').then(dados => {


    lista.innerHTML = "";
    dados.forEach(dado => {
        let card = document.createElement('form');
        card.id = dado['id'];
        card.classList.add('style-box' ,'func-lista');
    
        card.innerHTML = `
        
       <input type="hidden" name="id" value="${dado['id']}" readonly>

       <div>
        <label for="nome_fun">Nome: </label>
         <input type="text" name="nome" id="nome_fun" value="${dado['nome']}">
             </div><div>
        <p>Nasc:<br> ${dado['nascimento']}</p>
    </div><div>
        <label for="tel_fun">Telefone: </label>
        <input type="tel" name="telefone" id="tel_fun" value="${dado['telefone']}">
    </div><div>
        <label for="tel_fun2">Telefone 2: </label>
        <input type="tel" name="telefone2" id="tel_fun2" value="${dado['telefone_2']}">

    </div><div>
        <label for="rua_fun">Rua: </label>
        <input type="text" name="rua" id="rua_fun" value="${dado['rua']}">
    </div><div>
        <label for="rua_num">N°: </label>
        <input type="number" name="numero" id="rua_num" value="${dado['numero_casa']}">
    </div><div>
        <label for="bairro_fun">Bairro: </label>
        <input type="text" name="bairro" id="bairro_fun" value="${dado['bairro']}">
    </div><div>
        <label for="func">Função: </label>
        <input type="text" name="funcao" id="func" value="${dado['funcao']}">
        </div><div>
        <label for="salar">Salário: </label>
        <input type="number" name="salario" id="salar" value="${dado['salario']}">
</div><div>
        <label for="comic">Comissão: </label>
        <input type="number" name="comicao" id="comid" value="${dado['comicao']}">
        </div>
        <button type="button" onclick="att_banco('paga_fun', ${dado['id']})">Pagar</button>
        <button type="button" onclick="att_banco('alt_fun', ${dado['id']})">Salvar Alterações</button>
        <button type="button" onclick="del_func_modal(${dado['id']})">Excluir</button>
        `;
        
        lista.appendChild(card);
    })
    //console.log(dados);
})

}

function del_func_modal(id){
    const modal = document.getElementById('excluir_modal');
    const sim = document.getElementById('sim');
    sim.setAttribute('onclick', `apaga_fun(${id})`);
    modal.showModal();
}

function apaga_fun(id){
    const el = document.getElementById(id);
    el.remove();
    fecha_modal('excluir_modal');

    remove('del_fun', id);
}

//JÁ NEM SEI MAIS OQUE É
//MODAL DE BUSCA DE CLIENTE

let idCli = [];

function modal_cli(){
    document.getElementById('modal_busca').showModal();
    const input = document.getElementById('campo_buscar');

    input.addEventListener('input', function(){
        const inpt = input.value;
        this.value.length >= 3 ? monta_card(inpt) : null;
    });
}

function monta_card(input){
    
    const cards = document.getElementById('cards');
    chama_banco('get_cli', input).then(dados => {
        cards.innerHTML = '';
        idCli = dados;

        dados.forEach(dado => {

            const div = document.createElement('div');
            div.innerHTML = `
            <p>${dado['nome']}</p>
            <p>${dado['nascimento']}</p>
            <p>${dado['telefone']}</p>
            <button onclick="seleciona_cli(${dado['id']})">Selecionar</button>
            `;

            cards.appendChild(div);
        })
    })
}

function seleciona_cli(id){

    document.getElementById('campo_buscar').value = '';
    document.getElementById('cards').innerHTML = '';
    const servicos = document.getElementById('servico');
    const cliente = document.getElementById('cliente');
    const telefone = document.getElementById('cli_tel');
    const element = document.getElementById('meuform');
    const ipt = document.createElement('input');
    ipt.name = 'id_cli';
    ipt.type = 'hidden';
    ipt.value = id;
    ipt.readOnly = true;
    ipt.id = 'id_cliente';
    const dadosCli = idCli.find(dados => dados.id == id);


    const pacotes = dadosCli['PACOTES'];
    get_serv('cliente', id);

    cliente.value = dadosCli['nome'];

    telefone.readOnly = true;
    telefone.value = dadosCli['telefone'];
    element.insertAdjacentElement('afterbegin', ipt);
    fecha_modal('modal_busca');
}

// PARTE DE SEPARAÇÃO DE NIVEIS


function ocultar(){

    const el = document.getElementById('busca_cli');
    el.remove();
    }
    
    function funcio(){
        const el = document.getElementById('meuform');
        const select = document.createElement('select');
        select.id = 'funcio';
        select.name = 'funcionario';

        const opt = document.createElement('option');
        opt.innerText = '';

        const div = document.createElement('div');
        div.innerHTML = '<label for="funcio">Responsável</label>';
        
        const bloco = document.getElementById('cabecalho');

        const filho = el.children[3];
        select.appendChild(opt);

        chama_banco('get_fun').then(dados => {
           
           dados.forEach(dado => {

            const option = document.createElement('option');
            option.value = dado['id'];
            option.innerText = dado['nome'];
            select.appendChild(option);
           })
           div.appendChild(select);
           el.insertBefore(div, filho);
           const select2 = select.cloneNode(true);
           select2.id = "funcionario-bloco";
           bloco.appendChild(select2);
           
           select2.addEventListener('change', function(){ troca_agenda(this.value);})
        })
    }

    function prevent(event, funcao, form){
       event.preventDefault();


       if(document.getElementById('hora_fim') && document.getElementById('horario') && form == 'meuform'){
        
       const fim = document.getElementById('hora_fim');
       const inicio = document.getElementById('horario').value;
        if(inicio > fim.value || inicio == fim.value){
            alert('O horário final não pode ser menor ou igual ao horário inicial');
        } else {
            carrega();
        }
       } else {

        att_banco(funcao, form);
       }
      
       
    }

    function troca_agenda(id){

        const dia = document.getElementById('dia').value;
        armazena_agenda(dia, id);
    }

    function validaCPF_CNPJ(input){

        const cpfRegex = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
        const cnpjRegex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;

        if(!cpfRegex.test(input.value) && !cnpjRegex.test(input.value) ){
            console.log(input.value);
            alert("Por favor, insira um CPF/CNPJ válido");
            // Limpa o valor do input
            input.select();
        }
    }

    function formatacpf_cnpj(input){

          // Obter o valor atual do campo
    let valor = input.value;
    // Verificar se o valor excede o limite máximo
    console.log(input.value);
    if (valor.length > 18) {
        // Se exceder, definir o valor do campo para os primeiros 14 caracteres
        input.value = valor.slice(0, 18);
      
    }
    
    }

    function validaNasc(input){
       const regexNasc =  /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/([0-9]{4})$/;

       if(!regexNasc.test(input.value)){
        alert("Por favor, insira uma data de nascimento válida");
        // Limpa o valor do input
        input.select();
       }
    }

    function criaLink(id){
        input = document.getElementById('agendamento');
        input.value = 'AQUI VAI A URL' + id;
    }

    function cria_lista(){
       let dia = document.getElementById('dia').value;
        const id = id_ag;
        chama_banco('get_agendamentos', dia, id).then(agendados => {
            let box = document.querySelectorAll('.item');
            let todosHorarios = Array.from(box).map(item => item.dataset.value);
            let add = document.createElement('button');
            let horaDisp = [];
            let horaDisp2 = [];
            add.textContent = 'Adicionar';
            const dado = document.createElement('p');
            box.forEach(item => {
                item.classList.remove('item-border');
                
            });
            agendados.forEach((item, index) =>{
        
              
               const inicioIndex = todosHorarios.findIndex(element => element === item.hora);
               const fimIndex = todosHorarios.findIndex(element => element === item.ate);
               const remover = todosHorarios.findIndex(element => element > item.hora && element < item.ate);
               horaDisp = horaDisp.concat(todosHorarios.slice(inicioIndex, fimIndex));
                const alturaBox = 198;
                const boxAgendado = document.createElement('div');
                boxAgendado.classList.add('marcado');
                boxAgendado.id = item.id;
              // box[inicioIndex].style.height = `${alturaBox}px`;
                box[inicioIndex].classList.add('marcado_item');
                boxAgendado.innerHTML = 
                `<h2>INDISPONÍVEL</h2>
                `;
        
                box[inicioIndex].appendChild(boxAgendado);
                
                horaDisp.forEach((hora, x) => {
        
                 
                    if(hora > item.hora && hora < item.ate){
                        const teste = document.querySelectorAll(`div[data-value="${hora}"]`);
              
                        if(teste){
                            teste.forEach(teste => {
                               teste.remove();
                            })
                        }
                
                    }
                 
                })
        
               
                
                 
        }); 
            const exclusivos = todosHorarios.filter(element => !horaDisp.includes(element));
            const exclusivo = exclusivos.map(item => {
                return todosHorarios.findIndex(element => element === item);
            });
        
            //CRIA O BOTÃO DE AGENDAR
            exclusivo.forEach((item, index) => {
                const valores = box[item];
                const p = document.createElement('span');
                p.innerText = valores.dataset.value;
                p.classList.add('marcador');
                const botao = document.createElement('button');
                botao.innerText = 'Agendar';
                botao.className = 'agen';
                botao.onclick = function(){ botao_agenda(valores.dataset.value)};
                valores.appendChild(p);
                valores.appendChild(botao);
            })
        })
    
    }

    async function lista_disponivel() {
        try {
            await armazena_horarios(1); // Aguarda até que os horários sejam armazenados no localStorage
            horarios(); // Chama a função para preencher os horários no select
            await agenda(); // Aguarda a função agenda()
            await get_serv('simples', id_ag);
            // Supondo que cria_lista() seja uma função síncrona que não requer await
            cria_lista(); // Cria a lista com o dia e o id fornecidos
        } catch (error) {
            console.error('Ocorreu um erro:', error);
        }
    }