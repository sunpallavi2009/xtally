
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form id="otpForm" method="POST" action="{{ route('sendOtp') }}">
        @csrf
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone"
                class="block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                type="text" name="phone" required autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4" id="user-container">
            <x-input-label for="user" :value="__('User')" />
            <x-text-input id="user" class="block mt-1 w-full" type="text" name="user" :value="old('user')" required
                autocomplete="phone" readonly />
            <x-input-error :messages="$errors->get('user')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button  class="ms-3">
                {{ __('Send Otp') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.getElementById('phone');
            const userInput = document.getElementById('user');
            const users = @json($users);

            phoneInput.addEventListener('input', function () {
                const typedPhone = phoneInput.value;
                const matchedUser = users.find(user => user.phone.startsWith(typedPhone));
                userInput.value = matchedUser ? matchedUser.name : '';
            });

            document.getElementById('otpForm').addEventListener('submit', function() {
                document.getElementById('submitBtn').innerHTML = 'Sending...';
            });
        });
    </script>
</x-guest-layout>