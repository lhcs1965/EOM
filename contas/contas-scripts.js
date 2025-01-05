const APP = "contas"
const DIR = APP + "/" + APP 
const GET = DIR + "-get.php"
const PUT = DIR + "-put.php"
const NEW = DIR + "-new.php"
const DEL = DIR + "-del.php"

function get_fix_filter(){
    const result = "?empresa=" + document.getElementById("empresa").innerHTML
                 + "&atual=" + document.getElementById("cb-ano-atual").checked
                 + "&anterior=" + document.getElementById("cb-ano-anterior").checked
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

function troca_empresa(){

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
    columns:[
        {
            data:0,
            title: "Ano",
        },
        {
            data:14,
            title: "Conta",
        },
        {
            data:1,
            title: "Janeiro",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:2,
            title: "Fevereiro",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:3,
            title: "Março",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:4,
            title: "Abril",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:5,
            title: "Maio",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:6,
            title: "Junho",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:7,
            title: "Julho",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:8,
            title: "Agosto",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:9,
            title: "Setembro",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:10,
            title: "Outubro",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:11,
            title: "Novembro",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:12,
            title: "Dezembro",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },
        {
            data:13,
            title: "Total Anual",
            orderable: false,
            className: 'text-end',
            render: DataTable.render.number(".",",",2,"R$ ","  "),
        },

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
