@csrf

<div class="form-group">
    <label for="name">Nama Kategori</label>
    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="100" autofocus>
    @error('name')
        <div class="field-error" style="color: #B91C1C; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="description">Deskripsi (Opsional)</label>
    <textarea id="description" name="description" rows="3" maxlength="500">{{ old('description', $category->description) }}</textarea>
    @error('description')
        <div class="field-error" style="color: #B91C1C; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
    @enderror
</div>

<div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
    <button type="submit" class="btn">Simpan</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
</div>
