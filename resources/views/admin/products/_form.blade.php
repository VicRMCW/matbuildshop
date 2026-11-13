@csrf

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
    <div class="form-group" style="grid-column: 1 / -1;">
        <label for="name">Nama Produk</label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="150" placeholder="Contoh: Semen Portland 50 kg (per sak)" autofocus>
        @error('name')
            <div class="field-error" style="color: #B91C1C; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="category_id">Kategori</label>
        <select id="category_id" name="category_id" required style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid var(--border-color); border-radius: 4px; font-size: 0.95rem; background: #FFF;">
            <option value="">Pilih Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="field-error" style="color: #B91C1C; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="price">Harga (Rupiah)</label>
        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="1" step="1" required placeholder="Contoh: 68000">
        @error('price')
            <div class="field-error" style="color: #B91C1C; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="stock">Jumlah Stok</label>
        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" step="1" required>
        @error('stock')
            <div class="field-error" style="color: #B91C1C; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1.75rem;">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} style="width: auto;">
        <label for="is_active" style="margin-bottom: 0; font-weight: normal; cursor: pointer;">Status Produk Aktif</label>
        @error('is_active')
            <div class="field-error" style="color: #B91C1C; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group" style="grid-column: 1 / -1;">
        <label for="description">Deskripsi Produk</label>
        <textarea id="description" name="description" rows="4" required placeholder="Deskripsikan spesifikasi, ukuran, dan kegunaan material...">{{ old('description', $product->description) }}</textarea>
        @error('description')
            <div class="field-error" style="color: #B91C1C; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group" style="grid-column: 1 / -1;">
        <label for="image">Gambar Produk (Opsional: JPG, PNG, WEBP, Maks. 2MB)</label>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
        @error('image')
            <div class="field-error" style="color: #B91C1C; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
        @enderror

        <div style="display: flex; gap: 1.5rem; margin-top: 1rem; align-items: flex-start;">
            @if(!empty($product->image))
                <div>
                    <span style="font-size: 0.8rem; color: #666; display: block; margin-bottom: 0.35rem;">Gambar Saat Ini:</span>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 90px; height: 90px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
                </div>
            @endif

            <div id="preview-wrapper" style="display: none;">
                <span style="font-size: 0.8rem; color: #666; display: block; margin-bottom: 0.35rem;">Pratinjau Gambar Baru:</span>
                <img id="image-preview" src="#" alt="Pratinjau Baru" style="width: 90px; height: 90px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
            </div>
        </div>
    </div>
</div>

<div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
    <button type="submit" class="btn">Simpan Produk</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
</div>

<script>
    document.getElementById('image')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('image-preview');
        const wrapper = document.getElementById('preview-wrapper');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                preview.src = evt.target.result;
                wrapper.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            wrapper.style.display = 'none';
        }
    });
</script>
