@section('title', 'Settings')
<x-erb-reviewer>
    <main class="xl:ml-[335px] max-xl:ml-auto p-2 2xl:p-4">
        <div class="py-8 max-md:m-3">
            <div class="max-w-full mx-auto sm:px-6 lg:px-4 space-y-6">
                <!-- Update Profile Information -->
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Change Password -->
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-erb-reviewer>