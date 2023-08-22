// SUCCESS MESSAGE
window.addEventListener("swal:success", (event) => {
    swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
    });
});



// WARNING CONFIRMATION MESSAGE
window.addEventListener("swal:confirmation", (event) => {
    swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
        buttons: {
            confirm: {
                text: event.detail.buttonConfirmText,
                value: true,
                visible: true,
                closeModal: true,
            },
            cancel: {
                text: "Cancel",
                value: null,
                visible: true,
                closeModal: true,
            },
        }
      }).then((result) => {
        console.log(result);
        if (result) {
            Livewire.emit(event.detail.livewireEmit)
        }
      });
});
