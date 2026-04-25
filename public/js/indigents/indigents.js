$('#addForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'indigents/save',$(this).serialize(),()=>location.reload());
});

$(document).on('click','.edit-btn',function(){
    $.get(baseUrl+'indigents/edit/'+$(this).data('id'),res=>{
        $('#editModal input[name=id]').val(res.data.id);
        $('#editModal').modal('show');
    },'json');
});

$('#editForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'indigents/update',$(this).serialize(),()=>location.reload());
});

$(document).on('click','.deleteBtn',function(){
    if(confirm('Delete?')){
        $.post(baseUrl+'indigents/delete/'+$(this).data('id'),()=>location.reload());
    }
});

$('#example1').DataTable({
    serverSide:true,
    ajax:{url:baseUrl+'indigents/fetchRecords',type:'POST'}
});