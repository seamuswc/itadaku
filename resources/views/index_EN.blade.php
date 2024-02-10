<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.3.4/dist/web3.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
</head>
<body>

    <!-- Navigation Buttons -->
    <div class="buttons has-addons is-centered" style="margin-top: 20px;">
        <button onclick="location.href='#mint'" class="button custom-button">Mint</button>
        <button onclick="location.href='#redeem'" class="button custom-button">Redeem</button>
        <button onclick="location.href='#docs'" class="button custom-button">Docs</button>
        <button onclick="switchLanguage()" class="button custom-button">日本語</button>
    </div>

    <!-- Mint Section -->
    <section id="mint" class="section">
        <div class="custom-container">
            
            <form class="form-centered" action="/mint" method="POST">
            @csrf  <!-- CSRF Token -->
                <div class="control">
                    <button type="submit" class="button custom-submit-button">Mint</button>
                </div>
                <br>

                <div class="field custom-field">
                    <input class="input" type="email" name="email" placeholder="Email">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="tracking_number" placeholder="Tracking Number">
                </div>
                

                <div class="has-text-centered">
                    <br>
                    <p>Mail To Address:</p>
                    <p>1234 Fancy Street, Suite 100</p>
                    <p>Newtown, Imaginaria, 54321</p>
                    <br>
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
            <div class="control">
                    <button type="submit" class="button custom-submit-button">Redeem</button>
                </div>
                <br>

                <div class="field custom-field">
                    <input class="input" type="email" name="email" placeholder="Email">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="phone" placeholder="Phone">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="mailing_address_1" placeholder="Mailing Address">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="mailing_address_2" placeholder="Additional Mailing Address">
                </div>
                <div class="field custom-field">
                    <input class="input" type="text" name="mailing_address_3" placeholder="Additional Mailing Address">
                </div>
                

                
            </form>
        </div>
    </section>

<!-- Docs Section -->
<section id="docs" class="section">
    <div class="custom-container">
        <div class="content custom-content">
            <h2 class="custom-title">Documentation</h2>
            <p>Welcome to our site! Here you can find everything you need to know about using our platform. Whether you're looking to mint or redeem, we've got you covered.</p>
            <p>Our platform offers a variety of features including, but not limited to, minting digital assets, redeeming offers, and managing your portfolio with ease.</p>
            <p>We are committed to providing a seamless experience for our users. If you have any questions or need assistance, our support team is always here to help.</p>
        </div>
    </div>
</section>

<script>
    function switchLanguage() {
        // Replace 'http://example.jp' with the actual URL for your Japanese site
        window.location.href = 'http://itadaku.xyz';
    }
</script>

<!-- Add the path to your validation JavaScript file here -->
@vite('resources/js/app.js')

</body>
</html>