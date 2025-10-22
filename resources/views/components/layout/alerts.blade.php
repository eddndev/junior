{{-- Flash Messages Component - Displays success, error, warning, and info messages --}}

@if(session('success'))
    <div class="mb-6">
        <x-feedback.alert type="success" :dismissible="true">
            {{ session('success') }}
        </x-feedback.alert>
    </div>
@endif

@if(session('error'))
    <div class="mb-6">
        <x-feedback.alert type="error" :dismissible="true">
            {{ session('error') }}
        </x-feedback.alert>
    </div>
@endif

@if(session('warning'))
    <div class="mb-6">
        <x-feedback.alert type="warning" :dismissible="true">
            {{ session('warning') }}
        </x-feedback.alert>
    </div>
@endif

@if(session('info'))
    <div class="mb-6">
        <x-feedback.alert type="info" :dismissible="true">
            {{ session('info') }}
        </x-feedback.alert>
    </div>
@endif
