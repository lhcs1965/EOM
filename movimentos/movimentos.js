const APP = "movimentos"
const DIR = APP + "/" + APP 
const GET = DIR + "-get.php"
const PUT = DIR + "-put.php"
const NEW = DIR + "-new.php"
const DEL = DIR + "-del.php"
const dialog  = new bootstrap.Modal(document.getElementById("dialog"))

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

async function update_movimentos(field,action,ids){
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
    update_movimentos(field,action,ids)
}

function edit_dialog(){
    var rows = table.rows()
    rows.every(function(rowIndex,tableLoop,rowLoop){
        var row = this.data()
        var ind = row[0]
        var sel = document.getElementById("sel"+ind)
        if(sel.checked){
            document.getElementById("documento").value = row[7]
            document.getElementById("emissao").value = row[6]
            document.getElementById("descricao").value = row[9]
            document.getElementById("fornecedor").value = row[8]
            document.getElementById("valor").value = row[3]
            document.getElementById("vencimento").value = row[11]
            document.getElementById("pagamento").value = row[2]
            document.getElementById("conta").value = row[5]
            document.getElementById("obs").value = row[12]
            dialog.show()
            // stop
        }
    })
}

function troca_empresa(){

}

// const confirm_dialog = new bootstrap.Modal(document.getElementById("confirm-dialog"))
// const local_filter = document.getElementById("local_filter").innerHTML
// const owner_filter = document.getElementById("owner_filter").innerHTML
// const table_filter = document.getElementById("table_filter").innerHTML
// const field_filter = document.getElementById("field_filter").innerHTML
var action=""
// var clear_local_filter = document.getElementById("clear-local-filter")

// clear_local_filter.addEventListener("click",function(){
//     set_filter('local_filter','')
// })

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

// window.addEventListener("load",function() {
//     document.getElementById("hide-local-filter").toggleAttribute("hidden",document.getElementById("local_filter").innerHTML=="")
//     document.getElementById("hide-owner-filter").toggleAttribute("hidden",document.getElementById("owner_filter").innerHTML=="")
//     document.getElementById("hide-table-filter").toggleAttribute("hidden",document.getElementById("table_filter").innerHTML=="")
//     document.getElementById("hide-field-filter").toggleAttribute("hidden",document.getElementById("field_filter").innerHTML=="")
//     // document.getElementById("hide-"+filter_name).toggleAttribute("hidden",filter_value=="")
// })

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
            // className: 'dt-right',
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
                    // render:function(data,type,row){
            //     const ret = '<div class="align-items-center d-flex">' 
            //               + '<div class="btn-group" role="group">'
            //               + '<img class="btn btn-sm btn-warning" '
            //               + 'data-bs-toggle="modal" data-bs-target="#note-dialog" '
            //               + 'src="/img/edit_16.svg" onclick="show_note_dialog('
            //               + row[0]
            //               + ')"></div>&#160;'
            //               + data 
            //               + '</div>'
            //     return ret
            // }

                    // render:function(data,type){
            //     const ret = '<div class="align-items-center d-flex">' 
            //               + '<div class="btn-group" role="group">'
            //               + '<img class="btn btn-sm btn-warning" '
            //               + 'src="/img/arrow-right-square.svg" '
            //               + 'onclick="javascript:location.href=' //\'owner-index.php?local_filter='
            //               + '\'page.php?page=owner&menu=SCHEMAS&over=Tabelas&local_filter='
            //               + data
            //               + '\'"></div>&#160;'
            //               + data 
            //               + '</div>'
                
            //     return ret
            // }

    ],
})

$('#data-table').on('click','td.dt-control',function(){
    var tr = $(this).closest('tr')
    var row = table.row(tr)
    if(row.child.isShown()){
        row.child.hide()
        tr.removeClass('shown')
    }
    else{
        row.child(format_child(row.data())).show()
        tr.addClass('shown')
    }
})

function format_child(d){
    const result = 
        "<dl>" +
        "<dd>" + d[11] + "</dd>" +
        "<dd>" + d[12] + "</dd>" +
        "</dl>"+
        "<dl>Emitada em:<dd>"+d[6]+"</dd:</dl>"
    return(result)
}

