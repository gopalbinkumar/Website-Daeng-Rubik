// Admin Panel JavaScript

(() => {
    // Modal functions
    window.openModal = (modalId) => {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById("modalBackdrop");
        if (modal && backdrop) {
            modal.classList.add("show");
            backdrop.classList.add("show");
            document.body.style.overflow = "hidden";
        }
    };

    window.closeModal = (modalId) => {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById("modalBackdrop");
        if (modal && backdrop) {
            modal.classList.remove("show");
            backdrop.classList.remove("show");
            document.body.style.overflow = "";
        }
    };

    // Close modal on backdrop click
    const backdrop = document.getElementById("modalBackdrop");
    if (backdrop) {
        backdrop.addEventListener("click", (e) => {
            if (e.target === backdrop) {
                const openModal = document.querySelector(".modal.show");
                if (openModal) {
                    closeModal(openModal.id);
                }
            }
        });
    }

    // Close modal on close button
    document.querySelectorAll(".modal-close").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const modal = e.target.closest(".modal");
            if (modal) {
                closeModal(modal.id);
            }
        });
    });

    // Confirm delete
    window.confirmDelete = (itemName, callback) => {
        if (
            confirm(
                `Apakah Anda yakin ingin menghapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`,
            )
        ) {
            if (callback) callback();
        }
    };

    // File upload preview
    document
        .querySelectorAll('input[type="file"][data-preview]')
        .forEach((input) => {
            input.addEventListener("change", function () {
                const file = this.files[0];
                if (!file) return;

                const label = this.closest(".upload-box");
                const img = label.querySelector(".upload-preview-img");
                const text = label.querySelector("span");

                img.src = URL.createObjectURL(file);
                img.hidden = false;
                text.style.display = "none";
            });
        });

    // Mobile sidebar toggle
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.querySelector(".admin-sidebar");
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("open");
        });
    }
})();

function openEditProduct(btn) {
    const form = document.getElementById("formEditProduct");
    const id = btn.dataset.id;

    /* =============================
       SET ACTION FORM
    ============================== */
    form.action = `/admin/produk/${id}`;

    /* =============================
       TEXT & NUMBER INPUT
    ============================== */
    form.querySelector('[name="name"]').value = btn.dataset.name || "";
    form.querySelector('[name="price"]').value = btn.dataset.price || "";
    form.querySelector('[name="stock"]').value = btn.dataset.stock || "";
    form.querySelector('[name="description"]').value =
        btn.dataset.description || "";

    /* =============================
       SELECT
    ============================== */
    form.querySelector('[name="cube_category_id"]').value =
        btn.dataset.cubeCategory || "";
    form.querySelector('[name="brand"]').value = btn.dataset.brand || "";
    form.querySelector('[name="difficulty_level"]').value =
        btn.dataset.difficulty || "";
    form.querySelector('[name="is_active"]').value =
        btn.dataset.active == 1 ? 1 : 0;

    /* =============================
       MARKETPLACE (INI WAJIB)
    ============================== */
    form.querySelector('[name="marketplace_links[tokopedia]"]').value =
        btn.dataset.tokopedia || "";

    form.querySelector('[name="marketplace_links[shopee]"]').value =
        btn.dataset.shopee || "";

    form.querySelector('[name="marketplace_links[tiktok_shop]"]').value =
        btn.dataset.tiktok || "";

    /* =============================
       RESET PREVIEW GAMBAR
    ============================== */
    const previews = form.querySelectorAll(".upload-preview-img");
    previews.forEach((img) => {
        img.hidden = true;
        img.src = "";
    });

    /* =============================
       TAMPILKAN GAMBAR LAMA (URUT)
    ============================== */
    let images = [];
    try {
        images = JSON.parse(btn.dataset.images || "[]");
    } catch (e) {
        images = [];
    }

    images.forEach((src, index) => {
        if (previews[index]) {
            previews[index].src = src;
            previews[index].hidden = false;
        }
    });

    /* =============================
       OPEN MODAL
    ============================== */
    openModal("modalEditProduct");
}
