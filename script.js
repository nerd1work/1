document.getElementById("login-form").addEventListener("submit", function(event) {
  event.preventDefault();

  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;

  fetch("users.json")
    .then(response => response.json())
    .then(data => {
      var users = data.users;
      var authenticatedUser = users.find(user => user.username === username && user.password === password);
      
      if (authenticatedUser) {
        localStorage.setItem("username", authenticatedUser.username);
        window.location.href = "go.html";
      } else {
        document.getElementById("error-message").textContent = "اسم المستخدم أو كلمة المرور غير صحيحة.";
      }
    })
    .catch(error => console.error("حدث خطأ: ", error));
});
