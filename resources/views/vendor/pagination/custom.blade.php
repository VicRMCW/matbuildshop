@if ($paginator->hasPages())
    {{-- Pagination kustom MatbuildShop: tombol minimal 40x40px, warna aksen, dan teks bahasa Indonesia --}}
    <nav role="navigation" aria-label="Navigasi Halaman" style="display: flex; flex-direction: column; gap: 12px; margin-top: 24px;">
        <div style="font-size: 15px; color: #555555;">
            Menampilkan
            <span style="font-weight: 600; color: #1E1E1E;">{{ $paginator->firstItem() ?? 0 }}</span>
            sampai
            <span style="font-weight: 600; color: #1E1E1E;">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span style="font-weight: 600; color: #1E1E1E;">{{ $paginator->total() }}</span>
            data
        </div>

        <ul style="display: inline-flex; flex-wrap: wrap; gap: 6px; list-style: none; padding: 0; margin: 0; align-items: center;">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="Sebelumnya">
                    <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border: 1px solid #D6D3D1; border-radius: 6px; font-size: 14px; font-weight: 600; color: #9CA3AF; background: #F3F4F6; cursor: not-allowed;">
                        &laquo; Prev
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya"
                        style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border: 1px solid #D6D3D1; border-radius: 6px; font-size: 14px; font-weight: 600; color: #1E1E1E; background: #FFFFFF; text-decoration: none;">
                        &laquo; Prev
                    </a>
                </li>
            @endif

            {{-- Elemen Nomor Halaman --}}
            @foreach ($elements as $element)
                {{-- Pemisah Tiga Titik --}}
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; font-size: 14px; color: #777777;">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                {{-- Nomor Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border: 1px solid #C8601A; border-radius: 6px; font-size: 14px; font-weight: 700; color: #FFFFFF; background: #C8601A;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                    style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border: 1px solid #D6D3D1; border-radius: 6px; font-size: 14px; font-weight: 600; color: #1E1E1E; background: #FFFFFF; text-decoration: none;">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya"
                        style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border: 1px solid #D6D3D1; border-radius: 6px; font-size: 14px; font-weight: 600; color: #1E1E1E; background: #FFFFFF; text-decoration: none;">
                        Next &raquo;
                    </a>
                </li>
            @else
                <li aria-disabled="true" aria-label="Berikutnya">
                    <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border: 1px solid #D6D3D1; border-radius: 6px; font-size: 14px; font-weight: 600; color: #9CA3AF; background: #F3F4F6; cursor: not-allowed;">
                        Next &raquo;
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
