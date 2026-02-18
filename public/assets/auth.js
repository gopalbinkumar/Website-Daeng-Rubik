// Auth Pages JavaScript (FIXED & FINAL)

(() => {
    document.addEventListener("DOMContentLoaded", () => {
        /* ===============================
           TOGGLE PASSWORD (LOGIN & REGISTER)
        =============================== */
        document.querySelectorAll(".toggle-password").forEach((btn) => {
            btn.addEventListener("click", () => {
                const wrapper = btn.closest(".input-wrapper");
                if (!wrapper) return;

                const input = wrapper.querySelector(".form-input");
                if (!input) return;

                if (input.type === "password") {
                    input.type = "text";
                    btn.innerHTML = '<i class="fa-regular fa-eye-slash"></i>';
                } else {
                    input.type = "password";
                    btn.innerHTML = '<i class="fa-regular fa-eye"></i>';
                }
            });
        });

        /* ===============================
           PASSWORD STRENGTH + MIN 8 CHAR
           (REGISTER ONLY)
        =============================== */
        const passwordInput = document.getElementById("password");
        const strengthBar = document.querySelector(".strength-fill");
        const strengthText = document.querySelector(".strength-text");

        if (passwordInput && strengthBar && strengthText) {
            passwordInput.addEventListener("input", (e) => {
                const password = e.target.value;
                let strength = 0;

                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password))
                    strength++;
                if (/\d/.test(password)) strength++;
                if (/[^a-zA-Z\d]/.test(password)) strength++;

                strengthBar.className = "strength-fill";
                strengthText.className = "strength-text";

                if (password.length === 0) {
                    strengthBar.style.width = "0%";
                    strengthText.textContent = "";
                } else if (password.length < 8) {
                    strengthBar.style.width = "30%";
                    strengthBar.classList.add("weak");
                    strengthText.classList.add("weak");
                    strengthText.textContent = "Minimal 8 karakter";
                } else if (strength <= 2) {
                    strengthBar.style.width = "50%";
                    strengthBar.classList.add("medium");
                    strengthText.classList.add("medium");
                    strengthText.textContent = "Sedang";
                } else {
                    strengthBar.style.width = "100%";
                    strengthBar.classList.add("strong");
                    strengthText.classList.add("strong");
                    strengthText.textContent = "Kuat";
                }
            });
        }

        /* ===============================
           CONFIRM PASSWORD VALIDATION
        =============================== */
        const confirmPassword = document.getElementById("confirmPassword");

        if (passwordInput && confirmPassword) {
            confirmPassword.addEventListener("input", () => {
                const errorMsg =
                    confirmPassword.parentElement.querySelector(".form-error");

                if (
                    confirmPassword.value &&
                    confirmPassword.value !== passwordInput.value
                ) {
                    confirmPassword.classList.add("error");
                    confirmPassword.classList.remove("success");
                    if (errorMsg) errorMsg.style.display = "flex";
                } else if (
                    confirmPassword.value === passwordInput.value &&
                    confirmPassword.value.length >= 8
                ) {
                    confirmPassword.classList.remove("error");
                    confirmPassword.classList.add("success");
                    if (errorMsg) errorMsg.style.display = "none";
                }
            });
        }

        /* ===============================
           REGISTER FORM FINAL CHECK
        =============================== */
        const registerForm = document.querySelector('form[action*="register"]');

        if (registerForm && passwordInput && confirmPassword) {
            registerForm.addEventListener("submit", (e) => {
                if (passwordInput.value.length < 8) {
                    e.preventDefault();
                    alert("Password minimal 8 karakter");
                    passwordInput.focus();
                    return;
                }

                if (passwordInput.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert("Konfirmasi password tidak cocok");
                    confirmPassword.focus();
                    return;
                }
            });
        }
    });
})();
