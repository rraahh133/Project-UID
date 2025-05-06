document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("service-form");
    const tableBody = document.querySelector("#service-table tbody");
    const totalServices = document.getElementById("total-services");

    // Simpan data di localStorage
    let services = JSON.parse(localStorage.getItem("services")) || [];

    const renderTable = () => {
        tableBody.innerHTML = "";
        services.forEach((service, index) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${service}</td>
                <td>
                    <button onclick="deleteService(${index})">Hapus</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
        totalServices.textContent = services.length;
    };

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const serviceName = document.getElementById("service-name").value;
        services.push(serviceName);
        localStorage.setItem("services", JSON.stringify(services));
        renderTable();
        form.reset();
    });

    window.deleteService = (index) => {
        services.splice(index, 1);
        localStorage.setItem("services", JSON.stringify(services));
        renderTable();
    };

    // Render awal
    renderTable();
});
