<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="mb-3 row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Current Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="current_password" value="{{ old('current_password') }}"
                    placeholder="enter your current password">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="inputPassword" class="col-sm-2 col-form-label">New Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password" placeholder="enter the new password"
                    value="{{ old('password') }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Confirm Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password_confirmation"
                    placeholder="re enter your new password" value="{{ old('password_confirmation') }}">
            </div>
        </div>

        <div class="flex items-center gap-4">
            @can('edit profile')
            <button type="submit" class="btn btn-success">Save</button>
            @endcan

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
