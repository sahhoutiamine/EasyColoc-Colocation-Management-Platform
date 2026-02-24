<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900">Account Status</h3>
                    <div class="mt-4 space-y-2">
                        <div>
                            <span class="font-semibold">Role:</span>
                            <span class="ml-2 px-2 py-1 text-sm rounded {{ Auth::user()->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ Auth::user()->is_admin ? 'Admin' : 'Member' }}
                            </span>
                        </div>
                        <div>
                            <span class="font-semibold">Status:</span>
                            <span class="ml-2 px-2 py-1 text-sm rounded {{ Auth::user()->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ Auth::user()->is_banned ? 'Banned' : 'Active' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
