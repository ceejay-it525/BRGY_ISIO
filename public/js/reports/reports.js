const reportsTableElement = $('#example1');
if (reportsTableElement.length) {
    reportsTableElement.DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: baseUrl + 'reports/fetchRecords',
            type: 'POST',
            data: function (d) {
                d.csrf_test_name = $('input[name=csrf_test_name]').val();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        }
    });
}

function initReportsCharts() {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js is not loaded');
        return;
    }

    const genderCtx = document.getElementById('genderChart');
    const statusCtx = document.getElementById('statusChart');
    const blotterCtx = document.getElementById('blotterChart');
    const clearancesCtx = document.getElementById('clearancesChart');

    const COLOR_MAP = {
        'male':     '#2563EB',
        'female':   '#EC4899',
        'active':   '#16A34A',
        'inactive': '#DC2626',
        'pending':  '#F59E0B',
        'other':    '#6B7280',
    };

    function getColors(labels) {
        return labels.map(l => COLOR_MAP[l.toLowerCase()] || COLOR_MAP['other']);
    }

    const buildChart = (ctx, labels, data, type, label) => {
        if (!ctx) return;
        return new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: getColors(labels),
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            }
        });
    };

    const genderLabels = genderData.map(item => item.gender);
    const genderValues = genderData.map(item => item.total);
    const statusLabels = statusData.map(item => item.status);
    const statusValues = statusData.map(item => item.total);
    const blotterLabels = blotterData.map(item => item.complaint_type);
    const blotterValues = blotterData.map(item => item.total);
    const clearanceLabels = clearanceData.map(item => item.month_label);
    const clearanceValues = clearanceData.map(item => item.total);

    buildChart(genderCtx, genderLabels, genderValues, 'doughnut', 'Residents by Gender');
    buildChart(statusCtx, statusLabels, statusValues, 'doughnut', 'Residents by Status');
    buildChart(blotterCtx, blotterLabels, blotterValues, 'bar', 'Top Blotter Types');
    buildChart(clearancesCtx, clearanceLabels, clearanceValues, 'line', 'Clearances Issued');
}

$(document).ready(initReportsCharts);