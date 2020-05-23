$(function() {

    $('.modalButton').click(function (e) {
        e.preventDefault();
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('href'));
    });

    $('.modalButtonView').click(function (e) {
        e.preventDefault();
        $('#modalview').modal('show')
            .find('#modalContentView')
            .load($(this).attr('href'));
    });

    $('.modalButtonCreate').click(function (e) {
        e.preventDefault();
        $('#modalcreate').modal('show')
            .find('#modalContentCreate')
            .load($(this).attr('href'));
    });

    $('.modalButtonUpdate').click(function (e) {
        e.preventDefault();
        $('#modalupdate').modal('show')
            .find('#modalContentUpdate')
            .load($(this).attr('href'));
    });

});