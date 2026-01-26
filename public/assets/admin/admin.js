// Admin Panel JavaScript

(() => {
  // Modal functions
  window.openModal = (modalId) => {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById('modalBackdrop');
    if (modal && backdrop) {
      modal.classList.add('show');
      backdrop.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  };

  window.closeModal = (modalId) => {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById('modalBackdrop');
    if (modal && backdrop) {
      modal.classList.remove('show');
      backdrop.classList.remove('show');
      document.body.style.overflow = '';
    }
  };

  // Close modal on backdrop click
  const backdrop = document.getElementById('modalBackdrop');
  if (backdrop) {
    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) {
        const openModal = document.querySelector('.modal.show');
        if (openModal) {
          closeModal(openModal.id);
        }
      }
    });
  }

  // Close modal on close button
  document.querySelectorAll('.modal-close').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const modal = e.target.closest('.modal');
      if (modal) {
        closeModal(modal.id);
      }
    });
  });

  // Confirm delete
  window.confirmDelete = (itemName, callback) => {
    if (confirm(`Apakah Anda yakin ingin menghapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`)) {
      if (callback) callback();
    }
  };

  // File upload preview
  document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
          const preview = input.closest('.form-group')?.querySelector('.upload-preview');
          if (preview) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
          }
        };
        reader.readAsDataURL(file);
      }
    });
  });

  // Mobile sidebar toggle
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.admin-sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });
  }
})();
