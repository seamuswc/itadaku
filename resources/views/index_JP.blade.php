<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>あなたのウェブサイト</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/4.4.0/web3.min.js" integrity="sha512-9KPDCVlm3clvZUmFQLt9apyK2Z8PyDyaOVtaBiwefKt2S+UzLFMJ61PQ6AhWFrYkOe4tR0qdClm4xnA3L8brcw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <!--<link rel="stylesheet" href="{{ asset('css/styles.css') }}">-->
    <style>
        ul {
            list-style-type: none !important;  
        }

    </style>
</head>
<body>

        <div class="container has-text-centered">
            
            <div class="columns is-desktop">
                <div class="column">
                    <!-- This space is intentionally left blank for offset on larger screens -->
                </div>
                <div class="column responsive-content">
                    <div class="field">
                        <button class="button is-primary" id="connectWalletButton">Connect to MetaMask</button>
                        <div class="content mt-4">
                            Connected Network: <span id="networkConnected">Not connected</span><br>
                            Connected Address: <span id="walletAddress">Not connected</span>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Navigation Buttons -->
    <div class="buttons has-addons is-centered" style="margin-top: 20px;">
        <button onclick="location.href='#mint'" class="button custom-button">ミント</button>
        <button onclick="location.href='#redeem'" class="button custom-button">引き換える</button>
        <button onclick="location.href='#docs'" class="button custom-button">ドキュメント</button>
        <button onclick="switchLanguage()" class="button custom-button">English</button>
    </div>

    <!-- Mint Section -->
    <section id="mint" class="section">
        <div class="custom-container">
            <form class="form-centered" action="/mint" method="POST">
                @csrf  <!-- CSRF Token -->
                <div class="control">
                    <button type="submit" class="button custom-submit-button">ミント</button>
                </div>
                <br>
                <div class="field custom-field">
                    <input class="input" type="email" name="email" placeholder="メール">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="tracking_number" placeholder="追跡番号">
                </div>
                
                <div class="has-text-centered">
                    

                    @if($city)
                        @if($city == 'Tokyo')
                            <br>
                            <p>送付先住所:</p>
                            <p>Tokyo</p>
                            <p>Newtown, Imaginaria, 54321</p>
                            <br>
                        @else
                            <br>
                            <p>送付先住所:</p>
                            <p>FUKUPLA</p>
                            <p>Newtown, Imaginaria, 54321</p>
                            <br>
                        @endif
                    @else
                        <p>Service Temporarily Halted</p>
                    @endif

                </div>
                <input type="hidden" name="nft_id" id="nft_id">
                <input type="hidden" name="tx_hash" id="tx_hash">
            </form>
        </div>
    </section>

    <!-- Redeem Section -->
    <section id="redeem" class="section">
        <div class="custom-container">
            <form class="form-centered" action="/redeem" method="POST">
            @csrf  <!-- CSRF Token -->

                <div class="control">
                    <button type="submit" class="button custom-submit-button">引き換える</button>
                </div>
                <br>
                <div class="field custom-field">
                    <input class="input" type="number" name="nft_id" id = "nft_id_redeem" placeholder="NFTのID">
                </div>
                <div class="field custom-field">
                    <input class="input" type="email" name="email" placeholder="メール">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="phone" placeholder="電話">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="mailing_address_1" placeholder="郵送先住所">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="mailing_address_2" placeholder="追加の郵送先住所">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="mailing_address_3" placeholder="追加の郵送先住所">
                </div>

                <input type="hidden" name="tx_hash" id="tx_hash_redeem">

                
            </form>
        </div>
    </section>

    <!-- Docs Section -->
    <section id="docs" class="section">
        <div class="custom-container">
            <div class="content custom-content">
            <h1>Single Package or Letter Mail Storage and Redelivery For 1 Year</h1>

            <h2>Minting your NFT:</h2>
            <ul>
                <li>Costs $25 DAI to Mint a NFT representing your single package.</li>
                <li>After minting, you can mail us your package.</li>
            </ul>

   
    <h2>Package Mailing Rules:</h2>
    <ul>
        <li>Under 2 Kilos</li>
        <li>Dimensions: Size: Height + Width + Depth <= 200cm</li>
    </ul>

    <p>After minting your NFT, you have 375 days (1 Year plus 10 days) to redeem your package.</p>

    <h2>Redeeming Your NFT:</h2>
    <ul>
        <li>Address must be in Japan</li>
        <li>Phone number must be Japanese</li>
        <li>Will be mailed with Japan Post</li>
    </ul>
<br>
    <p>Please keep your transaction hashes for customer service.</p>
    <p>Email: <a href="mailto:jamesthaiphone@gmail.com">jamesthaiphone@gmail.com</a></p>
    <p><b>All packages are subject to Japan law.</b></p>
            </div>
        </div>
    </section>

    <script>
        function switchLanguage() {
            window.location.href = 'http://itadaku.xyz/en';
        }
    </script>


    @vite('resources/js/app.js')

</body>
</html>
