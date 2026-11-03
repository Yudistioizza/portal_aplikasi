@foreach($konten as $item)
<!-- Modal Edit -->
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Konten</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('list.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Aplikasi:</label>
                        <input type="text" name="nama" class="form-control" value="{{ $item->app_name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="website_type_id">Tipe Website</label>
                        <select name="website_type_id" class="form-control" required>
                            <option value="">-- Pilih Tipe Website --</option>
                            @foreach($websiteTypes as $type)
                                <option value="{{ $type->id }}" {{ $item->website_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="ownerContainerEdit{{ $item->id }}">
                        <label for="business_category_id">Pemilik Bisnis</label>
                        
                        <!-- First dropdown (always shown) -->
                        <div class="input-group">
                            <select name="business_category_id[]" class="form-control w-25 me-3 mb-2" required>
                                <option value="">-- Pilih Pemilik Bisnis --</option>
                                @foreach($businessCategories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ $item->businessCategories->contains('id', $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-group-btn mt-1">
                                <button type="button" class="btn btn-success" onclick="addOwnerField({{ $item->id }})">+</button>
                                <button type="button" class="btn btn-danger" onclick="removeOwnerField({{ $item->id }})">−</button>
                            </span>
                        </div>
                        
                        <!-- Additional dropdowns for existing selections -->
                        @php
                            $selectedBusinessCategories = $item->businessCategories;
                        @endphp
                        
                        @if($selectedBusinessCategories->count() > 1)
                            @foreach($selectedBusinessCategories->skip(1) as $selectedCategory)
                                <div class="form-group mt-2">
                                    <select name="business_category_id[]" class="form-control w-75" required>
                                        <option value="">-- Pilih Pemilik Bisnis --</option>
                                        @foreach($businessCategories as $category)
                                            <option value="{{ $category->id }}" {{ $selectedCategory->id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Deskripsi:</label>
                        <textarea name="deskripsi" class="form-control">{{ $item->app_desc }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Link Aplikasi:</label>
                        <input type="url" name="link_aplikasi" class="form-control" value="{{ $item->app_link }}">
                    </div>

                    <div class="form-group">
                        <label>Logo:</label>
                        <input type="file" name="logo" class="form-control" id="logoInputEdit{{ $item->id }}" onchange="previewLogoEdit(event, {{ $item->id }})">
                    </div>

                    <div class="form-group">
                        @if(!empty($item->app_logo))
                            <img id="logoPreviewEdit{{ $item->id }}" src="{{ $item->app_logo }}" alt="Preview Logo" class="img-thumbnail mt-2" style="max-width: 200px; height: auto;">
                        @else
                            <img id="logoPreviewEdit{{ $item->id }}" src="#" alt="Preview Logo" class="img-thumbnail mt-2" style="display: none; max-width: 200px; height: auto;">
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Store business categories data for this item -->
<script>
    // Store business categories for each item
    if (!window.businessCategoriesData) {
        window.businessCategoriesData = {};
    }
    window.businessCategoriesData[{{ $item->id }}] = @json($businessCategories);
    
    // Store already selected categories
    if (!window.selectedBusinessCategories) {
        window.selectedBusinessCategories = {};
    }
    window.selectedBusinessCategories[{{ $item->id }}] = @json($item->businessCategories->pluck('id'));
</script>
@endforeach

<!-- Global JavaScript functions -->
<script>
    // Preview logo function
    function previewLogoEdit(event, itemId) {
        const input = event.target;
        const preview = document.getElementById("logoPreviewEdit" + itemId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = "#";
            preview.style.display = "none";
        }
    }

    // Add owner field function
    function addOwnerField(itemId) {
        const container = document.getElementById("ownerContainerEdit" + itemId);
        const businessCategories = window.businessCategoriesData[itemId];
        
        // Get all currently selected values
        const selectedValues = Array.from(container.querySelectorAll("select"))
            .map(select => select.value);
        
        // Filter out already selected categories
        const availableCategories = businessCategories.filter(category => 
            !selectedValues.includes(category.id.toString()) && category.id.toString() !== "");
        
        if (availableCategories.length === 0) {
            alert("Semua kategori bisnis telah dipilih.");
            return;
        }
        
        // Create new dropdown
        const newFormGroup = document.createElement("div");
        newFormGroup.classList.add("form-group", "mt-2");
        
        const newSelect = document.createElement("select");
        newSelect.name = "business_category_id[]";
        newSelect.classList.add("form-control", "w-75");
        newSelect.required = true;
        
        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "-- Pilih Pemilik Bisnis --";
        newSelect.appendChild(defaultOption);
        
        availableCategories.forEach(category => {
            const option = document.createElement("option");
            option.value = category.id;
            option.textContent = category.name;
            newSelect.appendChild(option);
        });
        
        newFormGroup.appendChild(newSelect);
        container.appendChild(newFormGroup);
    }
    
    // Remove owner field function
    function removeOwnerField(itemId) {
        const container = document.getElementById("ownerContainerEdit" + itemId);
        const formGroups = container.querySelectorAll(".form-group");
        
        // Don't remove the first dropdown with the +/- buttons
        if (formGroups.length > 0) {
            container.removeChild(formGroups[formGroups.length - 1]);
        }
    }
</script>