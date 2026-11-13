@csrf

{{-- Form Category: Memakai kelas komponen terstandarisasi .field dan .input dengan padding & margin proporsional --}}
<div class="field">
    <label for="name">Nama Kategori</label>
    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="100" autofocus class="input">
    @error('name')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>

<div class="field">
    <label for="description">Deskripsi (Opsional)</label>
    <textarea id="description" name="description" rows="6" maxlength="500" class="input">{{ old('description', $category->description) }}</textarea>
    @error('description')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
</div>
