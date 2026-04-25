$('#example1').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: baseUrl + 'reports/fetchRecords',
        type: 'POST'
    }
});