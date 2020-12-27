function deleteUser(id){
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