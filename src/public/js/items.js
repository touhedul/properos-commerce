$(document).ready(function () {
    $('#removeItem').on('click', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this item",
            icon: "warning",
            buttons: true,
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "btn-warning",
                    closeModal: true,
                },
                confirm: {
                    text: "Yes, delete it",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false
                }
            }
        }).then(isConfirm => {
            if (isConfirm) {
                window.location.href = '/admin/store/removeItem/' + $(e.target).data('id');
            } 
        });
    });
});

