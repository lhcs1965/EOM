const APP = "movimentos"
const DIR = APP + "/" + APP 
const GET = DIR + "-get.php"
const PUT = DIR + "-put.php"
const NEW = DIR + "-new.php"
const DEL = DIR + "-del.php"

const dialog  = new bootstrap.Modal(document.getElementById("dialog"))

const documento  = document.getElementById("documento")
const emissao    = document.getElementById("emissao")
const descricao  = document.getElementById("descricao")
const fornecedor = document.getElementById("fornecedor")
const valor      = document.getElementById("valor")
const vencimento = document.getElementById("vencimento")
const pagamento  = document.getElementById("pagamento")
const conta      = document.getElementById("conta")
const obs        = document.getElementById("obs")

const values = {
    "documento"  : "",
    "emissao"    : "",
    "descricao"  : "",
    "fornecedor" : "",
    "valor"      : "",
    "vencimento" : "",
    "pagamento"  : "",
    "conta"      : "",
    "obs"        : "",
}
var id = 0;

function get_fix_filter(){
    const result = "?empresa=" + document.getElementById("empresa").innerHTML
                 + "&quitada=" + document.getElementById("cb-quitada").checked
                 + "&vencida=" + document.getElementById("cb-vencida").checked
                 + "&hoje=" + document.getElementById("cb-hoje").checked
                 + "&amanha=" + document.getElementById("cb-amanha").checked
                 + "&semana=" + document.getElementById("cb-semana").checked
                 + "&proxima=" + document.getElementById("cb-proxima").checked
                 + "&breve=" + document.getElementById("cb-breve").checked
                 + "&conta=" + document.getElementById("cb-conta").checked
                 + "&fornecedor=" + document.getElementById("cb-fornecedor").checked
    return(result)
}

function set_fix_filter(){
    reload = GET + get_fix_filter()
    table.ajax.url(reload).load()
}

function select_all(){
    var rows = table.rows()
    rows.every(function(rowIndex,tableLoop,rowLoop){
        var row = this.data()
        var ind = row[0]
        var sel = document.getElementById("sel"+ind)
        sel.checked = !sel.checked
    })
}

function get_selected(){
    var rows = table.rows()
    var ids = []
    rows.every(function(rowIndex,tableLoop,rowLoop){
        var row = this.data()
        var ind = row[0]
        var sel = document.getElementById("sel"+ind)
        if(sel.checked){
            ids.push(ind)
        }
    })
    return ids.join(",")
}

async function update_action(field,action,ids){
    $.ajax({
        method: "POST",
        url: PUT,
        data: {
            field: field,
            action: action,
            ids: ids,
        },
        success: function(data){
            table.draw()
        }
    })
}

function apply_action(field,action){
    const ids = get_selected()
    update_action(field,action,ids)
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
            table.draw()
        }
    })
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
    dialog.show()
}

function save(field){
    const new_value = document.getElementById(field).value
    const old_value = values[field]
    if(new_value!=old_value){
        update(field,new_value)
    }
}

function troca_empresa(){

}

var action=""

function delete_objects(page){
    var rows = table.rows()
    rows.every(function(rowIndex,tableLoop,rowLoop){
        var row = this.data()
        var ind = row[0]
        var sel = document.getElementById("sel"+ind)
        if(sel.checked){
            delete_item(page,ind)
        }
    })
}

async function delete_item(page,id){
    // search_dialog.show()
    // const data = await fetch("db/delete-" + page + ".php?id=" + id)
    // table.draw()
    $.ajax({
        method: "DELETE",
        url: "db/delete-" + page + ".php?id=" + id,
        success: function(data){
            table.draw()
        }
    })
}

function import_objects(act){
    document.getElementById("cd-header").innerHTML = "IMPORTAR NOVAS COLUNAS"
    document.getElementById("cd-body").innerHTML = "Importar Colunas das Tabelas selecionados?<BR><BR>Esta operação poderá demorar vários minutos!"
    action = act
    confirm_dialog.show()
}

function btn_filter(name,data){
    const ret = '<div class="align-items-center d-flex">' 
              + '<div class="btn-group" role="group">'
              + '<img class="btn btn-sm btn-warning" '
              + 'src="/img/funnel.svg" '
              + 'onclick="set_filter'
              + "('"+name+"','" + data + "')"
              + '"></div>&#160;'
              + data 
              + '</div>'
    return ret
}



