@section('title', 'Submitted Documents')
<x-erb-layout>
    <main class="xl:ml-[335px] max-xl:ml-auto p-4 max-md:p-2">
        <h2 class="max-xl:hidden text-left bg-[#f2f2f2] shadow-lg p-[35px] rounded-[30px] font-medium text-[28px]">
            SUBMITTED DOCUMENTS
        </h2>
        <div class="w-full mx-auto my-8 px-4 py-6 bg-white rounded-lg border-2 border-gray">
            <h1 class="text-primary text-2xl max-md:text-base font-semibold mb-4">Submission Details</h1>
            <h2 class="text-primary text-xl max-md:text-base font-medium">
                User: <span class="text-blue-600">{{ $piFiles->user_Fname }}</span>
            </h2>

            <div class="max-sm:text-sm mt-6 space-y-4 h-64 overflow-y-auto">
                @forelse($piFiles->researchFiles as $file)
                    <div class="p-3 border border-darkgray bg-lightgray flex justify-between items-center rounded-lg" data-file-id="{{ $file->id }}">
                        <div>
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="block hover:bg-gray-50 p-2 rounded">
                                <h3 class="font-medium text-lg">Form: {{ $file->form?->form_name ?? 'N/A' }}</h3>
                                <p class="text-gray-700">Document: {{ $file->file_name }}</p>
                                <p class="text-sm">Submitted: {{ $file->submitted_at ?? 'N/A' }}</p>
                            </a>
                        </div>
                        <div class="right">
                            @if($file->status === 'active')
                            <form method="POST" action="{{ route('research-files.soft-delete', $file->id) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this document?')" 
                                        class="bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded duration-200 transition-colors">
                                    <i class="bi bi-trash3-fill text-xl max-sm:text-sm"></i>
                                </button>
                            </form>
                            @else
                            <span class="bg-gray-400 text-white py-2 px-3 rounded cursor-not-allowed">
                                <i class="bi bi-trash3-fill text-xl max-sm:text-sm"></i>
                            </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-lg">No submitted documents found.</p>
                        <p class="text-gray-400 text-sm mt-2">All documents will appear here once submitted.</p>
                    </div>
                @endforelse

                <!-- HARDCODED LAYOUT EXAMPLE (NO BACKEND FOR REFERENCE) -->
                <!--
                <div class="p-3 border border-darkgray bg-lightgray flex justify-between items-center rounded-lg">
                    <div>
                        <a href="#">
                            <h3 class="font-medium text-lg text-primary">Form: FORM3A</h3>
                            <p class="text-gray-700">Document: FORM3A.pdf</p>
                            <p class="text-gray-500 text-sm">Submitted: 10-21-25</p>
                            <p class="text-sm mt-1">Status: 
                                <span class="text-green-600 font-medium">Active</span>
                            </p>
                        </a>
                    </div>
                    <div class="right">
                        <form method="POST" action="#" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this document?')" 
                                    class="bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded duration-200">
                                <i class="bi bi-trash3-fill text-xl max-sm:text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
                -->
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ url('/erb/research-records') }}"
                class="bg-secondary hover:bg-primary text-lg max-xl:text-base text-primary hover:text-secondary uppercase tracking-widest px-4 py-2 rounded-md duration-200 transition-colors">
                Back
            </a>
        </div>
    </main>

    <!-- Success/Error Message Toast -->
    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300" id="toast">
            <div class="flex items-center">
                <i class="bi bi-check-circle-fill mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300" id="toast">
            <div class="flex items-center">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
</x-erb-layout>

<script>
    // Auto-hide toast messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    });

    // Optional: Enhanced delete with loading state (if you prefer AJAX approach)
    function enhancedSoftDelete(form) {
        if (!confirm('Are you sure you want to delete this document?')) {
            return false;
        }

        const button = form.querySelector('button[type="submit"]');
        const originalHTML = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="bi bi-hourglass-split text-xl max-sm:text-sm"></i>';
        button.disabled = true;
        button.classList.add('opacity-50');

        // Optional: You can keep the form submission as is, or use fetch for AJAX
        return true; // Allow form submission
    }
</script>

<style>
    .transition-colors {
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    
    .cursor-not-allowed {
        cursor: not-allowed;
    }
    
    .opacity-50 {
        opacity: 0.5;
    }
</style>