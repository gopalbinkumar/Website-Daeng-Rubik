// Auth Pages JavaScript

(() => {
    // Toggle password visibility
    document.querySelectorAll(".toggle-password").forEach((btn) => {
        btn.addEventListener("click", () => {
            const input = btn.previousElementSibling;
            if (input) {
                const type = input.type === "password" ? "text" : "password";
                input.type = type;
                btn.textContent = type === "password" ? "👁" : "👁‍🗨";
            }
        });
    });

    // Password strength checker
    const passwordInput = document.getElementById("password");
    const strengthBar = document.querySelector(".strength-fill");
    const strengthText = document.querySelector(".strength-text");

    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener("input", (e) => {
            const password = e.target.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;

            // Update bar
            strengthBar.className = "strength-fill";
            strengthText.className = "strength-text";

            if (password.length === 0) {
                strengthBar.style.width = "0%";
                strengthText.textContent = "";
            } else if (strength <= 1) {
                strengthBar.classList.add("weak");
                strengthText.classList.add("weak");
                strengthText.textContent = "Lemah";
            } else if (strength === 2 || strength === 3) {
                strengthBar.classList.add("medium");
                strengthText.classList.add("medium");
                strengthText.textContent = "Sedang";
            } else {
                strengthBar.classList.add("strong");
                strengthText.classList.add("strong");
                strengthText.textContent = "Kuat";
            }
        });
    }

    // Confirm password validation
    const confirmPassword = document.getElementById("confirmPassword");
    if (confirmPassword && passwordInput) {
        confirmPassword.addEventListener("input", () => {
            if (
                confirmPassword.value &&
                confirmPassword.value !== passwordInput.value
            ) {
                confirmPassword.classList.add("error");
                confirmPassword.classList.remove("success");
                const errorMsg =
                    confirmPassword.parentElement.querySelector(".form-error");
                if (errorMsg) {
                    errorMsg.style.display = "flex";
                }
            } else if (
                confirmPassword.value === passwordInput.value &&
                confirmPassword.value.length > 0
            ) {
                confirmPassword.classList.remove("error");
                confirmPassword.classList.add("success");
                const errorMsg =
                    confirmPassword.parentElement.querySelector(".form-error");
                if (errorMsg) {
                    errorMsg.style.display = "none";
                }
            }
        });
    }

    // Form validation on submit
    // const registerForm = document.getElementById("registerForm");

    // if (registerForm) {
    //     registerForm.addEventListener("submit", (e) => {
    //         // validasi JS SAJA
    //         const passwordInput = document.getElementById("password");
    //         const confirmPassword = document.getElementById("confirmPassword");

    //         if (confirmPassword.value !== passwordInput.value) {
    //             e.preventDefault();
    //             alert("Konfirmasi password tidak cocok");
    //             return;
    //         }
    //     });
    // }

    // Login form
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const submitBtn = loginForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add("loading");
                submitBtn.disabled = true;

                setTimeout(() => {
                    alert("Login berhasil! (UI Demo - belum ada backend)");
                    submitBtn.classList.remove("loading");
                    submitBtn.disabled = false;
                }, 1500);
            }
        });
    }
})();