function set_filter(filter_name,filter_value){
    const hide_filter = "hide-"+filter_name.replace("_","-")
    document.getElementById(hide_filter).toggleAttribute("hidden",filter_value=="")
    document.getElementById(filter_name).innerHTML = filter_value
    reload = "db/get-"+APP+".php"+get_fix_filter()
    table.ajax.url(reload).load()
    // const url = APP + "-index.php" + get_fix_filter()
    // window.location.replace(url)
}

function search_all(){
    var rows = table.rows()
    var items = []
    rows.every(function(rowIndex,tableLoop,rowLoop){
        var row = this.data()
        var ind = row[0]
        var sel = document.getElementById("sel"+ind)
        if(sel.checked){
            items.push(row[0])
        }
    })
    window.location.href = "found-field-index.php?items=" + items.toString()
}

var table = $('#data-table').DataTable({
    processing : true,
    serverSide : true,
    ordering: true,
    paging : true,
    pageLength: 20,
    pagingType : 'first_last_numbers',
    lengthMenu: [
        [10,15,20,25,50,100,250,500],
        [10,15,20,25,50,100,250,500]
    ],
    searching: true,
    search:{
        return:true
    },
    scrollCollapse: false,
    ajax : GET + get_fix_filter(),
    language : { url: 'config/pt-BR.json' },
    columnDefs: [{ targets: '_all', className:"align-middle"}],
    order:[],
    createdRow: function( row, data, dataIndex){
        if(data[10] == 2){
            $(row).addClass('fw-bold')
        } else if(data[10] == 1){
            $(row).addClass('fw-bold bg-danger-subtle')
        }
    },
    columns:[
        {
            data:0,
            title: '<input type="checkbox" id="sel" onclick="select_all()">',
            orderable: false,
            render:function(data,type,row){
                return '<input type="checkbox" id="sel'+data+'">'
            },
            width: 3,
        },
        {
            data:0,
            title:'ID',
            orderable: false,
            visible: false
        },
        {
            data:1,
            title:'VENCIMENTO',
            visible:true,
            render: DataTable.render.datetime('DD-MM-YYYY'),
            width: 50,
            className: "text-start",
        },
        {
            data:2,
            title:'PAGAMENTO',
            render: DataTable.render.datetime('DD-MM-YYYY'),
            width: 50,
            className: "text-start",
        },
        {
            data:3,
            title: "VALOR",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:4,
            title: "TIPO",
        },
        {
            data:5,
            title: "CONTA",
        },
        {
            data:6,
            title: "EMISSÃO",
            render: DataTable.render.datetime('DD-MM-YYYY'),
            visible: false,
        },
        {
            data:7,
            title:'DOCUMENTO',
        },
        {
            data:8,
            title: "FORNECEDOR",
        },
        {
            data:9,
            title: "DESCRIÇÃO",
        },
        {
            data:10,
            title: "VENCE",
            orderable: true,
            render: function(data,type,row){
                const status=[
                    'Quitada',
                    'VENCIDA',
                    'Hoje',
                    'Amanhã',
                    'Nesta Semana',
                    'Na próxima Semana',
                    'Em breve'
                ]
                if(data==1){
                    return '<div class="text-danger fw-bold">'+status[data]+'</div>'
                } else {
                    return status[data]
                }
            }
        },
        {
            className: "dt-control align-middle",
            orderable: false,
            data:      null,
            defaultContent: "",
            width: 5
        },
        {
            data:11,
            title:'EMPRESA',
            orderable: false,
            visible: false,
        },
        {
            data:12,
            title:'OBSERVAÇÕES',
            orderable: false,
            visible: false,
        },
    ],
})

$('#data-table').on('click','td.dt-control',function(){
    var tr = $(this).closest('tr')
    var row = table.row(tr)
    if(row.child.isShown()){
        row.child.hide()
        tr.removeClass('shown')
        dialog.hide()
    }
    else{
        row.child(format_child(row.data())).show()
        tr.addClass('shown')
        }
})

function format_child(row){
    const result=
        "<dl>" +
        "<dd>" + row[11] + "</dd>" +
        "<dd>" + row[12] + "</dd>" +
        "</dl>"+
        "<dl>Emitada em:<dd>"+row[6]+"</dd></dl>"
    edit(row)
    return(result)
}

