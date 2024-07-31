(function() { // ContactManager Dashboard
    "use strict";

    htmx.onLoad(function(content) {
        var sortables     = content.querySelectorAll(".connectedSortable");
        const cardHeaders = content.querySelectorAll(".connectedSortable .card-header");

        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = "move";
        });

        for (var i = 0; i < sortables.length; i++) {
            var sortable = sortables[i];
            new Sortable(sortable, {
                group: {
                    name: "shared",
                },
                handle: ".card-header",
            });
        }
    });

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

    if (createListButton !== undefined && createListButton !== null) {
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

    if (listSubmit !== 'undefined' && listSubmit !== null) {
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
    }

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

    if (contactSubmit !== 'undefined' && contactSubmit !== null) {
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
    }

    if (listBoard !== 'undefined' && listBoard !== null) {
        listBoard.addEventListener('click', event => {
            console.log("list-board event: ", event.target);
            const buttonCheck = event.target.nodeName === 'BUTTON';
            console.log("listBoardListener: before check");
            if (!buttonCheck || event.target.dataset.cmlistId === 'undefined') {
                return;
            }
            console.log("listBoardListener: after check");
            contactModal.toggle();
            document.getElementById('contact-list-id').value = event.target.dataset.cmlistId;
        });
    }

})();