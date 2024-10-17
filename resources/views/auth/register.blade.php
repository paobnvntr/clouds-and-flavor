<x-guest-layout>
    @section('title', 'Register')
    
    <!-- Flash Message Area -->
    <div id="flash-message">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-medium text-gray-600">
            Create an Account
        </h2>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <x-input-label for="name">
                {{ __('Name') }} <span class="text-red-600">*</span>
            </x-input-label>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email">
                {{ __('Email') }} <span class="text-red-600">*</span>
            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password">
                {{ __('Password') }} <span class="text-red-600">*</span>
            </x-input-label>
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation">
                {{ __('Confirm Password') }} <span class="text-red-600">*</span>
            </x-input-label>
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Add 18+ confirmation checkbox -->
        <div class="mt-4">
            <label for="terms" class="flex items-center">
                <input id="terms" type="checkbox" name="terms" required>
                <span class="ml-2 text-sm text-gray-600">I confirm that I am 18 years old or older and agree to the <a
                        href="#termsModal" class="text-blue-600 hover:text-blue-800"
                        data-modal-toggle="termsModal">terms and conditions</a>.</span>
            </label>
        </div>

        <!-- Terms and Conditions Modal -->
        <div id="termsModal" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal h-full hidden">
            <div class="relative w-full max-w-2xl h-full md:h-auto">
                <!-- Modal content -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Terms and Conditions</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-hide="termsModal">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <div class="overflow-y-auto max-h-96 mt-4">
                        <p class="text-gray-600">
                            <strong>1. Age Requirement</strong><br>
                            By creating an account or placing an order on this website, you confirm that you are at
                            least 18 years of age or older. It is illegal for anyone under the age of 18 to purchase or
                            use vaping products...
                            <!-- Include the rest of your terms and conditions here -->
                            <br><br>

                            <strong>2. Product Usage</strong><br>
                            All products sold on this website are intended for adult use only and should not be used by:
                        <ul class="list-disc list-inside">
                            <li>Pregnant or breastfeeding women,</li>
                            <li>People with respiratory issues...</li>
                            <!-- Continue with the terms content -->
                        </ul>

                        <br>
                        <strong>3. Legal Compliance</strong><br>
                        You agree to comply with all local, state, and federal laws regarding the purchase and use of
                        vaping products in your location...

                        <br><br>
                        <strong>4. Privacy Policy</strong><br>
                        We respect your privacy and will only collect and use your personal data as described in our
                        Privacy Policy. By using our website and services, you agree to the collection, use, and sharing
                        of your personal information for the purposes of order processing and age verification.

                        <br><br>
                        <strong>5. Limitation of Liability</strong><br>
                        You agree to use our products at your own risk. We are not liable for any damages, injuries, or
                        health issues that result from the use or misuse of our products.
                        <br><br>
                        We make no warranties, express or implied, regarding the safety, durability, or performance of
                        our products beyond those required by law.

                        <br><br>
                        <strong>6. Health Disclaimer</strong><br>
                        The products sold on this site are not designed for smoking cessation and have not been
                        evaluated by the Food and Drug Administration (FDA) or any other health authority. The use of
                        vape products may carry health risks, especially for individuals with pre-existing medical
                        conditions.
                        <br><br>
                        If you experience any adverse reactions while using our products, please discontinue use and
                        consult a healthcare professional.


                        <br><br>
                        <p>By registering, you confirm that you are 18 years of age or older and accept the following
                            terms...</p>


                    </div>

                    <div class="flex justify-end pt-4 border-t mt-4">
                        <button type="button" class="text-blue-600 hover:text-blue-800 focus:outline-none"
                            data-modal-hide="termsModal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('termsModal');
            const openModalLink = document.querySelector('[data-modal-toggle="termsModal"]');
            const closeModalButtons = document.querySelectorAll('[data-modal-hide="termsModal"]');

            // Open modal when clicking on the terms link
            openModalLink.addEventListener('click', (event) => {
                event.preventDefault();
                modal.classList.remove('hidden');
            });

            // Close modal when clicking the close button or any element with data-modal-hide
            closeModalButtons.forEach(button => {
                button.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            });
        });
    </script>
</x-guest-layout>
