<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Media Partner Management</h1>

    <div class="flex justify-between mt-5">
        <button type="button" wire:click.prevent="showAddMediaPartner" wire:key="showAddMediaPartner"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-40 h-10">Add
            media partner</button>
    </div>

    
    @if (count($finalListOfMediaPartners) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no media partners yet.
        </div>
    @else
        
    @endif
</div>
