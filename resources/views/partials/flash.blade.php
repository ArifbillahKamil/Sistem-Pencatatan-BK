{{-- Flash Messages --}}

@if(session('success'))
<div id="flash-success"
     class="flex items-start gap-3 p-4 mb-4 bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm">
    <div class="flex-shrink-0 w-5 h-5 text-emerald-500 mt-0.5">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
    </div>
    <button onclick="this.closest('#flash-success').remove()"
            class="flex-shrink-0 text-emerald-400 hover:text-emerald-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
@endif

@if(session('error'))
<div id="flash-error"
     class="flex items-start gap-3 p-4 mb-4 bg-red-50 border border-red-200 rounded-xl shadow-sm">
    <div class="flex-shrink-0 w-5 h-5 text-red-500 mt-0.5">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
    </div>
    <button onclick="this.closest('#flash-error').remove()"
            class="flex-shrink-0 text-red-400 hover:text-red-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
@endif

@if(session('warning'))
<div id="flash-warning"
     class="flex items-start gap-3 p-4 mb-4 bg-amber-50 border border-amber-200 rounded-xl shadow-sm">
    <div class="flex-shrink-0 w-5 h-5 text-amber-500 mt-0.5">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-amber-800">{{ session('warning') }}</p>
    </div>
    <button onclick="this.closest('#flash-warning').remove()"
            class="flex-shrink-0 text-amber-400 hover:text-amber-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
@endif

{{-- Auto-dismiss after 5 seconds --}}
<script>
    setTimeout(function () {
        ['flash-success','flash-error','flash-warning'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) el.style.transition = 'opacity 0.4s';
            if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }
        });
    }, 5000);
</script>
