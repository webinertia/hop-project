document.body.addEventListener("systemMessage", (evt) => {
    if(evt.detail.level === "success") {
        alert(evt.detail.message);
    }
});

