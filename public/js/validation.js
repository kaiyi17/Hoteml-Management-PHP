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

    if (!valid) {
      e.preventDefault();
    }
  });
});

document.querySelectorAll(".book-room").forEach((button) => {
  button.addEventListener("click", function () {
    const roomNumber = this.getAttribute("data-room-number");

    fetch("book_room.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ roomNumber: roomNumber }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Room booked successfully!");
        } else {
          alert("Error booking the room.");
        }
      });
  });
});
