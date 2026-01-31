// ================================
// SECTION: KATEGORI EVENT (ADD)
// ================================

// Ambil elemen select kategori event
const category = document.getElementById("eventCategory");

// Group input yang akan ditampilkan / disembunyikan
const price = document.getElementById("priceGroup");
const quota = document.getElementById("quotaGroup");
const prize = document.getElementById("prizeGroup");
const lomba = document.getElementById("competitionCategoryGroup");
const customCategory = document.getElementById("customCategoryGroup");
const status = document.getElementById("statusGroup");
const competitionTitle = document.getElementById("competitionTitle");

// Sembunyikan semua field
function hideAll() {
    price.style.display = "none";
    quota.style.display = "none";
    prize.style.display = "none";
    lomba.style.display = "none";
    customCategory.style.display = "none";
    status.style.display = "none";
    competitionTitle.style.display = "none";
}

// Tampilkan field sesuai kategori yang dipilih
function handleCategory() {
    hideAll();

    switch (category.value) {
        case "kompetisi":
            // Jika kompetisi → tampilkan semua field kompetisi
            price.style.display = "block";
            quota.style.display = "block";
            prize.style.display = "block";
            lomba.style.display = "block";
            status.style.display = "block";
            competitionTitle.style.display = "block";
            break;

        case "gathering":
            // Gathering hanya butuh status
            status.style.display = "block";
            break;

        case "lainnya":
            // Lainnya → input kategori custom + status
            customCategory.style.display = "block";
            status.style.display = "block";
            break;

        default:
            // Belum pilih kategori → tidak tampilkan apa pun
            break;
    }
}

// Jalankan saat halaman pertama kali load
handleCategory();

// Jalankan ulang saat kategori berubah
category.addEventListener("change", handleCategory);

// ================================
// SECTION: KATEGORI LOMBA (ADD)
// ================================

const select = document.getElementById("competitionCategorySelect");
const input = document.getElementById("competitionCategoryInput");
const addBtn = document.getElementById("addCategoryBtn");
const tagList = document.querySelector("#competitionCategoryGroup .tag-list");
const form = document.querySelector("#modalAddEvent form");

// Saat option dipilih → isi input dengan nama kategori
select.addEventListener("change", () => {
    if (!select.value) {
        input.value = "";
        return;
    }

    input.value = select.options[select.selectedIndex].text;
});

// Klik tombol "+ Tambah" → buat tag kategori
addBtn.addEventListener("click", () => {
    const categoryId = select.value;
    const categoryName = input.value.trim();

    // Validasi kosong
    if (!categoryId || !categoryName) return;

    // Cegah kategori duplikat
    if (
        form.querySelector(
            `input[name="competition_categories[]"][value="${categoryId}"]`
        )
    )
        return;

    // Buat tampilan tag
    const tag = document.createElement("span");
    tag.className = "tag";
    tag.innerHTML = `${categoryName} ✕`;

    // Hidden input untuk dikirim ke backend
    const hidden = document.createElement("input");
    hidden.type = "hidden";
    hidden.name = "competition_categories[]";
    hidden.value = categoryId;

    // Klik tag → hapus tag & input
    tag.addEventListener("click", () => {
        tag.remove();
        hidden.remove();
    });

    tagList.appendChild(tag);
    form.appendChild(hidden);

    // Reset select & input
    select.value = "";
    input.value = "";
});

// ================================
// SECTION: OPEN EDIT EVENT
// ================================

function openEditEvent(btn) {
    const modal = document.getElementById("modalEditEvent");
    const form = document.getElementById("editEventForm");

    // ===============================
    // SET ACTION FORM
    // ===============================
    form.action = `/admin/event/${btn.dataset.id}`;

    // ===============================
    // ISI FIELD DASAR
    // ===============================
    form.querySelector('[name="title"]').value = btn.dataset.title;
    form.querySelector('[name="description"]').value = btn.dataset.description;
    form.querySelector('[name="start_date"]').value = btn.dataset.startDate;
    form.querySelector('[name="start_time"]').value = btn.dataset.startTime;
    form.querySelector('[name="end_date"]').value = btn.dataset.endDate;
    form.querySelector('[name="end_time"]').value = btn.dataset.endTime;
    form.querySelector('[name="location"]').value = btn.dataset.location;

    // ===============================
    // KATEGORI EVENT
    // ===============================
    form.querySelector('[name="category"]').value = btn.dataset.category;

    const customInput = form.querySelector('[name="custom_category"]');
    if (customInput) {
        customInput.value = btn.dataset.customCategory || "";
    }

    // ===============================
    // STATUS
    // ===============================
    form.querySelector('[name="status"]').value = btn.dataset.status;

    // ===============================
    // FIELD KOMPETISI
    // ===============================
    if (form.querySelector('[name="ticket_price"]')) {
        form.querySelector('[name="ticket_price"]').value =
            btn.dataset.ticketPrice ?? "";
        form.querySelector('[name="max_participants"]').value =
            btn.dataset.maxParticipants ?? "";
        form.querySelector('[name="total_prize"]').value =
            btn.dataset.totalPrize ?? "";
    }

    // ===============================
    // COVER IMAGE PREVIEW (🔥 FIX UTAMA)
    // ===============================
    const coverInput = form.querySelector('[name="cover_image"]');
    const coverPreview = document.getElementById("editCoverPreview");

    // reset input supaya bisa upload ulang
    coverInput.value = "";

    // tampilkan cover lama jika ada
    if (btn.dataset.cover) {
        coverPreview.src = btn.dataset.cover;
        coverPreview.style.display = "block";
    } else {
        coverPreview.src = "";
        coverPreview.style.display = "none";
    }

    // ===============================
    // RESET & LOAD KATEGORI LOMBA
    // ===============================
    editTagList.innerHTML = "";
    editForm
        .querySelectorAll('input[name="competition_categories[]"]')
        .forEach((i) => i.remove());

    const categories = JSON.parse(btn.dataset.competitionCategories || "[]");
    categories.forEach((cat) => {
        addEditTag(cat.id, cat.name);
    });

    // ===============================
    // TRIGGER LOGIC SHOW/HIDE
    // ===============================
    form
        .querySelector('[name="category"]')
        .dispatchEvent(new Event("change"));

    // ===============================
    // OPEN MODAL
    // ===============================
    openModal("modalEditEvent");
}


