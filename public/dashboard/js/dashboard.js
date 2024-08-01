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
})();

const modal = new bootstrap.Modal(document.getElementById("modal"));
htmx.on("htmx:afterSwap", (e) => {
    if (e.detail.target.id == "dialog") {
        modal.show();
    }
});
htmx.on("htmx:beforeSwap", (e) => {
    //console.log(e.detail.xhr.getResponseHeader('HX-Success'), "getheader from beforeSwap");
    let targetHeader = e.detail.xhr.getResponseHeader('HX-Success');
    //console.log(targetHeader, 'targetHeader');
    //console.log(e.detail.target.id, 'e.detail.target.id');
    if (targetHeader == "true") {
        //console.info('check limiting to just dialog');
        modal.hide();
        //e.detail.shouldSwap = false;
    }
});