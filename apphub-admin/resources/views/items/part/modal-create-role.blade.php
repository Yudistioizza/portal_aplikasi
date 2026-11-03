<!-- File: resources/views/items/part/modal-create-role.blade.php -->
<div class="modal fade" id="modal-create-role" tabindex="-1" aria-labelledby="modalCreateRoleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('role.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateRoleLabel">Tambah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Role</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama role" required>
                    </div>
                    <div class="mb-3">
                        <label for="guard_name" class="form-label">Nama Guard</label>
                        <input type="text" class="form-control" id="guard_name" name="guard_name" value="web" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
