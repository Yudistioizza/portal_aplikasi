<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Role</th>
            <th>Nama Guard</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($roles as $index => $role)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $role->name }}</td>
            <td>{{ $role->guard_name }}</td>
            <td>
                <!-- Tombol Edit -->
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $role->id }}">
                    Edit
                </button>

                <!-- Tombol Delete -->
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $role->id }}">
                    Hapus
                </button>
            </td>
        </tr>

        {{-- Modal Edit --}}
        @include('items.part.modal-edit-role', ['item' => $role])

        {{-- Modal Delete --}}
        @include('items.part.modal-delete-role', ['item' => $role])

        @empty
        <tr>
            <td colspan="4" class="text-center">Role tidak ditemukan</td>
        </tr>
        @endforelse
    </tbody>
</table>
