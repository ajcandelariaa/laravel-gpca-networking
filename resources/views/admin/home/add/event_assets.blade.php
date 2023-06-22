<div class="mt-20 grid grid-cols-3 gap-y-5 gap-x-5 items-start">
    
    <div class="text-primaryColor font-medium text-xl col-span-3">
        Event assets
    </div>

    <div class="space-y-2">
        <div class="text-primaryColor">
            Event Logo <span class="text-red-500">*</span>
        </div>

        <div class="flex gap-3 flex-col mt-2">
            <div>
                <input type="file" accept="image/*" name="event_logo" onchange="previewEventLogo(event)"
                    class="w-full border-2 focus:border-primaryColor rounded-md px-2 text-sm focus:outline-none text-gray-700">

                @error('event_logo')
                    <div class="text-red-500 text-xs italic mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="self-center">
                <img src="https://via.placeholder.com/150" alt="logo" class="h-36 object-cover" id="imgEventLogo">
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <div class="text-primaryColor">
            Event Logo Inverted <span class="text-red-500">*</span>
        </div>

        <div class="flex gap-3 flex-col mt-2">
            <div>
                <input type="file" accept="image/*" name="event_logo_inverted" onchange="previewEventLogoInverted(event)"
                    class="w-full border-2 focus:border-primaryColor rounded-md px-2 text-sm focus:outline-none text-gray-700">

                @error('event_logo_inverted')
                    <div class="text-red-500 text-xs italic mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="self-center">
                <img src="https://via.placeholder.com/150" alt="logo" class="h-36 object-cover" id="imgEventLogoInverted">
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <div class="text-primaryColor">
            App Sponsor Logo<span class="text-red-500">*</span>
        </div>

        <div class="flex gap-3 flex-col mt-2">
            <div>
                <input type="file" accept="image/*" name="app_sponsor_logo" onchange="previewAppSponsorLogo(event)"
                    class="w-full border-2 focus:border-primaryColor rounded-md px-2 text-sm focus:outline-none text-gray-700">

                @error('app_sponsor_logo')
                    <div class="text-red-500 text-xs italic mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="self-center">
                <img src="https://via.placeholder.com/150" alt="logo" class="h-36 object-cover" id="imgAppSponsorLogo">
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <div class="text-primaryColor">
            Event Splash screen <span class="text-red-500">*</span>
        </div>
        <div class="flex gap-3 flex-col mt-2">
            <div>
                <input type="file" accept="image/*" name="event_splash_screen" onchange="previewEventSplashScreen(event)"
                    class="w-full border-2 focus:border-primaryColor rounded-md px-2 text-sm focus:outline-none text-gray-700">

                @error('event_splash_screen')
                    <div class="text-red-500 text-xs italic mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="self-center">
                <img src="http://via.placeholder.com/360x640" alt="banner" class="w-44 object-cover" id="imgEventSplashScreen">
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <div class="text-primaryColor">
            Event Banner <span class="text-red-500">*</span>
        </div>
        <div class="flex gap-3 flex-col mt-2">
            <div>
                <input type="file" accept="image/*" name="event_banner" onchange="previewEventBanner(event)"
                    class="w-full border-2 focus:border-primaryColor rounded-md px-2 text-sm focus:outline-none text-gray-700">

                @error('event_banner')
                    <div class="text-red-500 text-xs italic mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="self-center">
                <img src="http://via.placeholder.com/640x360" alt="banner" class="h-36 object-cover" id="imgEventBanner">
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <div class="text-primaryColor">
            App Sponsor Banner <span class="text-red-500">*</span>
        </div>
        <div class="flex gap-3 flex-col mt-2">
            <div>
                <input type="file" accept="image/*" name="app_sponsor_banner" onchange="previewAppSponsorBanner(event)"
                    class="w-full border-2 focus:border-primaryColor rounded-md px-2 text-sm focus:outline-none text-gray-700">

                @error('app_sponsor_banner')
                    <div class="text-red-500 text-xs italic mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="self-center">
                <img src="http://via.placeholder.com/640x360" alt="banner" class="h-36 object-cover" id="imgAppSponsorBanner">
            </div>
        </div>
    </div>
</div>
