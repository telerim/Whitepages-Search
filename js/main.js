/* 
 * 
 */

jQuery(document).ready(function($) {
    var term = '';
    var location = ''; // City
    var postal_code = '';
    var csvFilename = '';
    
    if($.cookie('term')){
        term = $.cookie('term');
        $('#input-search-term').val(term);
    }
    if($.cookie('location')){
        location = $.cookie('location');
        $('#input-search-location').val(location);
    }
    if($.cookie('postal-code')){
        postal_code = $.cookie('postal-code');
        $('#input-search-postal-code').val(postal_code);
    }
    
    term.trim();
    location.trim();
    postal_code.trim();
    
    if(term.length === 0){
        $('#input-search-term').focus();
        $('#input-search-term').tooltip('show');
    }
    
    var searchData = {
                "type": 'person',
                "term": '', // don't search on first page load
                "location": '',
                //"postal_code": postal_code
            };
    
    csvFilename = term;
    
    if(location.length !== 0){
        csvFilename = csvFilename + '-' + location;
    }
    
    if(postal_code.length !== 0){
        csvFilename = csvFilename + '-' + postal_code;
    }
    
    var tableResult = $('table.table').on('xhr.dt', function ( e, settings, json, xhr ){
        if(typeof(json.dataError) !== 'undefined'){
            
            $('#error-message').text(json.dataError.message);
            $('.alert').removeClass('hidden');
            $('.alert').fadeIn(200);
        }
        // get error data and do something about it
        if(xhr.status !== 200){
            
            $('#error-message').text('Opps! Data cannot be loaded. Please try again.');
            $('.alert').removeClass('hidden');
            $('.alert').fadeIn(200);
        }
    }).DataTable({
        "searching": false,
        "pageLength": 20,
        "lengthChange": false,
        "ordering": false,
        "info":     false,
        "processing": true,
        //"serverSide": true,
        "dom": 'Bfrtip',
        "buttons": [{ 
            extend: 'csv',
            text: 'Download',
            className: 'btn-danger',
            title: csvFilename,
            exportOptions: {
                    columns: [ 0, 1, 3, 4, 5, 6, 7 /*, 8*/ ]
                }
        }],
        "ajax": {
            "url": "ajax.php",
            "type": "POST",
            "data": searchData
        },
        "columnDefs": [
            { "visible": false, "targets": 3 },
            //{ "visible": false, "targets": 8 }
        ],
        "columns":[
            {"data":"name"},
            {"data":"age_range"},
            {"data":"phone_display"},
            {"data":"phone"},
            {"data":"address"},
            {"data":"city"},
            {"data":"state"},
            {"data":"zip"},
            //{"data":"category"},
            //{"data":"url_link"},
            //{"data":"url"}
        ]
    });

    tableResult.buttons().container().appendTo($('#controlPanel'));

    $('#btn-search-submit').click(function(e){
        e.preventDefault();
        
        $('.alert').addClass('hidden');
        
        term = $('#input-search-term').val();
        location = $('#input-search-location').val();
        postal_code = $('#input-search-postal-code').val();
        
        term.trim();
        location.trim();
        postal_code.trim();
        
        $.cookie('term',term,{ expires: 500 });
        $.cookie('location',location,{ expires: 500 });
        $.cookie('postal-code',postal_code,{ expires: 500 });
        
        if(term.length === 0){
            $('#input-search-term').focus();
            $('#input-search-term').tooltip('show');
            return;
        }
        
        searchData = {
                "type": 'person',
                "term": term,
                "location": location,
                "postal_code": postal_code
            };
        
        csvFilename = term;
    
        if(location.length !== 0){
            csvFilename = csvFilename + '-' + location;
        }

        if(postal_code.length !== 0){
            csvFilename = csvFilename + '-' + postal_code;
        }
        
        tableResult.destroy();
        $('table.table tbody').empty();
        tableResult = $('table.table').on('xhr.dt', function ( e, settings, json, xhr ){
        
        if(typeof(json.dataError) !== 'undefined'){
            
            $('#error-message').text(json.dataError.message);
            $('.alert').removeClass('hidden');
            $('.alert').fadeIn(200);
        }
        // get error data and do something about it
        if(xhr.status !== 200){
            
            $('#error-message').text('Opps! Data cannot be loaded. Please try again.');
            $('.alert').removeClass('hidden');
            $('.alert').fadeIn(200);
        }
        
    }).DataTable({
            "searching": false,
            "pageLength": 20,
            "lengthChange": false,
            "ordering": false,
            "info":     false,
            "processing": true,
            //"serverSide": true,
            "dom": 'Bfrtip',
            "buttons": [{
                extend: 'csv',
                text: 'Download',
                className: 'btn-danger',
                title: csvFilename,
                exportOptions: {
                    columns: [ 0, 1, 3, 4, 5, 6, 7 /*, 8 */]
                }
            }],
            "ajax": {
                "url": "ajax.php",
                "type": "POST",
                "data": searchData
            },
            "columnDefs": [
                { "visible": false, "targets": 3 },
                //{ "visible": false, "targets": 8 }
            ],
            "columns":[
                {"data":"name"},
                {"data":"age_range"},
                {"data":"phone_display"},
                {"data":"phone"},
                {"data":"address"},
                {"data":"city"},
                {"data":"state"},
                {"data":"zip"},
                //{"data":"category"}, //
                //{"data":"url_link"},
                //{"data":"url"}
            ]
        });
        
        tableResult.buttons().container().appendTo($('#controlPanel'));
    });
    
    $('[data-toggle="tooltip"]').tooltip();
    $('.alert .close').click(function () {
        //$('.alert').addClass('hidden');
        $('.alert').fadeOut(200);
    });
});
