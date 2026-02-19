document.getElementById('orderForm').addEventListener('submit', function(event) {
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;

    if (name === "" || email === "") {
        alert("Эй, заполни Имя и Email перед отправкой!");
        event.preventDefault(); // Останавливает отправку
    }
});