<x-app-layout>
    <div class="page-container" style="max-width: 800px;">
        <div class="page-header animate-fade-in">
            <h1 class="page-title">{{ __('Profile Settings') }}</h1>
            <p class="page-subtitle">{{ __("Manage your account settings and preferences.") }}</p>
        </div>

        <div class="space-y-6">
            <div class="glass-card animate-fade-in-delay-1" style="padding: 2rem;">
                <div style="max-width: 600px;">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="glass-card animate-fade-in-delay-2" style="padding: 2rem;">
                <div style="max-width: 600px;">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="glass-card animate-fade-in-delay-3" style="padding: 2rem; border-color: rgba(239, 68, 68, 0.2);">
                <div style="max-width: 600px;">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
