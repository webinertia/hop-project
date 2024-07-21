(function() { // sortable.js
    "use strict";
    const connectedSortables = document.querySelectorAll(".connectedSortable");
    connectedSortables.forEach((connectedSortable) => {
        let sortable = new Sortable(connectedSortable, {
            group: "shared",
            handle: ".card-header",
        });
    });

    const cardHeaders = document.querySelectorAll(
        ".connectedSortable .card-header",
    );
    cardHeaders.forEach((cardHeader) => {
        cardHeader.style.cursor = "move";
    });
})();

(function() { // modal handlers
    "use strict";
    let listForm         = document.getElementById('list-form');
    let submit           = document.getElementById('create-list-modal-submit');
    const listModal      = new bootstrap.Modal('#create-list-modal');
    let createListButton = document.getElementById('create-list-modal-button');

    if (createListButton !== undefined) {
        createListButton.addEventListener('click', function(e) {
            listModal.toggle();
        });
    }

    submit.addEventListener('click', function(e) {
        let action   = listForm.getAttribute('action');
        let formData = new FormData(listForm);
        //console.log(formData);
        fetch(action, {
            method: 'POST',
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => {
            console.log(response);
            if (response.status === 200) {
                listForm.reset(); // rest form values
                listModal.toggle(); // close modal
            }
        })
        .catch((error) => {
            alert('error reported');
        });
    });
})();