// ================================
// SECTION: KATEGORI EVENT (EDIT)
// ================================

const editCategory = document.getElementById("editEventCategory");
const editPrice = document.getElementById("editPriceGroup");
const editQuota = document.getElementById("editQuotaGroup");
const editPrize = document.getElementById("editPrizeGroup");
const editLomba = document.getElementById("editCompetitionCategoryGroup");
const editCustomCategory = document.getElementById("editCustomCategoryGroup");
const editStatus = document.getElementById("editStatusGroup");
const editCompetitionTitle = document.getElementById("editCompetitionTitle");

// Sembunyikan semua field edit
function hideAllEdit() {
    editPrice.style.display = "none";
    editQuota.style.display = "none";
    editPrize.style.display = "none";
    editLomba.style.display = "none";
    editCustomCategory.style.display = "none";
    editStatus.style.display = "none";
    editCompetitionTitle.style.display = "none";
}

// Tampilkan field edit sesuai kategori
function handleEditCategory() {
    hideAllEdit();

    switch (editCategory.value) {
        case "kompetisi":
            editPrice.style.display = "block";
            editQuota.style.display = "block";
            editPrize.style.display = "block";
            editLomba.style.display = "block";
            editStatus.style.display = "block";
            editCompetitionTitle.style.display = "block";
            break;

        case "gathering":
            editStatus.style.display = "block";
            break;

        case "lainnya":
            editCustomCategory.style.display = "block";
            editStatus.style.display = "block";
            break;
    }
}

editCategory.addEventListener("change", handleEditCategory);

// ================================
// SECTION: KATEGORI LOMBA (EDIT)
// ================================

const editSelect = document.getElementById("editCompetitionCategorySelect");
const editInput = document.getElementById("editCompetitionCategoryInput");
const editAddBtn = document.getElementById("editAddCategoryBtn");
const editTagList = document.getElementById("editTagList");
const editForm = document.getElementById("editEventForm");

// Isi input otomatis saat pilih kategori
editSelect.addEventListener("change", () => {
    editInput.value =
        editSelect.options[editSelect.selectedIndex]?.text || "";
});

// Tambah kategori lomba ke event
editAddBtn.addEventListener("click", () => {
    const id = editSelect.value;
    const name = editInput.value.trim();

    if (!id || !name) return;

    // Cegah duplikat
    if (
        editForm.querySelector(
            `input[name="competition_categories[]"][value="${id}"]`
        )
    )
        return;

    addEditTag(id, name);

    editSelect.value = "";
    editInput.value = "";
});

// Helper untuk membuat tag edit
function addEditTag(id, name) {
    const tag = document.createElement("span");
    tag.className = "tag";
    tag.innerHTML = `${name} ✕`;

    const hidden = document.createElement("input");
    hidden.type = "hidden";
    hidden.name = "competition_categories[]";
    hidden.value = id;

    // Klik tag → hapus
    tag.addEventListener("click", () => {
        tag.remove();
        hidden.remove();
    });

    editTagList.appendChild(tag);
    editForm.appendChild(hidden);
}

document.addEventListener("change", function (e) {
    // hanya untuk input file yang punya data-preview
    if (e.target.matches('input[type="file"][data-preview]')) {
        const file = e.target.files[0];
        const previewImg = e.target
            .closest(".upload-box")
            .querySelector("img");

        if (file && previewImg) {
            previewImg.src = URL.createObjectURL(file);
            previewImg.style.display = "block";
        }
    }
});

