// EDIT ATTENDEE
window.addEventListener("swal:attendee-password-updated", (event) => {
    swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
    });
});

window.addEventListener("swal:reset-password-attendee-confirmation", (event) => {
    swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
        buttons: {
            confirm: {
                text: "Yes, reset it!",
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
            Livewire.emit('resetPasswordAttendeeConfirmed')
        }
      });
});

// EDIT ATTENDEE
window.addEventListener("swal:attendee-updated", (event) => {
    swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
    });
});

window.addEventListener("swal:edit-attendee-confirmation", (event) => {
    swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
        buttons: {
            confirm: {
                text: "Yes, update it!",
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
            Livewire.emit('editAttendeeConfirmed')
        }
      });
});

// ADD ATTENDEE
window.addEventListener("swal:attendee-added", (event) => {
    swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
    });
});

window.addEventListener("swal:add-attendee-confirmation", (event) => {
    swal({
        title: event.detail.message,
        text: event.detail.text,
        icon: event.detail.type,
        buttons: {
            confirm: {
                text: "Yes, add it!",
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
            Livewire.emit('addAttendeeConfirmed')
        }
      });
});