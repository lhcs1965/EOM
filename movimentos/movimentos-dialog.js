const dialog  = new bootstrap.Modal(document.getElementById("dialog"))

const documento     = document.getElementById("documento")
const emissao       = document.getElementById("emissao")
const descricao     = document.getElementById("descricao")
const fornecedor    = document.getElementById("fornecedor")
const valor         = document.getElementById("valor")
const vencimento    = document.getElementById("vencimento")
const pagamento     = document.getElementById("pagamento")
const conta         = document.getElementById("conta")
const obs           = document.getElementById("obs")
const conta_id      = document.getElementById("conta_id")
const fornecedor_id = document.getElementById("fornecedor_id")


const values = {
    "documento"     : "",
    "emissao"       : "",
    "descricao"     : "",
    "fornecedor"    : "",
    "valor"         : "",
    "vencimento"    : "",
    "pagamento"     : "",
    "conta"         : "",
    "obs"           : "",
    "conta_id"      : "",
    "fornecedor_id" : "",
}

async function update(field,value){
    $.ajax({
        method: "POST",
        url: PUT,
        data: {
            id    : id,
            field : field,
            value : value,
        },
        success: function(data){
            values[field] = value
            id = parseInt(JSON.parse(data).msg)
            table.draw()
        }
    })
}

function save(field,required){
    const current = document.getElementById(field)
    const new_value = current.value
    const old_value = values[field]
    if(required==1 && new_value==""){
        alert("Preenchimento Obrigatório")
        current.value = old_value
        current.focus()
        return
    }
    if(new_value!=old_value){
        update(field,new_value)
    }
}

function edit(row){
    id = row[0]
    values["documento" ] = row[7]
    values["emissao"   ] = row[6]
    values["descricao" ] = row[9]
    values["fornecedor"] = row[8]
    values["valor"     ] = row[3]
    values["vencimento"] = row[1]
    values["pagamento" ] = row[2]
    values["conta"     ] = row[5]
    values["obs"       ] = row[12]
    documento.value      = row[7]
    emissao.value        = row[6]
    descricao.value      = row[9]
    fornecedor.value     = row[8]
    valor.value          = row[3]
    vencimento.value     = row[1]
    pagamento.value      = row[2]
    conta.value          = row[5]
    obs.value            = row[12]
    document.getElementById("conta_id").value=row[13]
    document.getElementById("fornecedor_id").value=row[14]
    dialog.show()
}

function insert(){
    const date = new Date()
    edit([0,date.getDate(),date.getDate(),0,null,1,date.getDate(),null,1,null,null,null,null])
}
