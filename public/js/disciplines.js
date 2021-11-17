$(function($) {

    $("#new-block").click(function() {
        $("#title-discipline").text("Nova Disciplina");
        $("#new-discipline input[name='discipline']").val("");
        $.get("/courses/selected", function(data) {
            if (data.status) {
                $("#new-discipline select[name='course']").val(data.value);
            }
        })
    });

    $("#select-course select").change(function() {
        $.post("/courses/selected", {
            course_id: $(this).val()
        });
        $("#new-discipline select[name='course']").val($(this).val());
        $("#list-disciplines").load("/disciplines/list", {
            "course": $(this).val()
        }, function() {
            $(".trash").click(trash);
            $(".discipline-edit").click(function() {
                var discipline = $(this).closest(".data").attr("key");
                $("#formEditDiscipline input[name='discipline']").val(discipline);
                $.getJSON("/disciplines/discipline", {
                    "discipline": discipline
                }, function(data) {
                    $("#formEditDiscipline input[name='name']").val(data.name);
                    $("#formEditDiscipline #ementa").val(data.ementa);
                    $("#modalEditDiscipline").modal();
                }).fail(function() {
                    alert('Erro ao se conectar ao servidor');
                });
            });
        }).error(function() {
            alert('Erro ao se conectar ao servidor');
        });
    }).change();

    $("#new-discipline select[name='course']").change(function() {
        $.post("/courses/selected", {
            course_id: $(this).val()
        });
        $.get("/disciplines/list-periods", {
            "course": $(this).val()
        }, function(data) {
            $("#new-discipline select[name='period']").html("");
            for (var i = 0; i < data.length; i++)
                $("#new-discipline select[name='period']").append($("<option>", {
                    value: data[i].id,
                    html: data[i].name
                }));
        }).fail(function() {
            alert('Erro ao se conectar ao servidor');
        });
    }).change();

});