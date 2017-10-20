$(document).ready(function() {

    $(".logout-link").click(function(e){
        var answer=confirm('Tem a certeza que deseja sair?');
        if(answer){

        }
        else{
            e.preventDefault();
        }


    });


    // WIKI UL CONTENT
    $(".toggle-table").click(function(){

        $(this).find('.chevron').toggleClass('fa fa-chevron-down fa fa-chevron-up');

        $(this).parent().next(".list-group").fadeToggle();

    });

    // WIKI EDIT CONTENT
    $(".toggle-table-edit").click(function(){

        $(this).find('.chevron').toggleClass('fa fa-chevron-down fa fa-chevron-up');

        $(this).parent().next(".table").fadeToggle();

            $('html, body').animate({
                scrollTop: $(this).parent().next(".table").offset().top
            }, 'slow');
    });




    // WIKI category
    $(".delete-category-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja eliminar esta categoria? Todos os artigos pertencentes à mesma serão eliminados.');
        if(answer){

        }
        else{
            e.preventDefault();
        }
    });

    // WIKI article
    $(".delete-article-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja eliminar este artigo?');
        if(answer){

        }
        else{
            e.preventDefault();
        }

    });

    // MANAGE VACATIONS
    $(".delete-vacation-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja eliminar este período de férias?');
        if(answer){

        }
        else{
            e.preventDefault();
        }

    })

    // Complete project confirm
    $(".complete-project-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja marcar este projecto como completo?');
        if(answer){

        }
        else{
            e.preventDefault();
        }
    });

    // Colocar em progresso
    $(".progress-project-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja colocar este projecto em progresso?');
        if(answer){

        }
        else{
            e.preventDefault();
        }
    });

    // Delete project confirm
    $(".delete-project-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja eliminar este projecto? Todas as tarefas pertencentes ao mesmo serão eliminadas.');
        if(answer){

        }
        else{
            e.preventDefault();
        }
    });

    // Delete task confirm
    $(".delete-task-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja eliminar esta tarefa?');
        if(answer){

        }
        else{
            e.preventDefault();
        }
    });

    // Complete project confirm
    $(".complete-task-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja marcar esta tarefa como completa?');
        if(answer){
        }
        else{
            e.preventDefault();
        }
    });

    // Delete project confirm
    $(".delete-color-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja eliminar esta cor?');
        if(answer){

        }
        else{
            e.preventDefault();
        }
    });



    // EDIT project
    $(".edit-chevron").click(function(){
        $('.chevron').toggleClass('fa fa-chevron-down fa fa-chevron-up');
        $('.user-list').fadeToggle();

    });
    // EDIT project 2nd chevron
    $(".edit-chevron2").click(function(){
        $('.chevron2').toggleClass('fa fa-chevron-down fa fa-chevron-up');
        $('#add-member-div').fadeToggle();
    });
    /* EDIT project add member chevron
    $("#add-member-chevron").click(function(){
        $('.chevron').toggleClass('fa fa-chevron-down fa fa-chevron-up');
        $('#add-member-div').fadeToggle();
    });*/


    // DASHBOARD chevrons
    $("#dashboard-chevron").click(function(){
        $('.chevron').toggleClass('fa fa-chevron-down fa fa-chevron-up');
        $('#projects-div').fadeToggle();
    });

    $("#dashboard-chevron2").click(function(){
        $('.chevron2').toggleClass('fa fa-chevron-down fa fa-chevron-up');
        $('#tasks-div').fadeToggle();
    });





    // Datepicker
    $( function() {
        $( "#datepicker, #datepicker2" ).datepicker({
            dateFormat: 'yy-mm-dd'
        });
    } );

    // Datepicker class
    $( function() {
        $(".datepicker, .datepicker2" ).datepicker({
            dateFormat: 'yy-mm-dd'
        });
    } );


    // LIVE SEARCH EDIT PROJECT
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");

        if(inputVal.length){
            $.get("backend-search.php", {utilizadores: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){

        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());

        $(this).parent(".result").empty();
    });

    // LIVE SEARCH TASK
    $('.search-box-task input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result-task");

        if(inputVal.length){
            $.get("backend-search-task.php", {utilizadores: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    // Set search input value on click of result item
    $(document).on("click", ".result-task p", function(){

        $(this).parents(".search-box-task").find('input[type="text"]').val($(this).text());

        $(this).parent(".result-task").empty();
    });



    // LIVE SEARCH VACATION
    $('.search-box-vacation input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result-vacation");

        if(inputVal.length){
            $.get("backend-search-vacation.php", {utilizadores: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    // Set search input value on click of result item
    $(document).on("click", ".result-vacation p", function(){

        $(this).parents(".search-box-vacation").find('input[type="text"]').val($(this).text());

        $(this).parent(".result-vacation").empty();
    });







/********************************* MODAL **************************************************/

    // Data inicio
    $(document).on("click", ".view-admin", function() {
        var adminid = $(this).data('id');
        $(".modal-body #showid").text(adminid);
        var dataEscolhida = year + '-' + month + '-' + adminid;
        document.getElementById("modal_project_start_date").value = dataEscolhida;
        document.getElementById("modal_task_start_date").value = dataEscolhida;
        document.getElementById("modal_note_date").value = dataEscolhida;
        document.getElementById("modal_vacation_start_date").value = dataEscolhida;

        //year + '-' + month + '-' + adminid;
        //document.getElementsByClassName("datepicker").value = year + '-' + month + '-' + adminid;

        $('#view_contact').modal('show');
    });


    // AJAX: PROJECTO
    $("#modal-project-form").submit(function(e){

        e.preventDefault();
        var formData = $( this ).serialize();

        $.ajax({
            type: 'POST',
            url: 'ajax/modal_add_project.php',
            data: formData,
            dataType: 'json',
            success: function( resp ){

                if ( resp == 1 ){
                    $('#modal-error').css("background-color", "#5CB85C");
                    $( '#modal-error' ).text( 'Projecto criado com sucesso' );
                    $("#project-img").removeClass("selected-modal-img");
                    $('#project-div').fadeOut();
                } else if (resp == 2 ) {
                    $('#modal-error').css("background-color", "#d9534f");
                    $( '#modal-error' ).text( 'Já existe um projecto com este nome' );
                }
                else if(resp == 3) {
                    $('#modal-error').css("background-color", "#d9534f");
                    $('#modal-error').text('Por favor preencha todos os campos');
                }
                else {
                    $( '#modal-error' ).text('Erros desconhecidos');
                }
            }
        });

    });


    // AJAX: TASK
    $("#modal-task-form").submit(function(e){

        e.preventDefault();
        var formData = $( this ).serialize();

        $.ajax({
            type: 'POST',
            url: 'ajax/modal_add_task.php',
            data: formData,
            dataType: 'json',
            success: function( resp ){

                if ( resp == 1 ){
                    $('#modal-error').css("background-color", "#5CB85C");
                    $( '#modal-error' ).text( 'Tarefa criada com sucesso' );
                    $("#task-img").removeClass("selected-modal-img");
                    $('#task-div').fadeOut();
                } else if (resp == 2 ) {
                    $('#modal-error').css("background-color", "#d9534f");
                    $( '#modal-error' ).text( 'Já existe uma tarefa com este nome' );
                }
                else if(resp == 3) {
                    $('#modal-error').css("background-color", "#d9534f");
                    $('#modal-error').text('Por favor preencha todos os campos');
                }
                else {
                    $( '#modal-error' ).text('Erros desconhecidos');
                }
            }
        });

    });


    // AJAX: VACATION
    $("#modal-vacation-form").submit(function(e){

        e.preventDefault();
        var formData = $( this ).serialize();

        $.ajax({
            type: 'POST',
            url: 'ajax/modal_add_vacation.php',
            data: formData,
            dataType: 'json',
            success: function( resp ){

                if ( resp == 1 ){
                    $('#modal-error').css("background-color", "#5CB85C");
                    $( '#modal-error' ).text( 'Férias marcadas com sucesso' );
                    $("#vacation-img").removeClass("selected-modal-img");
                    $('#vacation-div').fadeOut();
                } else if (resp == 2 ) {
                    $('#modal-error').css("background-color", "#d9534f");
                    $( '#modal-error' ).text( 'Já existe um prazo de férias previstas para este utilizador neste espaço de tempo' );
                }
                else if(resp == 3) {
                    $('#modal-error').css("background-color", "#d9534f");
                    $('#modal-error').text('Por favor preencha todos os campos');
                }
                else if(resp == 4) {
                    $('#modal-error').css("background-color", "#d9534f");
                    $('#modal-error').text('Utilizador inexistente');
                }
                else {
                    $( '#modal-error' ).text('Erros desconhecidos');
                }
            }
        });

    });




    // AJAX: NOTES
    $("#modal-note-form").submit(function(e){

        e.preventDefault();
        var formData = $( this ).serialize();

        $.ajax({
            type: 'POST',
            url: 'ajax/modal_add_note.php',
            data: formData,
            dataType: 'json',
            success: function( resp ){

                if ( resp == 1 ){
                    $('#modal-error').css("background-color", "#5CB85C");
                    $( '#modal-error' ).text( 'Nota criada com sucesso' );
                    $("#note-img").removeClass("selected-modal-img");
                    $('#note-div').fadeOut();
                } else if (resp == 2 ) {
                    $('#modal-error').css("background-color", "#d9534f");
                    $( '#modal-error' ).text( 'Por favor preencha todos os campos' );
                }
                else {
                    $( '#modal-error' ).text('Erros desconhecidos');
                }
            }
        });

    });





    // Recarregar a página ao fechar o modal(de que forma for)
    $('.modal').on('hidden.bs.modal', function () {
        location.reload();
    });




    // Active images and stuff
    $("#project-img").click(function(){
        $(".modal-img").removeClass("selected-modal-img");
        $(".selection-div").not("#project-div").fadeOut();
        $("#project-img").toggleClass("selected-modal-img");
        $("#project-div").fadeToggle();
        $( '#modal-error' ).text( ' ' );
    });

    $("#task-img").click(function() {
        $(".modal-img").removeClass("selected-modal-img");
        $(".selection-div").not("#task-div").fadeOut();
        $("#task-img").toggleClass("selected-modal-img");
        $("#task-div").fadeToggle();
        $( '#modal-error' ).text( ' ' );
    });

    $("#note-img").click(function() {
        $(".modal-img").removeClass("selected-modal-img");
        $(".selection-div").not("#note-div").fadeOut();
        $("#note-img").toggleClass("selected-modal-img");
        $("#note-div").fadeToggle();
        $( '#modal-error' ).text( ' ' );
    });

    $("#vacation-img").click(function() {
        $(".modal-img").removeClass("selected-modal-img");
        $(".selection-div").not("#vacation-div").fadeOut();
        $("#vacation-img").toggleClass("selected-modal-img");
        $("#vacation-div").fadeToggle();
        $( '#modal-error' ).text( ' ' );
    });



    // MODAL NOTE
    $(document).on('click', '#openNote', function(e){

        e.preventDefault();

        var uid = $(this).data('id'); // get id of clicked row

        $('#dynamic-content').html(''); // leave this div blank
        $('#modal-loader').show();      // load ajax loader on button click

        $.ajax({
            url: 'ajax/open_note.php',
            type: 'POST',
            data: 'id='+uid,
            dataType: 'html'
        })
            .done(function(data){
                //console.log(data);
                $('#dynamic-content').html(''); // blank before load.
                $('#dynamic-content').html(data); // load here
                $('#modal-loader').hide(); // hide loader
            })
            .fail(function(){
                $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Alguma coisa correu mal, por favor tente de novo...');
                $('#modal-loader').hide();
            });

    });


    // MODAL USERS
    $(document).on('click', '#openProfile', function(e){

        e.preventDefault();

        var uid = $(this).data('id'); // get id of clicked row

        $('#dynamic-content').html(''); // leave this div blank
        $('#modal-loader').show();      // load ajax loader on button click

        $.ajax({
            url: 'ajax/open_profile.php',
            type: 'POST',
            data: 'id='+uid,
            dataType: 'html'
        })
            .done(function(data){
                //console.log(data);
                $('#dynamic-content').html(''); // blank before load.
                $('#dynamic-content').html(data); // load here
                $('#modal-loader').hide(); // hide loader
            })
            .fail(function(){
                $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Alguma coisa correu mal, por favor tente de novo...');
                $('#modal-loader').hide();
            });

    });


    // Delete user confirm
    $(".delete-user-btn").click(function(e){
        var answer=confirm('Tem a certeza que deseja eliminar este utilizador? Esta acção não pode ser revertida.');
        if(answer){
            alert('Utilizador eliminado');
        }
        else{
            e.preventDefault();
        }
    });












});