async function show_note_dialog(id) {
    const data = await fetch("db/get-"+APP+"-note.php?id=" + id)
    const cols = await data.json()

    document.getElementById("local-id").innerHTML = cols["data"].local_id
    document.getElementById("local-host").value = cols["data"].local_host
    document.getElementById("local-name").value = cols["data"].local_name
    document.getElementById("local-type").value = cols["data"].local_type
    document.getElementById("local-user").value = cols["data"].local_user
    document.getElementById("local-pass").value = cols["data"].local_pass
    document.getElementById("local-note").value = cols["data"].local_note
    document.getElementById("local-osql").value = cols["data"].local_osql
    document.getElementById("local-tsql").value = cols["data"].local_tsql
    document.getElementById("local-fsql").value = cols["data"].local_fsql
    document.getElementById("note-dialog-title").innerHTML = "EDITAR SERVIDOR"

    note_dialog.show()
}

async function update_note(){
    const local_id = document.getElementById("local-id").innerHTML
    const local_host = document.getElementById("local-host").value
    const local_name = document.getElementById("local-name").value
    const local_type = document.getElementById("local-type").value
    const local_user = document.getElementById("local-user").value
    const local_pass = document.getElementById("local-pass").value
    const local_note = document.getElementById("local-note").value
    const local_osql = document.getElementById("local-osql").value
    const local_tsql = document.getElementById("local-tsql").value
    const local_fsql = document.getElementById("local-fsql").value
    const target_url = function(){
        if(document.getElementById("note-dialog-title").innerHTML=="EDITAR SERVIDOR"){
            return "db/put-local-note.php"
        }else{
            return "db/post-local-note.php"
        }
    }

    $.ajax({
        method: "POST",
        url: target_url(),
        data: {
            id: local_id,
            host: local_host,
            name: local_name,
            type: local_type,
            user: local_user,
            pass: local_pass,
            note: local_note,
            osql: local_osql,
            tsql: local_tsql,
            fsql: local_fsql
        },
        success: function(data){
            table.draw()
        }
    })
}


// function select_all(){
//     var rows = table.rows()
//     rows.every(function(rowIndex,tableLoop,rowLoop){
//         var row = this.data()
//         var ind = row[0]
//         var sel = document.getElementById("sel"+ind)
//         sel.checked = !sel.checked
//     })
// }

// function import_objects(act){
//     document.getElementById("cd-header").innerHTML = "IMPORTAR NOVOS SCHEMAS"
//     document.getElementById("cd-body").innerHTML = "Importar Schemas dos servidores selecionados?<BR><BR>Esta operação poderá demorar vários minutos!"
//     action = act
//     confirm_dialog.show()
// }

// function delete_objects(act){
//     document.getElementById("cd-header").innerHTML = "EXLUIR SERVIDORES"
//     document.getElementById("cd-body").innerHTML = "Excluir Servidores selecionados?<BR><BR>Todos os Schemas, Tabelas e Colunas também serão excluídos!"
//     action = act
//     confirm_dialog.show()
// }

// function confirm(){
//     if(action == "import"){
//         search_all()
//     } else if(action == "delete"){
//         delete_all()
//     }
// }

// function insert_objects(){
//     document.getElementById(APP+"-id").innerHTML = null
//     document.getElementById(APP+"-host").value = null
//     document.getElementById(APP+"-name").value = null
//     document.getElementById(APP+"-type").value = null
//     document.getElementById(APP+"-user").value = null
//     document.getElementById(APP+"-pass").value = null
//     document.getElementById(APP+"-note").value = null
//     document.getElementById(APP+"-osql").value = null
//     document.getElementById(APP+"-tsql").value = null
//     document.getElementById(APP+"-fsql").value = null
//     document.getElementById("note-dialog-title").innerHTML = "NOVO SERVIDOR"

//     note_dialog.show()
// }



// function search_all(){
//     var rows = table.rows()
//     var items = []
//     rows.every(function(rowIndex,tableLoop,rowLoop){
//         var row = this.data()
//         var ind = row[0]
//         var sel = document.getElementById("sel"+ind)
//         if(sel.checked){
//             items.push(row[0])
//         }
//     })
//     window.location.href = "found-owner-index.php?items=" + items.toString()
// }

// function delete_all(){
//     var rows = table.rows()
//     rows.every(function(rowIndex,tableLoop,rowLoop){
//         var row = this.data()
//         var ind = row[0]
//         var sel = document.getElementById("sel"+ind)
//         if(sel.checked){
//             delete_item(ind)
//         }
//     })
// }

// async function delete_item(id){
//     search_dialog.show()
//     const data = await fetch("db/delete-"+APP+".php?id="+id)
//     search_dialog.hide()
//     table.draw()
// }


