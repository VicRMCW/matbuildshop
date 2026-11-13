@if (session('status'))
    <div style="background: #E8F5E9; color: #1E4620; border: 1px solid #C8E6C9; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1.25rem; font-size: 0.9rem;">
        {{ session('status') }}
    </div>
@endif

@if (session('success'))
    <div style="background: #E8F5E9; color: #1E4620; border: 1px solid #C8E6C9; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1.25rem; font-size: 0.9rem;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1.25rem; font-size: 0.9rem;">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div style="background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; padding: 0.75rem 1rem; border-radius: 4px; margin-bottom: 1.25rem; font-size: 0.9rem;">
        <ul style="margin-left: 1.25rem; margin-bottom: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
