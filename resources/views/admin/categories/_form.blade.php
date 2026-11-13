@csrf

{{-- Form Category: Tinggi input min 44px (h-11), font 16px (text-base), dan jarak antar elemen minimal 20px (space-y-5) sesuai R-03 --}}
<div class="space-y-5">
    <div>
        <label for="name" class="block text-sm font-medium text-neutral-800 mb-1.5">Nama Kategori</label>
        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="100" autofocus
            class="w-full h-11 px-4 text-base rounded border border-neutral-300 bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-[#C8601A] focus:border-[#C8601A] transition-colors">
        @error('name')
            <div class="text-sm text-red-600 mt-1.5">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-neutral-800 mb-1.5">Deskripsi (Opsional)</label>
        <textarea id="description" name="description" rows="6" maxlength="500"
            class="w-full p-4 text-base rounded border border-neutral-300 bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-[#C8601A] focus:border-[#C8601A] transition-colors resize-y">{{ old('description', $category->description) }}</textarea>
        @error('description')
            <div class="text-sm text-red-600 mt-1.5">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="flex items-center gap-3 pt-6 border-t border-neutral-200 mt-6">
    <button type="submit" class="inline-flex items-center justify-center h-11 px-6 text-base font-medium rounded text-white bg-[#C8601A] hover:bg-[#A64E13] transition-colors">
        Simpan
    </button>
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center h-11 px-6 text-base font-medium rounded text-neutral-700 bg-transparent border border-neutral-300 hover:bg-neutral-100 transition-colors">
        Batal
    </a>
</div>
