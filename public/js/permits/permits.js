$('#addForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'permits/save',$(this).serialize(),()=>location.reload());
});

$(document).on('click','.edit-btn',function(){
    $.get(baseUrl+'permits/edit/'+$(this).data('id'),res=>{
        $('#editModal input[name=id]').val(res.data.id);
        $('#editModal').modal('show');
    },'json');
});

$('#editForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'permits/update',$(this).serialize(),()=>location.reload());
});

$(document).on('click','.deleteBtn',function(){
    if(confirm('Delete?')){
        $.post(baseUrl+'permits/delete/'+$(this).data('id'),()=>location.reload());
    }
});

$('#example1').DataTable({
    serverSide:true,
    ajax:{url:baseUrl+'permits/fetchRecords',type:'POST'}
});