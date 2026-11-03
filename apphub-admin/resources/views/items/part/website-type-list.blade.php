<table class="table">
    <thead>
        <tr>
            <th>Tipe Website</th>
            <th>Status</th>
            <th>Dibuat Oleh</th>
            <th>Terakhir Diubah Oleh</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($websiteTypes as $type)
            <tr>
                <td>{{ $type->name }}</td>
                <td>
                    @if ($type->status)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-secondary">Nonaktif</span>
                    @endif
                </td>
                <td>{{ $type->createdBy->name ?? '-' }}</td>
                <td>{{ $type->modifiedBy->name ?? '-' }}</td>
                <td>
                    <form action="{{ route('website-type.destroy', $type->id) }}" method="POST" onsubmit="return confirm('Yakin ingin nonaktifkan?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Nonaktifkan</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
