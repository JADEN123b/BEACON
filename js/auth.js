// Authentication form switching
function switchToRegister() {
  const loginSection = document.querySelector(".form-section.login");
  const registerSection = document.querySelector(".form-section.register");
  if (loginSection && registerSection) {
    loginSection.style.display = "none";
    registerSection.style.display = "block";
  }
  // toggle header tabs (if present)
  const tabs = document.querySelectorAll(".header-tab");
  if (tabs.length >= 2) {
    tabs.forEach((t) => t.classList.remove("active"));
    tabs[1].classList.add("active");
  }
}

function switchToLogin() {
  const loginSection = document.querySelector(".form-section.login");
  const registerSection = document.querySelector(".form-section.register");
  if (loginSection && registerSection) {
    registerSection.style.display = "none";
    loginSection.style.display = "block";
  }
  // toggle header tabs (if present)
  const tabs = document.querySelectorAll(".header-tab");
  if (tabs.length >= 1) {
    tabs.forEach((t) => t.classList.remove("active"));
    tabs[0].classList.add("active");
  }
}

// Form validation
document.addEventListener("DOMContentLoaded", function () {
  // Login form validation
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const email = document.getElementById("loginEmail").value;
      const password = document.getElementById("loginPassword").value;

      // Basic validation
      if (!email || !password) {
        showMessage("Please fill in all fields", "error");
        return;
      }

      if (!validateEmail(email)) {
        showMessage("Please enter a valid email address", "error");
        return;
      }

      // Submit form
      this.submit();
    });
  }

  // Register form validation
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const fullname = document.getElementById("registerName").value;
      const email = document.getElementById("registerEmail").value;
      const password = document.getElementById("registerPassword").value;
      const confirmPassword = document.getElementById(
        "registerConfirmPassword",
      ).value;

      // Basic validation
      if (!fullname || !email || !password || !confirmPassword) {
        showMessage("Please fill in all fields", "error");
        return;
      }

      if (!validateEmail(email)) {
        showMessage("Please enter a valid email address", "error");
        return;
      }

      if (password.length < 6) {
        showMessage("Password must be at least 6 characters long", "error");
        return;
      }

      if (password !== confirmPassword) {
        showMessage("Passwords do not match", "error");
        return;
      }

      // Submit form
      this.submit();
    });
  }
});

// Email validation
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Show message function
function showMessage(message, type) {
  // Create message element if it doesn't exist
  let messageDiv = document.querySelector(".message");
  if (!messageDiv) {
    messageDiv = document.createElement("div");
    messageDiv.className = "message";
    // insert at top of auth card if present, otherwise at body top
    const container = document.querySelector(".auth-card") || document.body;
    container.insertBefore(messageDiv, container.firstChild);
  }

  messageDiv.textContent = message;
  messageDiv.className = `message ${type}`;

  // Remove message after 3 seconds
  setTimeout(() => {
    messageDiv.remove();
  }, 3000);
}

// Add message styles to head
const messageStyles = `
    .message {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 5px;
        text-align: center;
        font-size: 0.9rem;
    }
    
    .message.error {
        background-color: #fee;
        color: #c33;
        border: 1px solid #fcc;
    }
    
    .message.success {
        background-color: #efe;
        color: #3c3;
        border: 1px solid #cfc;
    }
`;

const styleSheet = document.createElement("style");
styleSheet.textContent = messageStyles;
document.head.appendChild(styleSheet);
