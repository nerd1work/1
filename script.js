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

// في go.html
document.addEventListener("DOMContentLoaded", function() {
  var username = localStorage.getItem("username");
  if (!username) {
    window.location.href = "4.html"; // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
  }
  document.getElementById("username").textContent = username;
});

function logout() {
  localStorage.removeItem("username");
  window.location.href = "4.html";
}
