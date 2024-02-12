{{-- Ensure jQuery is included in your layout --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Tab Buttons -->
                    <nav class="flex mb-5">
                        <button id="show-mints" class="tab-button mr-4 py-2 px-4 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-700">Mints</button>
                        <button id="show-redeems" class="tab-button mr-4 py-2 px-4 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-700">Redeems</button>
                        <button id="show-done" class="tab-button py-2 px-4 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-700">Done</button>
                    </nav>

                    <!-- Mints Section -->
                    <div id="mints-section" class="tab-content">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight">All Mints</h3>
                        <div class="mints-list mt-4">
                            @forelse ($mints as $mint)
                                <div class="mint bg-gray-50 p-4 rounded-lg shadow mb-4">
                                    <p>Email: {{ $mint->email }}</p>
                                    <p>Tracking Number: {{ $mint->tracking_number }}</p>
                                    <p>Transaction Hash: {{ $mint->tx_hash }}</p>
                                    <p>NFT ID: {{ $mint->nft_id }}</p>
                                    <!-- Add other mint details here -->
                                </div>
                            @empty
                                <p>No mints found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Redeems Section -->
                    <div id="redeems-section" class="tab-content" style="display: none;">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight">All Redeems</h3>
                        <div class="redeems-list mt-4">
                            @forelse ($redeems as $redeem)
                                <div class="redeem bg-gray-50 p-4 rounded-lg shadow mb-4">
                                    <p>Email: {{ $redeem->email }}</p>
                                    <p>Phone: {{ $redeem->phone }}</p>
                                    <p>Mailing Address: {{ $redeem->mailing_address_1 }} {{ $redeem->mailing_address_2 ? ', '.$redeem->mailing_address_2 : '' }} {{ $redeem->mailing_address_3 ? ', '.$redeem->mailing_address_3 : '' }}</p>
                                    <p>NFT ID: {{ $redeem->nft_id }}</p>
                                    <p>Redeemed: {{ $redeem->redeemed ? 'Yes' : 'No' }}</p>
                            
                                    <!-- Redeem Form -->
                                    @if (!$redeem->redeemed)
                                        <!-- Tracking Form -->
                                        <form action="{{ route('redeems.redeem', $redeem->nft_id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="shipping_number-{{ $redeem->id }}">Shipping Number</label>
                                                    <input type="text" class="form-control" id="shipping_number-{{ $redeem->id }}" name="shipping_number" required>
                                            </div>
                                            <button type="submit" class="btn btn-success mt-2">Mark as Redeemed</button>
                                        </form>
                                    
                                    @endif
                                    
                                       
                                    
                                </div>
                            @empty
                                <p>No redeems found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Done Section -->
                    <div id="done-section" class="tab-content" style="display: none;">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight">Completed Transactions</h3>
                        <div class="done-list mt-4">
                            @forelse ($dones as $done)
                                    <div class="done bg-gray-50 p-4 rounded-lg shadow mb-4">
                                        <p>ID: {{ $done->redeem_id }}</p>
                                        <p>Tracking Number: {{ $done->shipping_number }}</p>        
                                    </div>
                                @empty
                                    <p>No Dones found.</p>
                                @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.tab-button').click(function() {
                // Hide all sections
                $('.tab-content').hide();

                // Show the clicked section
                const target = $(this).attr('id').replace('show-', '');
                $('#' + target + '-section').show();
            });

            // Initially show the 'mints' section
            $('#mints-section').show();
        });
    </script>
</x-app-layout>
