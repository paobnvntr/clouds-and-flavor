<x-guest-layout>
    @section('title', 'Register')

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
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email">
                {{ __('Email') }} <span class="text-red-600">*</span>
            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="address">
                {{ __('Address') }} <span class="text-red-600">*</span>
            </x-input-label>
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')"
                required autocomplete="address" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone_number">
                {{ __('Phone Number') }} (09XXXXXXXXX) <span class="text-red-600">*</span>
            </x-input-label>
            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number"
                :value="old('phone_number')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
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
                <span class="ml-2 text-sm text-gray-600">I confirm that I am 18 years old or older and agree to the
                    <button type="button" class="text-primary hover:text-blue-800 focus:outline-none"
                        data-bs-toggle="modal" data-bs-target="#termsModal">
                        Terms and Conditions
                    </button>.</span>
            </label>
        </div>

        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <!-- Modal header with better spacing and button styling -->
                    <div class="modal-header bg-gray-100 p-4">
                        <h5 class="modal-title text-lg font-semibold text-gray-800" id="termsModalLabel">Terms and
                            Conditions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal body with enhanced padding and spacing -->
                    <div class="modal-body p-4" style="max-height: 400px; overflow-y: auto;">
                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">1. Age Requirement</strong><br>
                            By creating an account, purchasing, or using products on this website, you confirm that you
                            are at least 18 years old (or older if required by your local laws). We reserve the right to
                            request proof of age at any time. If it is discovered that you are underage, your order will
                            be canceled immediately.
                        </p>
                        <hr class="my-4">

                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">2. Product Usage</strong><br>
                            All products sold on this website are intended for adult use only and should not be used by:
                        </p>
                        <ul class="list-disc ml-6 text-sm text-gray-700">
                            <li>Pregnant or breastfeeding women,</li>
                            <li>Individuals with existing respiratory conditions such as asthma,</li>
                            <li>People with heart disease, high blood pressure, or diabetes,</li>
                            <li>Non-smokers or non-vapers,</li>
                            <li>Anyone sensitive to nicotine or other ingredients used in e-liquids.</li>
                        </ul>
                        <hr class="my-4">

                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">3. Nicotine Warning</strong><br>
                            Nicotine is a highly addictive substance. By purchasing our products, you acknowledge the
                            health risks associated with nicotine consumption. You should not use vaping products if you
                            are allergic to nicotine, propylene glycol, or any of the other ingredients in e-liquids.
                            Always keep e-liquids and devices out of reach of children and pets.
                        </p>
                        <hr class="my-4">

                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">4. Health Disclaimer</strong><br>
                            Our products are not intended for smoking cessation or to treat any medical condition.
                            Consult your physician before using vaping products if you have any underlying health
                            issues. Vaping products have not been evaluated by the Food and Drug Administration (FDA),
                            and long-term effects of vaping are not fully known.
                        </p>
                        <hr class="my-4">

                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">5. Legal Compliance</strong><br>
                            You agree to comply with all local, state, and federal regulations regarding the purchase,
                            possession, and use of vaping products in your jurisdiction. It is your responsibility to be
                            aware of and follow the laws that apply to you. We are not liable for any legal consequences
                            arising from the misuse of our products or failure to comply with applicable laws.
                        </p>
                        <hr class="my-4">

                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">6. Returns and Refunds</strong><br>
                            Due to the nature of vaping products, we only accept returns for defective items within 30
                            days of purchase. E-liquids, opened or used products, and clearance items are
                            non-returnable. If you receive a defective item, please contact us immediately with proof of
                            purchase to arrange for a replacement or refund.
                        </p>
                        <hr class="my-4">

                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">7. Shipping and Delivery</strong><br>
                            We ship to selected locations based on the laws governing vaping products. All orders are
                            processed within 1-3 business days, and delivery times vary by location. Once an order has
                            shipped, we are not responsible for delays caused by shipping carriers. Shipping fees are
                            non-refundable.
                        </p>
                        <hr class="my-4">

                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">8. Limitation of Liability</strong><br>
                            By using our website and purchasing our products, you agree to use them at your own risk. We
                            are not responsible for any harm, injury, illness, or damage caused by improper use,
                            accidental ingestion, or product misuse. We are not liable for any consequential,
                            incidental, or punitive damages arising from the use of our products.
                        </p>
                        <hr class="my-4">

                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">9. Privacy Policy</strong><br>
                            We respect your privacy and are committed to protecting your personal information. Any
                            information collected on this website will be used solely for processing your orders,
                            improving our services, and marketing purposes (if consented to). We will never sell or
                            share your personal data with third parties without your consent. For more details, please
                            refer to our full Privacy Policy.
                        </p>
                        <hr class="my-4">

                        <p class="text-sm text-gray-700">
                            <strong class="text-lg text-gray-800">10. Changes to Terms</strong><br>
                            We reserve the right to update or modify these terms at any time without prior notice. Any
                            changes will take effect immediately upon posting to our website. It is your responsibility
                            to review the terms regularly. By continuing to use our website and products after any
                            changes, you agree to be bound by the updated terms.
                        </p>
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

    <style>
        .modal-header,
        .modal-footer {
            border: none;
            /* Remove default modal borders */
        }

        .modal-title {
            font-size: 1.25rem;
            /* Increase the title size */
        }

        .modal-body {
            font-size: 0.875rem;
            /* Slightly smaller body text for readability */
        }

        .modal-body strong {
            font-size: 1.125rem;
            /* Increase size of section titles */
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }
    </style>

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