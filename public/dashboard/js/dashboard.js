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
        // end sortables
        //htmx.process(content);
    });
})();