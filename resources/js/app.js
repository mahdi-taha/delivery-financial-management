import './bootstrap';
import 'bootstrap';
import Swal from 'sweetalert2';
window.Swal = Swal;
import $ from "jquery";
import select2 from 'select2';
import 'select2/dist/css/select2.css';
window.$ = window.jQuery = $;
select2();
window.addEventListener('load', function () {
    window.$('.select2').select2({
        width: '100%'
    });
});
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar');
const overlay = document.querySelector('.sidebar-overlay');
if (sidebarToggle && sidebar && overlay) {
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.add('show');
        overlay.classList.add('show');
    });
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
}
window.disableSubmitButton = function (form) {
    const button = form.querySelector('button[type="submit"]');
    button.disabled = true;
    button.querySelector('.btn-text').classList.add('d-none');
    button.querySelector('.btn-loading').classList.remove('d-none');
};
document.querySelectorAll('.toast-message')
    .forEach(toast => {
        Swal.fire({
            toast: true,
            icon: toast.dataset.type,
            title: toast.dataset.message,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5500,
            timerProgressBar: true,
        });
    });
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: window.translations.confirm_delete,
            text: window.translations.delete_warning,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: window.translations.yes_delete,
            cancelButtonText: window.translations.cancel,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});