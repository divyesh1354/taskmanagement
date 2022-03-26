function alertPopup(url, title, message, okButton = 'Delete', cancelButton = 'Cancel') {
    bootbox.dialog({
        message: message,
        title: title,
        closeButton: false,
        buttons: {
            success: {
                label: cancelButton,
                className: "btn-primary modal-button-hover",
                callback: function () {
                    return true;
                }
            },
            danger: {
                label: okButton,
                className: "btn-danger modal-button-hover",
                callback: function () {
                    axios.delete(url).then((response) => {
                        if (response) {
                            window.location.reload();
                        }
                    });
                }
            }
        }
    });
}
