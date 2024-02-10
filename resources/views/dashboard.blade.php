<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                <!-- -->
                <div class="mints-section">
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">All Mints</h3>
                    <div class="mints-list">
                        <br>
                        @foreach ($mints as $mint)
                            <div class="mint">
                            <p>Email: {{ $mint->email }}</p>
                            <p>Tracking Number: {{ $mint->tracking_number }}</p>
                            <p>Transaction Hash: {{ $mint->tx_hash }}</p>
                            <p>NFT ID: {{ $mint->nft_id }}</p>

                                <!-- Add other mint details here -->
                            </div>
                            <br>
                        @endforeach
                    </div>
                </div>
                <!-- -->


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
