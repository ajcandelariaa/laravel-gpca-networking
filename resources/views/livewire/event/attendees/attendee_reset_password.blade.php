<div class="fixed z-10 inset-0 overflow-y-auto">
    <form>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="text-primaryColor italic font-bold text-xl">
                        Reset password
                    </div>
                    <div class="mt-5">
                        <div class="text-primaryColor">
                            New password <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <input placeholder="*******" type="password" wire:model.lazy="newPassword"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                            @error('newPassword')
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="text-primaryColor">
                            Confirm password <span class="text-red-500">*</span>
                        </div>
                        <div>
                            <input placeholder="*******" type="password" wire:model.lazy="confirmPassword"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                            @error('confirmPassword')
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                            @if ($passwordError != null)
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $passwordError }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click.prevent="resetPasswordAttendeeConfirmation">Reset</button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click.prevent="cancelResetPasswordAttendee">Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>
