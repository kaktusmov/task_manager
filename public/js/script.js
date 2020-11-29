$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        "searching": false,
        "pageLength": 3,
        "lengthChange": false,
        "stateSave": true,
        "ajax": {
            "url": "tasks-list",
            "type": "POST"
        },
        "columns": [
            { "data": "author_name" },
            { "data": "author_email" },
            { "data": "text", "orderable": false },
            { "data": "status" },
            { "data": "id", 'visible': false}
        ],
        "order": [[ 4 , "desc" ]]
    } );
} );