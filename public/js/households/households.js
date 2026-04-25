$('#addForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'households/save',$(this).serialize(),()=>location.reload());
});

$(document).on('click','.edit-btn',function(){
    $.get(baseUrl+'households/edit/'+$(this).data('id'),res=>{
        $('#editModal input[name=id]').val(res.data.id);
        $('#editModal').modal('show');
    },'json');
});

$('#editForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'households/update',$(this).serialize(),()=>location.reload());
});

$(document).on('click','.deleteBtn',function(){
    if(confirm('Delete?')){
        $.post(baseUrl+'households/delete/'+$(this).data('id'),()=>location.reload());
    }
});

$('#example1').DataTable({
    serverSide:true,
    ajax:{url:baseUrl+'households/fetchRecords',type:'POST'}
});