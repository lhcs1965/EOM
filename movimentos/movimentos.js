const APP = "movimentos"
const DIR = APP + "/" + APP 
const GET = DIR + "-get.php"
const PUT = DIR + "-put.php"
const NEW = DIR + "-new.php"
const DEL = DIR + "-del.php"
var id = 0;
var action=""

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

function import_nfe(){
    const upload  = new bootstrap.Modal(document.getElementById("upload-dialog"))
    var fornecedor = ""
    var fatura = ""
    upload.show()
    $(function () {
        $("#upload").bind("click", function () {
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xml)$/
            if (regex.test($("#fileUpload").val().toLowerCase())) {
                if (typeof (FileReader) != "undefined") {
                    var reader = new FileReader()
                    reader.onload = function (e) {
                        var xmlDoc = $.parseXML(e.target.result)
                        var emit = $(xmlDoc).find("emit")
                        var emis = $(xmlDoc).find("ide")
                        var cobr = $(xmlDoc).find("cobr")
    
                        fornecedor += emit[0].children[0].textContent + "|"
                        fornecedor += emit[0].children[1].textContent + "|"
                        fornecedor += emit[0].children[2].textContent + "|"
                        fornecedor += emis[0].children[6].textContent.substr(0,10)
                        $(cobr).each(function () {
                            $(this).children().each(function (e) {
                                var cols = $(this).text().replace(/ /g,'').replace("\n"," ").trim().split("\n")
                                fatura += cols[0] + "|" + cols[1] + "|" + cols[2] + ";"
                            })
                        })
                        $.ajax({
                            method: "POST",
                            url: NEW,
                            data: {
                                fornecedor: fornecedor,
                                fatura: fatura,
                            },
                            success: function(data){
                                table.draw()
                            }
                        })
                    }
                    reader.readAsText($("#fileUpload")[0].files[0])
                } else {
                    alert("Este browser não suporta HTML5.")
                }
            } else {
                alert("Por favor faça Upload de um arquivo XML valido.")
            }
        })
    })
}

function delete_objects(){
    const ids = get_selected()
    if(confirm("Confirma exclusão das linhas selecionadas?")){
        $.ajax({
            method: "POST",
            url: DEL,
            data: {
                ids: ids,
            },
            success: function(data){
                table.draw()
            }
        })
    }
}

function troca_empresa(){

}

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
            className: "edit-icon align-middle",
            orderable: false,
            data:      null,
            defaultContent: "",
            width: 5,
            render: function(data,type,row){
                return '<div class="btn btn-group btn-secondary"><img src="img/pencil.svg"></div>'
            },
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
        {
            title:'conta_id',
            data:13,
            visible:false,
        },
        {
            title:'fornecedor_id',
            data:14,
            visible:false
        }
    ],
})

$('#data-table').on('click','td.edit-icon',function(){ //td.dt-control
    var tr = $(this).closest('tr')
    var row = table.row(tr)
    var x=row.data()
    edit(row.data())
})

$('#data-table').on('click','td.dt-control',function(){
    var tr = $(this).closest('tr')
    var row = table.row(tr)
    var x=row.data()
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


