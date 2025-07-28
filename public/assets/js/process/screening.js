
$(document).ready(function() {
    table = $(".data-table").DataTable();
});


function addNew(){
    $("#staticBackdrop").modal('show');
}


function editData(data){
    $("#edit_screening_id").val( data.screening_id );
    $("#edit_document_caption").val( data.document_caption );
    $("#view_span").html( $("#view_"+data.screening_id).html()  );
    $("#edit_validity").val( data.validity );
    $("#editModal").modal('show');
}

