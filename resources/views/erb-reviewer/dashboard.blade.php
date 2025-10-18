@section('title', 'Dashboard')
<x-erb-reviewer>
    <!-- Main Content -->
    <main class="xl:ml-[335px] max-xl:ml-auto p-4 max-xl:p-2">
        <h2 class="max-xl:hidden text-left bg-[#f2f2f2] shadow-lg p-[35px] rounded-[30px] font-medium text-[28px]">
            DASHBOARD
        </h2>
        <br>
        <div class="p-6 max-md:p-0 space-y-10">
            <div class="rounded-md shadow-md overflow-hidden bg-white">
                <!-- Header bar -->
                <div class="bg-primary text-white font-semibold px-4 py-2">
                    Reminder
                </div>

                <!-- Body -->
                <div class="p-6 text-sm leading-relaxed">
                    <p class="mb-4">
                        sample text
                    </p>
                </div>
            </div>
            <div>
                <div class="grid max-md:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                    <div class="card bg-lightgray p-4 rounded-lg border border-gray shadow">
                        <h3 class="text-[25px] max-md:text-[22px] font-semibold">5</h3>
                        <p class="max-md:text-[13px]">APPROVAL OF ACCOUNTS</p>
                    </div>
                    <div class="card bg-lightgray p-4 rounded-lg border border-gray shadow">
                        <h3 class="text-[25px] max-md:text-[22px] font-semibold">2</h3>
                        <p class="max-md:text-[13px]">RESEARCH PROTOCOL</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 max-md:px-0 space-y-10">
            <div class="flex max-md:block gap-10 w-full">
                <!-- Notification Tab -->
                <main class="flex-1 py-4 max-md:px-0 max-md:py-2">
                    <div class="w-full mx-auto px-4 max-md:px-1 py-4 flex items-center justify-between">
                        <h1 class="text-2xl max-md:text-[20px] font-semibold text-gray-800">Notifications</h1>
                        <form id="markAllReadForm" action="{{ route('erb-reviewer.notification.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm max-md:text-xs text-blue hover:text-darkblue duration-200">Mark all as read</button>
                        </form>
                    </div>
                    <div class="w-full mx-auto px-4 max-md:px-0 py-2 max-md:py-0">
                        <div class="bg-white shadow-sm border-2 border-gray">
                            <!-- Scroll area -->
                            <ul class="h-[32rem] overflow-y-auto scrollbar divide-y divide-gray" id="notifications-list">
                                @forelse(auth()->user()->notifications->take(20) as $notification)
                                    <li class="p-4 flex gap-4 hover:bg-gray duration-200 cursor-pointer {{ $notification->read_at ? '' : 'bg-blue-50' }}"
                                        onclick="handleNotificationClick('{{ $notification->id }}', '{{ json_encode($notification->data) }}')">
                                        <div
                                            class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-4h2v2H9v-2zm0-8h2v6H9V6z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-800">
                                                {{ $notification->data['message'] ?? 'New notification' }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                        @if(!$notification->read_at)
                                            <span class="inline-flex w-3 h-3 rounded-full bg-blue self-center"></span>
                                        @endif
                                    </li>
                                @empty
                                    <li class="p-4 text-center text-gray-500">
                                        No notifications yet
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </main>
</x-erb-reviewer>

<script>
    // Handle notification click
    function handleNotificationClick(notificationId, notificationDataJson) {
        const notificationData = JSON.parse(notificationDataJson);
        
        // First mark as read
        markAsRead(notificationId);
        
        // Then handle the action
        if (notificationData.action_url) {
            // Check if the route exists and is valid
            if (isValidRoute(notificationData.action_url)) {
                window.location.href = notificationData.action_url;
            } else {
                console.warn('Invalid route:', notificationData.action_url);
                // Fallback to reviewer dashboard
                window.location.href = '/reviewer/dashboard';
            }
        } else {
            // Default action if no action_url specified
            window.location.href = '/reviewer/dashboard';
        }
    }

    // Mark notification as read
    function markAsRead(notificationId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/notifications/${notificationId}/mark-read`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }

    // Check if route is valid (basic check)
    function isValidRoute(url) {
        // Remove leading slash if present
        const path = url.replace(/^\//, '');
        
        // Check if it's a valid-looking path
        return path.length > 0 && !path.includes('..') && !path.includes('//');
    }

    // Add confirmation for mark all as read
    document.getElementById('markAllReadForm').addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to mark all notifications as read?')) {
            e.preventDefault();
        }
    });
</script>