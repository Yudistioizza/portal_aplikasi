@if(session('success'))
<div id="success-alert" class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="padding: 15px; border-radius: 8px;">
    <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>

<script>
    setTimeout(() => {
        let alert = document.getElementById("success-alert");
        if (alert) {
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
</script>
@endif
