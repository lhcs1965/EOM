$(function () {
    $("#upload").bind("click", function () {
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xml)$/;
        if (regex.test($("#fileUpload").val().toLowerCase())) {
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var xmlDoc = $.parseXML(e.target.result);
                    var nfe = $(xmlDoc).find("emit");
                    //var nfe = $(xmlDoc).find("cobr");

                    //Create a HTML Table element.
                    var table = $("<table />");
                    table[0].border = "1";

                    //Add the header row.
                    var row = $(table[0].insertRow(-1));
                    nfe.eq(0).children().each(function () {
                        var headerCell = $("<th />");
                        headerCell.html(this.nodeName);
                        row.append(headerCell);
                    });

                    //Add the data rows.
                    $(nfe).each(function () {
                        row = $(table[0].insertRow(-1));
                        $(this).children().each(function (e) { // <-- adicione 'e' como parâmetro para pegar os índices do laço
                            var cell = $("<td />");
                            cell.html($(this).text());
                            row.append(cell);
                        //$($("input:text")[e]).val($(this).text()); // <-- insere valores nos campos
                        });
                    });
  
                    var dvTable = $("#dvTable");
                    dvTable.html("");
                    dvTable.append(table);
                }
                reader.readAsText($("#fileUpload")[0].files[0]);
            } else {
                alert("Este browser não suporta HTML5.");
            }
        } else {
            alert("Por favor faça Upload de um arquivo XML valido.");
        }
    });
});