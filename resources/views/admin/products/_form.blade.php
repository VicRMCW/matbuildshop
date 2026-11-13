@csrf

{{-- Form Product: Mengadopsi kelas komponen rapi .field, .input, .form-row-2, upload-box & preview-item --}}
<div class="field">
    <label for="name">Nama Produk</label>
    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="150" placeholder="Contoh: Semen Portland 50 kg (per sak)" autofocus class="input">
    @error('name')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>

<div class="field">
    <label for="category_id">Kategori</label>
    <select id="category_id" name="category_id" required class="input">
        <option value="">Pilih Kategori</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>

<div class="form-row form-row-2">
    <div class="field">
        <label for="price">Harga (Rupiah)</label>
        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="1" step="1" required placeholder="Contoh: 68000" class="input">
        @error('price')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="stock">Jumlah Stok</label>
        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" step="1" required class="input">
        @error('stock')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="field-checkbox">
    <label for="is_active" class="checkbox-label">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} class="checkbox-input">
        <span>Status Produk Aktif (dapat dilihat dan dibeli pelanggan)</span>
    </label>
    @error('is_active')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>

<div class="field">
    <label for="description">Deskripsi Produk</label>
    <textarea id="description" name="description" rows="6" required placeholder="Deskripsikan spesifikasi teknis, dimensi, atau kegunaan barang bangunan..." class="input">{{ old('description', $product->description) }}</textarea>
    @error('description')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>

<div class="field">
    <label for="image">Foto Produk (Opsional: JPG, PNG, WEBP, Maks. 2MB)</label>
    <div class="upload-box">
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
    </div>
    @error('image')
        <div class="field-error">{{ $message }}</div>
    @enderror

    <div class="preview-container">
        @if(!empty($product->image))
            <div class="preview-item">
                <span>Foto Saat Ini</span>
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            </div>
        @endif

        <div id="preview-wrapper" class="preview-item" style="display: none;">
            <span>Pratinjau Baru</span>
            <img id="image-preview" src="#" alt="Pratinjau Baru">
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Simpan Produk</button>
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
