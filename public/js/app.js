/**
 * BMIS - Global Application JavaScript
 * Common functionality shared across all modules
 */

// ================= GLOBAL TOAST NOTIFICATION =================
function showToast(type = 'info', message = '') {
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "3000",
            extendedTimeOut: "1000"
        };
        if (toastr[type]) {
            toastr[type](message);
        } else {
            toastr.info(message);
        }
    } else {
        // Fallback if toastr is not loaded
        console.log(`[${type.toUpperCase()}] ${message}`);
        alert(message);
    }
}

// ================= SWEET ALERT CONFIRM =================
function confirmDelete(message = 'Are you sure you want to delete this record?') {
    if (typeof Swal !== 'undefined') {
        return Swal.fire({
            title: 'Confirm Delete',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });
    }
    return confirm(message);
}

// ================= CSRF TOKEN HELPER =================
function getCsrfToken() {
    const tokenInput = document.querySelector('input[name="csrf_test_name"]');
    return tokenInput ? tokenInput.value : '';
}

function updateCsrfToken(response) {
    if (response && response.csrf_hash) {
        const tokenInput = document.querySelector('input[name="csrf_test_name"]');
        if (tokenInput) {
            tokenInput.value = response.csrf_hash;
        }
    }
}

// ================= REFRESH REPORT STATS =================
// This function is called from various modules after CRUD operations
function refreshReportStats() {
    if (typeof baseUrl === 'undefined') return;
    
    $.ajax({
        url: baseUrl + 'reports/reportStats',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Update dashboard stats if elements exist
            const elements = {
                'totalResidents': data.total_residents || 0,
                'totalHouseholds': data.total_households || 0,
                'totalBlotter': data.total_blotter || 0,
                'totalClearances': data.total_clearances || 0,
                'totalOfficials': data.total_officials || 0,
                'totalPermits': data.total_permits || 0,
                'totalIndigents': data.total_indigents || 0
            };
            
            $.each(elements, function(id, value) {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = value;
                }
            });
        },
        error: function(xhr) {
            console.log('Stats refresh error:', xhr.responseText);
        }
    });
}

// ================= FORMAT DATE =================
function formatDate(dateString) {
    if (!dateString) return '—';
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// ================= FORMAT DATETIME =================
function formatDatetime(dateString) {
    if (!dateString) return '—';
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleString('en-US', options);
}

// ================= STATUS BADGE HELPER =================
function statusBadge(status, customMap = {}) {
    const defaultMap = {
        'Active': 'success',
        'Inactive': 'secondary',
        'Pending': 'warning',
        'Approved': 'primary',
        'Released': 'info',
        'Rejected': 'danger',
        'Expired': 'danger',
        'Ongoing': 'warning',
        'Resolved': 'success',
        'Deceased': 'dark',
        'Transferred': 'info'
    };
    
    const map = { ...defaultMap, ...customMap };
    const color = map[status] || 'secondary';
    return `<span class="badge badge-${color}">${status}</span>`;
}

// ================= GENDER BADGE HELPER =================
function genderBadge(gender) {
    if (gender === 'Male') {
        return '<span class="badge badge-primary">Male</span>';
    } else if (gender === 'Female') {
        return '<span class="badge badge-pink">Female</span>';
    } else {
        return '<span class="badge badge-secondary">Other</span>';
    }
}

// ================= LOADING OVERLAY =================
function showLoading() {
    const overlay = $('<div class="loading-overlay" style="' +
        'position: fixed; top: 0; left: 0; width: 100%; height: 100%; ' +
        'background: rgba(255,255,255,0.8); z-index: 9999; ' +
        'display: flex; align-items: center; justify-content: center;">' +
        '<div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">' +
        '<span class="sr-only">Loading...</span></div></div>');
    $('body').append(overlay);
}

function hideLoading() {
    $('.loading-overlay').remove();
}

// ================= FORM VALIDATION HELPER =================
function validateForm(formId) {
    const form = $(`#${formId}`);
    let isValid = true;
    const requiredFields = form.find('[required]');
    
    requiredFields.each(function() {
        if (!$(this).val().trim()) {
            $(this).addClass('is-invalid');
            isValid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    return isValid;
}

// ================= CLEAR FORM =================
function clearForm(formId) {
    $(`#${formId}`)[0].reset();
    $(`#${formId}`).find('.is-invalid').removeClass('is-invalid');
    $(`#${formId}`).find('.validation-errors').remove();
}

// ================= MODAL HELPER =================
function showModal(modalId) {
    $(`#${modalId}`).modal('show');
}

function hideModal(modalId) {
    $(`#${modalId}`).modal('hide');
}

// ================= AJAX REQUEST HELPER =================
function ajaxRequest(url, method = 'POST', data = {}, successCallback = null, errorCallback = null) {
    $.ajax({
        url: url,
        type: method,
        data: data,
        dataType: 'json',
        success: function(response) {
            updateCsrfToken(response);
            if (successCallback) successCallback(response);
        },
        error: function(xhr) {
            console.error('AJAX Error:', xhr.responseText);
            if (errorCallback) errorCallback(xhr);
            showToast('error', 'An error occurred. Please try again.');
        }
    });
}

// ================= PRINT FUNCTION =================
function printElement(elementId) {
    const printContents = document.getElementById(elementId).innerHTML;
    const originalContents = document.body.innerHTML;
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    
    // Re-bind events after print
    location.reload();
}

// ================= EXPORT TO CSV =================
function exportToCSV(data, filename = 'export.csv') {
    let csvContent = 'data:text/csv;charset=utf-8,';
    
    // Add headers
    if (data.length > 0) {
        const headers = Object.keys(data[0]);
        csvContent += headers.join(',') + '\n';
    }
    
    // Add rows
    data.forEach(function(row) {
        const rowValues = Object.values(row).map(val => 
            typeof val === 'string' && val.includes(',') ? `"${val}"` : val
        );
        csvContent += rowValues.join(',') + '\n';
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// ================= INIT ON DOCUMENT READY =================
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-toggle="popover"]').popover();
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Add smooth scrolling to all links
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 70
            }, 1000);
        }
    });
    
    // Handle form submission with AJAX
    $('form[data-ajax="true"]').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const method = form.attr('method') || 'POST';
        const data = new FormData(this);
        
        $.ajax({
            url: url,
            type: method,
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                updateCsrfToken(response);
                if (response.status === 'success') {
                    showToast('success', response.message);
                    if (form.data('reload')) {
                        location.reload();
                    }
                } else {
                    showToast('error', response.message || 'Operation failed');
                }
            },
            error: function(xhr) {
                showToast('error', 'An error occurred');
            }
        });
    });
});

// ================= HANDLE PAGE VISIBILITY =================
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Page is hidden - could pause updates, timers, etc.
    } else {
        // Page is visible - refresh data if needed
    }
});