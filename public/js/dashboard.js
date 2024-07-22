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

    // List variables
    let listForm         = document.getElementById('list-form');
    let listSubmit       = document.getElementById('create-list-modal-submit');
    const listModal      = new bootstrap.Modal('#create-list-modal');
    let createListButton = document.getElementById('create-list-modal-button');
    let listBoard        = document.getElementById('list-board');

    // Contact variables
    let contactForm      = document.getElementById('create-contact-form');
    let contactSubmit    = document.getElementById('create-contact-modal-submit');
    const contactModal   = new bootstrap.Modal('#create-contact-modal');
    const createContactButtons = document.querySelectorAll('.create-contact-button');

    if (createListButton !== undefined) {
        createListButton.addEventListener('click', function(e) {
            listModal.toggle();
        });
    }

    async function postList(request) {
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

    listSubmit.addEventListener('click', function(e) {
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
        postList(request);
    });

    async function postContact(request, targetId) {
        let updateTarget = document.querySelector('#list_' + targetId + ' .card-body .connectedSortable');
        try {
            const response = await fetch(request)
            .then(response => response.text())
            .then((response) => {
                //console.log("response", response);
                updateTarget.insertAdjacentHTML("beforeend", response);
                contactModal.toggle();
                contactForm.reset();
                connectSortables();
            });
        } catch (error) {
            console.error("Error:", error);
        }
    }

    contactSubmit.addEventListener('click', function(e) {
        let action    = contactForm.getAttribute('action');
        let formData  = new FormData(contactForm);
        const request = new Request(action, {
            method: "post",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData,
        });
        console.log("formData", formData);
        postContact(request, formData.get('list_id'));
    });

    listBoard.addEventListener('click', event => {
        console.log("list-board event: ", event.target.parentElement);
        const buttonCheck = event.target.parentElement.nodeName === 'BUTTON';
        console.log("listBoardListener: before check");
        if (!buttonCheck) {
            return;
        }
        console.log("listBoardListener: after check");
        contactModal.toggle();
        let targetButton = event.target.parentElement;
        document.getElementById('contact-list-id').value = targetButton.dataset.cmlistId;
    });

})();