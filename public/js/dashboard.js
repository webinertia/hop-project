(function() { // ContactManager Dashboard
    "use strict";

    function connectSortables() {

        const connectedSortables = document.querySelectorAll(".connectedSortable");
        connectedSortables.forEach((connectedSortable) => {
            let sortable = new Sortable(connectedSortable, {
                group: {
                    name: "shared",
                },
                handle: ".card-header",
            });
        });

        const cardHeaders = document.querySelectorAll(
            ".connectedSortable .card-header",
        );
        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = "move";
        });
    }
    connectSortables();

    let listForm         = document.getElementById('list-form');
    let submit           = document.getElementById('create-list-modal-submit');
    const listModal      = new bootstrap.Modal('#create-list-modal');
    let createListButton = document.getElementById('create-list-modal-button');
    let listBoard        = document.getElementById('list-board');

    if (createListButton !== undefined) {
        createListButton.addEventListener('click', function(e) {
            listModal.toggle();
        });
    }

    async function post(request) {
        try {
            console.log("Request:", request);
            const response = await fetch(request)
            .then(response => response.text())
            .then((response) => {
                console.log("response", response);
                listBoard.insertAdjacentHTML("beforeend", response);
                listModal.toggle();
                listForm.reset();
                connectSortables();
            });
        } catch (error) {
            console.error("Error:", error);
        }
    }

    submit.addEventListener('click', function(e) {
        let action   = listForm.getAttribute('action');
        let formData = new FormData(listForm);
        console.log("formData: ",formData);
        const request = new Request(action, {
            method: "post",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData,
        });
        post(request);
    });
})();