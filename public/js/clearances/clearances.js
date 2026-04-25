$('#addForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'clearances/save',$(this).serialize(),()=>location.reload());
});

$(document).on('click','.edit-btn',function(){
    $.get(baseUrl+'clearances/edit/'+$(this).data('id'),res=>{
        $('#editModal input[name=id]').val(res.data.id);
        $('#editModal').modal('show');
    },'json');
});

$('#editForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'clearances/update',$(this).serialize(),()=>location.reload());
});

$(document).on('click','.deleteBtn',function(){
    if(confirm('Delete?')){
        $.post(baseUrl+'clearances/delete/'+$(this).data('id'),()=>location.reload());
    }
});

$('#example1').DataTable({
    serverSide:true,
    ajax:{url:baseUrl+'clearances/fetchRecords',type:'POST'}
});