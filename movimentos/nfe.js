$(function () {
    $("#upload").bind("click", function () {
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xml)$/;
        if (regex.test($("#fileUpload").val().toLowerCase())) {
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var xmlDoc = $.parseXML(e.target.result);
                    var emit = $(xmlDoc).find("emit")
                    var emis = $(xmlDoc).find("ide")

                    document.getElementById("cpf_cnpj"     ).value = emit[0].children[0].textContent
                    document.getElementById("razao_social" ).value = emit[0].children[1].textContent
                    document.getElementById("nome_fantasia").value = emit[0].children[2].textContent
                    document.getElementById("emissao"      ).value = emis[0].children[6].textContent.substr(0,10)

                    var cobr = $(xmlDoc).find("cobr");

                    //Create a HTML Table element.
                    html="<table><tr><th>Doc</th><th>Data</th><th>Valor</th></tr>"
                    $(cobr).each(function () {
                        $(this).children().each(function (e) {
                            var cols = $(this).text().replace(/ /g,'').replace("\n"," ").trim().split("\n")
                            var n = "<td>"+cols[0]+"</td>"
                            var d = "<td>"+cols[1]+"</td>"
                            var v = "<td>"+cols[2]+"</td>"
                            html += "<tr>"+n+d+v+"</tr>"
                        });
                    });
                    html += "</table>"
                    document.getElementById("dvTable").innerHTML=html
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