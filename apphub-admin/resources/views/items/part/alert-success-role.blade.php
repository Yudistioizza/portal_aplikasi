{{-- resources/views/items/part/alert-success.blade.php --}}

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>

    {{-- Auto close alert dalam 3 detik --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('fade');
                    alert.classList.remove('show');
                    setTimeout(() => {
                        alert.remove();
                    }, 500); // Delay untuk smooth fade
                }, 3000); // Durasi tampil
            }
        });
    </script>

    {{-- Optional: CSS untuk smooth fade --}}
    <style>
        .alert.fade {
            opacity: 0;
            transition: opacity 0.5s linear;
        }
    </style>
@endif
