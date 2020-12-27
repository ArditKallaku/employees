$(document).ready(function(){
  showDepartments();
  initializeDataTable();
});

function showDepartments(){
  $.ajax({
    type: "POST",
    url: "admin/getDepartmentsTree",
    dataType: "json",       
    success: function(response) {
       initTree(response)
    }   
  });
}

function initTree(treeData) {
  $('#tree').treeview({
    data: treeData,
    enableLinks: true
});

  // collapses all nodes
  //$('#tree').treeview('collapseAll', { silent: true });
}


function showEmployees(id,name){
  document.getElementById('updateD').setAttribute( "onClick", "document.location='department/update?id="+id+"'");
  document.getElementById('selectedName').innerHTML=name;
  document.getElementById('selectedId').innerHTML=id;
  document.getElementById('selectedD').style.display='block';
  $('#employees').DataTable().ajax.reload();
}

function initializeDataTable(){
  $('#employees').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax":{
      "url": "admin/employees",
      "dataType": "json",
      "type": "POST",
      "data": function(d) {
        d.id = document.getElementById('selectedId').innerHTML;
      }
    },
    "columns": [
        { "data": "first_name" },
        { "data": "last_name" },
        { "data": "email" },
        { "data": "actions" },
    ],
    'columnDefs': [ {
      'targets': [3],
      'orderable': false, // set orderable false for actions column
   }]
  });
}


function deleteUser(id){
  jQuery.ajax({
      url: "employee/delete",
      type: "post",
      data: "id="+id,
      success: function(response){
          if(response=="success"){
            $('#employees').DataTable().ajax.reload();
          }
          else{
              alert(response);
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
      
      }
  });
}