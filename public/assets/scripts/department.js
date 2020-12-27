function deleteD(id){
    jQuery.ajax({
        url: "hasChilds",
        type: "post",
        data: "id="+id,
        success: function(response){
            if(response=="yes"){
                $('#confirmModal').modal('show');
            }
            else{
                deleteAll(id);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
        
        }
    });
}

function deleteAll(id){
    jQuery.ajax({
        url: "delete",
        type: "post",
        data: "id="+id,
        success: function(response){
        if(response=="success"){
            window.location.href = "/admin";
        }
        else{
            alert(response);
        }
        },
        error: function(jqXHR, textStatus, errorThrown) {
        
        }
    });
}