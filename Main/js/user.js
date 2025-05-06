function editProfile(field) {
    let currentValue = document.getElementById(field + '-value').innerText;
    let newValue = prompt("Edit " + field.charAt(0).toUpperCase() + field.slice(1), currentValue);
    if (newValue !== null && newValue.trim() !== "") {
        document.getElementById(field + '-value').innerText = newValue;
    }
}