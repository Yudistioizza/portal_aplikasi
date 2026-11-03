<table class="table">
    <thead>
        <tr>
            <th>Pemilik Website</th>
            <th>Status</th>
            <th>Dibuat Oleh</th>
            <th>Terakhir Diubah Oleh</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>
                    @if ($category->status)
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge badge-secondary">Nonaktif</span>
                    @endif
                </td>
                <td>{{ $category->createdBy->name ?? '-' }}</td>
                <td>{{ $category->modifiedBy->name ?? '-' }}</td>
                <td>
                    <form action="{{ route('business-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin nonaktifkan?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Nonaktifkan</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
