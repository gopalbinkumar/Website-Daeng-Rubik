@extends('admin.layouts.app')

@section('title', 'Admin')
@section('page-title', 'Admin')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Admin</h2>
        <button class="btn btn-primary" onclick="openModal('modalAddAdmin')">
            + Tambah Admin
        </button>
    </div>

    <div class="table-wrapper">
        <div class="table-toolbar">
            <input type="text" class="search-input" placeholder="🔍 Search admin...">
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">Avatar</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="user-avatar" style="margin: 0 auto;">A1</div>
                    </td>
                    <td><strong>Admin 1</strong></td>
                    <td>admin1@daengrubik.com</td>
                    <td><span class="badge badge-danger">Super Admin</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditAdmin')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" disabled title="Tidak dapat dihapus">🗑️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="user-avatar" style="margin: 0 auto;">A2</div>
                    </td>
                    <td><strong>Admin 2</strong></td>
                    <td>admin2@daengrubik.com</td>
                    <td><span class="badge badge-info">Admin</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditAdmin')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Admin 2', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="user-avatar" style="margin: 0 auto;">A3</div>
                    </td>
                    <td><strong>Admin 3</strong></td>
                    <td>admin3@daengrubik.com</td>
                    <td><span class="badge badge-success">Editor</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditAdmin')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Admin 3', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="pagination">
            <div class="pagination-info">Menampilkan 1-10 dari 5 admin</div>
            <div class="pagination-controls">
                <button class="page-btn active">1</button>
            </div>
        </div>
    </div>

    <!-- Modal Add Admin -->
    <div id="modalAddAdmin" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Admin</h3>
            <button class="modal-close" onclick="closeModal('modalAddAdmin')">×</button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Avatar</label>
                    <div class="upload-area" onclick="document.getElementById('adminAvatar').click()" style="width: 120px; padding: 20px;">
                        <p style="font-size: 12px;">Upload Avatar</p>
                    </div>
                    <input type="file" id="adminAvatar" accept="image/*" style="display: none;">
                    <div class="upload-preview"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama <span class="required">*</span></label>
                    <input type="text" class="form-input" placeholder="Nama admin">
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" class="form-input" placeholder="admin@daengrubik.com">
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span class="required">*</span></label>
                    <input type="password" class="form-input" placeholder="Minimal 8 karakter">
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select class="form-select">
                        <option>Pilih Role</option>
                        <option>Super Admin</option>
                        <option>Admin</option>
                        <option>Editor</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalAddAdmin')">Batal</button>
            <button class="btn btn-primary">Simpan Admin</button>
        </div>
    </div>

    <!-- Modal Edit Admin -->
    <div id="modalEditAdmin" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Admin</h3>
            <button class="modal-close" onclick="closeModal('modalEditAdmin')">×</button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Avatar</label>
                    <div class="upload-area" onclick="document.getElementById('editAdminAvatar').click()" style="width: 120px; padding: 20px;">
                        <p style="font-size: 12px;">Upload Avatar</p>
                    </div>
                    <input type="file" id="editAdminAvatar" accept="image/*" style="display: none;">
                    <div class="upload-preview">
                        <div class="user-avatar" style="width: 80px; height: 80px; font-size: 32px;">A2</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Admin 2">
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" class="form-input" value="admin2@daengrubik.com">
                </div>

                <div class="form-group">
                    <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select class="form-select">
                        <option>Admin</option>
                        <option>Super Admin</option>
                        <option>Editor</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalEditAdmin')">Batal</button>
            <button class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>
@endsection
