// public/js/validation.js

document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  form.addEventListener("submit", function (e) {
    let valid = true;

    const name = form.querySelector("[name='name']");
    if (name.value.trim() === "") {
      alert("Name is required!");
      valid = false;
    }

    // ...其他字段的验证...

    if (!valid) {
      e.preventDefault();
    }
  });
});


document.querySelectorAll('.book-room').forEach(button => {
  button.addEventListener('click', function () {
    const roomNumber = this.getAttribute('data-room-number');

    // 发送AJAX请求到服务器预订房间
    fetch('book_room.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ roomNumber: roomNumber })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Room booked successfully!');
        } else {
          alert('Error booking the room.');
        }
      });
  });
});