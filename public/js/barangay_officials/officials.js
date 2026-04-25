$('#addForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'barangay_officials/save', $(this).serialize(), res=>{
        if(res.status==='success'){ location.reload(); }
    },'json');
});

$(document).on('click','.edit-btn',function(){
    $.get(baseUrl+'barangay_officials/edit/'+$(this).data('id'),res=>{
        $('#editModal input[name=id]').val(res.data.id);
        $('#editModal').modal('show');
    },'json');
});

$('#editForm').submit(function(e){
    e.preventDefault();
    $.post(baseUrl+'barangay_officials/update',$(this).serialize(),()=>location.reload());
});

$(document).on('click','.deleteBtn',function(){
    if(confirm('Delete?')){
        $.post(baseUrl+'barangay_officials/delete/'+$(this).data('id'),()=>location.reload());
    }
});

$('#example1').DataTable({
    serverSide:true,
    ajax:{url:baseUrl+'barangay_officials/fetchRecords',type:'POST'}
});