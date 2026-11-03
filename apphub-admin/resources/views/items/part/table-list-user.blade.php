<div class="table-responsive">
    <table class="table">
        <thead class="thead">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Tanggal Ditambahkan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td></td>
                <td class="text-center">
                    @if($user->status == 'active')
                        <span class="badge rounded-pill bg-success px-3 py-2" style="font-size: 0.85rem;">
                            <i class="bi bi-check-circle-fill"></i> Aktif
                        </span>
                    @else
                        <span class="badge rounded-pill bg-danger px-3 py-2" style="font-size: 0.85rem;">
                            <i class="bi bi-x-circle-fill"></i> Nonaktif
                        </span>
                    @endif
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($user->updated_at)->format('d-m-Y H:i') }}
                </td>
                <td>
                    <i class="bi bi-pencil-square text-warning fs-3" data-toggle="modal" data-target="#editModal{{ $user->id }}" style="cursor: pointer;" title="Edit"></i>
                    <form id="delete-form-{{ $user->id }}" action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <i class="bi bi-trash-fill text-danger fs-3" style="cursor: pointer;" title="Hapus" onclick="confirmDelete({{ $user->id }})"></i>
                    </form>
                </td>
            </tr>

            @include('items.part.modal-edit-user', ['user' => $user]) <!-- Panggil modal edit per user -->
            @endforeach
        </tbody>
    </table>
</div>