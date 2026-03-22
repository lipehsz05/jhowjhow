<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('dev.index') }}">Painel DEV</a></li>
    @if(!empty($devCrumb))
        <li class="breadcrumb-item active">{{ $devCrumb }}</li>
    @endif
</ol>
