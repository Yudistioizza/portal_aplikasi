<div class="table-responsive">
    <table class="table">
        <thead class="thead">
            <tr>
                <th class="text-left">No</th>
                <th class="text-left">Tipe Website</th>
                <th class="text-left">Pemilik Bisnis</th>
                <th class="text-left">Nama Aplikasi</th>
                <th class="text-left">Logo</th>
                <th class="text-left">Deskripsi</th>
                <th class="text-left">Link Aplikasi</th>
                <th class="text-left">Status</th>
                <th class="text-left">
                    <a href="{{ route('list', ['sort' => 'updated_at', 'order' => $sort == 'updated_at' && $order == 'asc' ? 'desc' : 'asc'] + request()->except('page')) }}" 
                    class="d-flex justify-content-center align-items-center gap-1 text-decoration-none">
                        Terakhir Diperbarui
                        <div class="d-flex flex-column align-items-center" style="line-height: 0.8;">
                            <span class="{{ $sort == 'updated_at' && $order == 'asc' ? 'text-primary fw-bold' : 'text-muted' }}">▲</span>
                            <span class="{{ $sort == 'updated_at' && $order == 'desc' ? 'text-primary fw-bold' : 'text-muted' }}">▼</span>
                        </div>
                    </a>
                </th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($konten as $index => $item)
            <tr>
                <td>{{ $konten->firstItem() + $index }}</td>
                <td>{{ $item->websiteType ? $item->websiteType->name : '-' }}</td> <!-- Menampilkan nama tipe website -->
                <td>
                    @if($item->businessCategories->count() > 0)
                        <ul class="list-unstyled m-0">
                            @foreach($item->businessCategories as $category)
                                <li>{{ $category->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>          
                <td>{{ $item->app_name }}</td>
                <td>
                    @if(!empty($item->app_logo))
                        <img src="{{ $item->app_logo }}" alt="Logo {{ $item->app_name }}" width="100" class="img-thumbnail">
                    @else
                        <img src="{{ asset('img/apphub-mark.svg') }}" alt="AppHub" width="100" class="img-thumbnail">
                    @endif
                </td>
                <td>{{ Str::limit($item->app_desc, 50) }}</td>
                <td>
                    @php
                        $link = $item->app_link;
                        if (!empty($link) && !Str::startsWith($link, ['http://', 'https://'])) {
                            $link = 'https://' . $link;
                        }
                    @endphp

                    @if(!empty($link))
                        <a href="{{ $link }}" target="_blank">{{ $link }}</a>
                    @else
                        <span class="text-muted">Link belum tersedia</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->status == 'active')
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
                    {{ \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i') }}
                </td>
                <td>
                    <i class="bi bi-pencil-square text-warning fs-3" data-toggle="modal" data-target="#editModal{{ $item->id }}" style="cursor: pointer; font-size: 1.2rem;" data-toggle="tooltip" title="Edit"></i>
                    <form id="toggle-status-form-{{ $item->id }}" action="{{ route('list.toggleStatus', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                    </form>

                    @if($item->status == 'active')
                        <!-- Jika status ACTIVE tampil icon Trash -->
                        <i class="bi bi-trash-fill text-danger fs-3" 
                        style="cursor: pointer; font-size: 1.2rem;" 
                        data-toggle="tooltip" 
                        title="Nonaktifkan"
                        onclick="confirmToggleStatus({{ $item->id }}, '{{ $item->status }}')">
                        </i>
                    @else
                        <!-- Jika status INACTIVE tampil icon Restore -->
                        <i class="bi bi-arrow-counterclockwise text-success fs-3" 
                        style="cursor: pointer; font-size: 1.2rem;" 
                        data-toggle="tooltip" 
                        title="Aktifkan"
                        onclick="confirmToggleStatus({{ $item->id }}, '{{ $item->status }}')">
                        </i>
                    @endif


                    <!-- Modal Edit Dinamis -->
                    @include('items.part.modal-edit-content', ['item' => $item])
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $konten->links() }}
    </div>
</div>

<script>
    function confirmToggleStatus(id, status) {
        let actionText = (status === 'active') ? 'Nonaktifkan' : 'Aktifkan';
        let messageText = (status === 'active') 
            ? "Konten akan dinonaktifkan dan tidak ditampilkan ke publik." 
            : "Konten akan diaktifkan dan ditampilkan ke publik.";

        Swal.fire({
            title: `Yakin ingin ${actionText} konten ini?`,
            text: messageText,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: `Ya, ${actionText}!`,
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("toggle-status-form-" + id).submit();
                setTimeout(() => {
                    window.location.reload(); // Reload otomatis setelah submit
                }, 500); // Delay untuk memastikan form terkirim
            }
        });
    }
</script>
