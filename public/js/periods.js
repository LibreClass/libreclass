$(document).ready(function() {

    var viewPeriods = $("#view-periods");
    
    viewPeriods.on("click", ".open-modal-add-period", function(e) {
        var form = $("#form-add-period");
        form.trigger('reset');
        if ($(e.target).is('[edit]')) {
            $.post('periods/read', {
                'period_id': $(e.target).attr('data-id')
            }, function(data) {
                form.find('input[name="period_id"]').val(data.period.id);
                form.find('select[name="course_id"]').val(data.period.course_id);
                form.find('input[name="name"]').val(data.period.name);
                // form.find('input[name="progression_value"]').val(data.period.progression_value);
                $("#modal-add-period").modal();
            });
        } else {
            $.get("/courses/selected", function(data) {
                if (data.status) {
                    form.find('select[name="course_id"]').val(data.value);
                }
            }).done(function() {
                $("#modal-add-period").modal();
            });
        }
    });

    /**
     * Abre modal para remoção de período.
     */
    viewPeriods.on("click", ".open-modal-remove-period", function(e) {
        var periorId = $(e.target).attr("data-id");
        var modal = $("#modal-remove-period");
        var modalSuccess = $("#modal-remove-success");
        $.post("periods/read", {"period_id": periorId}, function(data) {
            modal.find(".periodName").html(data.period.name);
            modalSuccess.find(".periodName").html(data.period.name);
            modal.find("button.remove").attr("data-id", periorId);
            modal.modal();
        });
    });

    $("#select-course-period select").change(function() {
        $("#view-periods .list-periods").load("/periods/list", {
            "course_id": $(this).val()
        }, function() {});
        $.post("/courses/selected", {
            course_id: $(this).val()
        });
    }).change();

    $('#form-add-period').validate({
        rules: {
            "course_id": {
                required: true
            },
            "name": {
                required: true
            },
            // "progression_value": {
            //   required: true
            // }
        },
        messages: {
            "course_id": {
                required: "Este campo deve ser preenchido"
            },
            "name": {
                required: "Este campo deve ser preenchido"
            },
            "progression_value": {
                required: "Este campo deve ser preenchido"
            }
        }
    });

});