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

    // Mobile table cards
    const enhanceAdminTables = (root = document) => {
        root.querySelectorAll(".admin-content table.table").forEach((table) => {
            const headers = Array.from(table.querySelectorAll("thead th")).map(
                (th) => th.textContent.replace(/\s+/g, " ").trim(),
            );

            if (!headers.length) return;

            table.classList.add("is-mobile-card-table");

            table.querySelectorAll("tbody tr").forEach((row) => {
                const cells = Array.from(row.children).filter(
                    (cell) => cell.tagName.toLowerCase() === "td",
                );
                const isEmptyRow =
                    cells.length === 1 &&
                    Number(cells[0].getAttribute("colspan") || 1) > 1;

                row.classList.toggle("table-empty-row", isEmptyRow);

                cells.forEach((cell, index) => {
                    if (isEmptyRow || cell.hasAttribute("colspan")) {
                        cell.removeAttribute("data-label");
                        return;
                    }

                    cell.setAttribute("data-label", headers[index] || "");
                });
            });
        });
    };

    enhanceAdminTables();

    const adminContent = document.querySelector(".admin-content");
    if (adminContent) {
        const tableObserver = new MutationObserver(() => enhanceAdminTables(adminContent));
        tableObserver.observe(adminContent, {
            childList: true,
            subtree: true,
        });
    }

    // Sidebar toggle
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.querySelector(".admin-sidebar");
    const sidebarOverlayQuery = window.matchMedia("(max-width: 1024px)");

    const isOverlaySidebar = () => sidebarOverlayQuery.matches;

    const closeOverlaySidebar = () => {
        if (!sidebar || !sidebarToggle) return;

        sidebar.classList.remove("open");
        document.body.classList.remove("admin-sidebar-open");
    };

    const syncSidebarToggle = () => {
        if (!sidebarToggle) return;

        if (isOverlaySidebar()) {
            sidebarToggle.setAttribute(
                "aria-expanded",
                sidebar?.classList.contains("open") ? "true" : "false",
            );
            sidebarToggle.setAttribute("aria-label", "Buka menu admin");
            return;
        }

        const isCollapsed = document.body.classList.contains("admin-sidebar-collapsed");
        sidebarToggle.setAttribute("aria-expanded", isCollapsed ? "false" : "true");
        sidebarToggle.setAttribute(
            "aria-label",
            isCollapsed ? "Tampilkan sidebar admin" : "Sembunyikan sidebar admin",
        );
    };

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", () => {
            if (isOverlaySidebar()) {
                const isOpen = sidebar.classList.toggle("open");
                document.body.classList.toggle("admin-sidebar-open", isOpen);
                syncSidebarToggle();
                return;
            }

            closeOverlaySidebar();
            document.body.classList.toggle("admin-sidebar-collapsed");
            syncSidebarToggle();
        });

        document.addEventListener("click", (e) => {
            const clickedOutsideSidebar = !sidebar.contains(e.target);
            const clickedOutsideToggle = !sidebarToggle.contains(e.target);

            if (
                sidebar.classList.contains("open") &&
                clickedOutsideSidebar &&
                clickedOutsideToggle
            ) {
                closeOverlaySidebar();
                syncSidebarToggle();
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && sidebar.classList.contains("open")) {
                closeOverlaySidebar();
                syncSidebarToggle();
            }
        });

        window.addEventListener("resize", () => {
            if (!isOverlaySidebar()) {
                closeOverlaySidebar();
            }

            syncSidebarToggle();
        });

        document.querySelectorAll(".admin-sidebar a").forEach((link) => {
            link.addEventListener("click", () => {
                const href = link.getAttribute("href") || "";

                if (
                    isOverlaySidebar() &&
                    href &&
                    !href.startsWith("javascript:")
                ) {
                    closeOverlaySidebar();
                    syncSidebarToggle();
                }
            });
        });

        syncSidebarToggle();
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
    form.querySelector('[name="product_category_id"]').value =
        btn.dataset.productCategory || "";
    form.querySelector('[name="brand"]').value = btn.dataset.brand || "";
    form.querySelector('[name="condition"]').value = btn.dataset.condition || "baru";
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
