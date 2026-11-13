@if (session('status'))
    <div class="flash-alert flash-alert-success">
        {{ session('status') }}
    </div>
@endif

@if (session('success'))
    <div class="flash-alert flash-alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="flash-alert flash-alert-error">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="flash-alert flash-alert-error">
        <ul style="margin-left: 20px; margin-bottom: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
