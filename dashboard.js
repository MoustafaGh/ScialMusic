document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("#searchInput");
    const rows = document.querySelectorAll("tbody tr");

    searchInput.addEventListener("keyup", function (e) {
        const searchTerm = e.target.value.toLowerCase();

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchTerm) ? "" : "none";
        });
    });
});
