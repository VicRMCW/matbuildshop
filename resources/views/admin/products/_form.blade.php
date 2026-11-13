@csrf

{{-- Form Product: Mengadopsi ukuran lega (min 44px tap target) dan split responsive 2 kolom di lg sesuai DESIGN.md --}}
<div class="space-y-5">
    <div>
        <label for="name" class="block text-sm font-medium text-neutral-800 mb-1.5">Nama Produk</label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="150" placeholder="Contoh: Semen Portland 50 kg (per sak)" autofocus
            class="w-full h-11 px-4 text-base rounded border border-neutral-300 bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-[#C8601A] focus:border-[#C8601A] transition-colors">
        @error('name')
            <div class="text-sm text-red-600 mt-1.5">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-neutral-800 mb-1.5">Kategori</label>
        <select id="category_id" name="category_id" required
            class="w-full h-11 px-4 text-base rounded border border-neutral-300 bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-[#C8601A] focus:border-[#C8601A] transition-colors">
            <option value="">Pilih Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="text-sm text-red-600 mt-1.5">{{ $message }}</div>
        @enderror
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div>
            <label for="price" class="block text-sm font-medium text-neutral-800 mb-1.5">Harga (Rupiah)</label>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="1" step="1" required placeholder="Contoh: 68000"
                class="w-full h-11 px-4 text-base rounded border border-neutral-300 bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-[#C8601A] focus:border-[#C8601A] transition-colors">
            @error('price')
                <div class="text-sm text-red-600 mt-1.5">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="stock" class="block text-sm font-medium text-neutral-800 mb-1.5">Jumlah Stok</label>
            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" step="1" required
                class="w-full h-11 px-4 text-base rounded border border-neutral-300 bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-[#C8601A] focus:border-[#C8601A] transition-colors">
            @error('stock')
                <div class="text-sm text-red-600 mt-1.5">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="pt-1">
        <label for="is_active" class="inline-flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                class="w-5 h-5 rounded border-neutral-300 text-[#C8601A] focus:ring-[#C8601A] cursor-pointer">
            <span class="text-sm font-medium text-neutral-800">Status Produk Aktif (dapat dilihat dan dibeli pelanggan)</span>
        </label>
        @error('is_active')
            <div class="text-sm text-red-600 mt-1.5">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-neutral-800 mb-1.5">Deskripsi Produk</label>
        <textarea id="description" name="description" rows="6" required placeholder="Deskripsikan spesifikasi teknis, dimensi, atau kegunaan barang bangunan..."
            class="w-full p-4 text-base rounded border border-neutral-300 bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-[#C8601A] focus:border-[#C8601A] transition-colors resize-y">{{ old('description', $product->description) }}</textarea>
        @error('description')
            <div class="text-sm text-red-600 mt-1.5">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="image" class="block text-sm font-medium text-neutral-800 mb-1.5">Foto Produk (Opsional: JPG, PNG, WEBP, Maks. 2MB)</label>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
            class="block w-full text-base text-neutral-600 file:mr-4 file:py-2.5 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-neutral-100 file:text-neutral-800 hover:file:bg-neutral-200 file:cursor-pointer border border-neutral-300 rounded cursor-pointer bg-white transition-colors">
        @error('image')
            <div class="text-sm text-red-600 mt-1.5">{{ $message }}</div>
        @enderror

        <div class="flex flex-wrap gap-6 mt-4 items-start">
            @if(!empty($product->image))
                <div>
                    <span class="block text-xs font-medium uppercase tracking-wider text-neutral-500 mb-2">Foto Saat Ini</span>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="w-40 h-40 object-cover rounded border border-neutral-300 bg-white shadow-xs">
                </div>
            @endif

            <div id="preview-wrapper" style="display: none;">
                <span class="block text-xs font-medium uppercase tracking-wider text-neutral-500 mb-2">Pratinjau Baru</span>
                <img id="image-preview" src="#" alt="Pratinjau Baru"
                    class="w-40 h-40 object-cover rounded border border-neutral-300 bg-white shadow-xs">
            </div>
        </div>
    </div>
</div>

<div class="flex items-center gap-3 pt-6 border-t border-neutral-200 mt-6">
    <button type="submit" class="inline-flex items-center justify-center h-11 px-6 text-base font-medium rounded text-white bg-[#C8601A] hover:bg-[#A64E13] transition-colors">
        Simpan Produk
    </button>
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center h-11 px-6 text-base font-medium rounded text-neutral-700 bg-transparent border border-neutral-300 hover:bg-neutral-100 transition-colors">
        Batal
    </a>
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
