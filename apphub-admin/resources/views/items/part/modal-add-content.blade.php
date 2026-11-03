<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Konten</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('list.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Aplikasi:</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="website_type_id">Tipe Website</label>
                        <select name="website_type_id" class="form-control" required>
                            <option value="">-- Pilih Tipe Website --</option>
                            @foreach($websiteTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="ownerContainer">
                        <label for="business_category_id">Pemilik Bisnis</label>
                        <div class="input-group">
                            <!-- Dropdown Multi-select untuk memilih banyak pemilik -->
                            <select name="business_category_id[]" class="form-control w-25 me-3 mb-2" required>
                                <option value="">-- Pilih Pemilik Bisnis --</option>
                                @foreach($businessCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span class="input-group-btn mt-1">
                                <button type="button" class="btn btn-success" id="addOwnerButton">+</button>
                                <button type="button" class="btn btn-danger" id="removeOwnerButton">−</button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi:</label>
                        <textarea name="deskripsi" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Link Aplikasi:</label>
                        <input type="url" name="link_aplikasi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Logo:</label>
                        <input type="file" name="logo" class="form-control" id="logoInput" onchange="previewLogo(event)">
                    </div>
                    <div class="form-group">
                        <img id="logoPreview" src="#" alt="Preview Logo" class="img-thumbnail mt-2" style="display: none; max-width: 200px; height: auto;">
                    </div>

                    <script>
                        function previewLogo(event) {
                            const input = event.target;
                            const preview = document.getElementById("logoPreview");

                            if (input.files && input.files[0]) {
                                const reader = new FileReader();

                                reader.onload = function(e) {
                                    preview.src = e.target.result;
                                    preview.style.display = "block"; // Tampilkan gambar
                                };

                                reader.readAsDataURL(input.files[0]);
                            } else {
                                preview.src = "#";
                                preview.style.display = "none"; // Sembunyikan jika tidak ada gambar
                            }
                        }

                        document.addEventListener("DOMContentLoaded", function () {
                            const addButton = document.getElementById("addOwnerButton");
                            const removeButton = document.getElementById("removeOwnerButton");
                            const container = document.getElementById("ownerContainer");

                            addButton.addEventListener("click", function () {
                                if (!container) {
                                    console.error("Elemen #ownerContainer tidak ditemukan.");
                                    return;
                                }

                                // Ambil semua value yang sudah dipilih
                                const selectedValues = Array.from(container.querySelectorAll("select")).map(select => select.value);

                                // Buat form-group baru
                                const newFormGroup = document.createElement("div");
                                newFormGroup.classList.add("form-group", "mt-2");

                                // Buat dropdown baru
                                const newSelect = document.createElement("select");
                                newSelect.name = "business_category_id[]";
                                newSelect.classList.add("form-control", "w-75");
                                newSelect.required = true;

                                // Default option
                                const defaultOption = document.createElement("option");
                                defaultOption.value = "";
                                defaultOption.textContent = "-- Pilih Pemilik Bisnis --";
                                newSelect.appendChild(defaultOption);

                                // Tambahkan option berdasarkan yang belum dipilih
                                let hasAvailable = false;

                                @foreach($businessCategories as $category)
                                    if (!selectedValues.includes("{{ $category->id }}")) {
                                        const option = document.createElement("option");
                                        option.value = "{{ $category->id }}";
                                        option.textContent = "{{ $category->name }}";
                                        newSelect.appendChild(option);
                                        hasAvailable = true;
                                    }
                                @endforeach

                                if (!hasAvailable) {
                                    alert("Semua kategori bisnis telah dipilih.");
                                    return;
                                }

                                newFormGroup.appendChild(newSelect);
                                container.appendChild(newFormGroup);
                            });

                            removeButton.addEventListener("click", function () {
                                const selects = container.querySelectorAll(".form-group");
                                if (selects.length > 0) {
                                    container.removeChild(selects[selects.length - 1]);
                                }
                            });
                        });
                    </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
