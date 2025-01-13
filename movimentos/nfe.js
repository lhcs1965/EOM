var cpf_cnpj = ""
var razao_social = ""
var nome_fantasia = ""
var emissao = ""
var fatura = ""
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

                    cpf_cnpj      = emit[0].children[0].textContent
                    razao_social  = emit[0].children[1].textContent
                    nome_fantasia = emit[0].children[2].textContent
                    emissao       = emis[0].children[6].textContent.substr(0,10)
                    $(cobr).each(function () {
                        $(this).children().each(function (e) {
                            var cols = $(this).text().replace(/ /g,'').replace("\n"," ").trim().split("\n")
                            fatura += cols[0] + "|" + cols[1] + "|" + cols[2] + ";"
                        })
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
console.log(cpf_cnpj)
console.log(razao_social)
console.log(nome_fantasia)
console.log(emissao)
console.log(fatura)