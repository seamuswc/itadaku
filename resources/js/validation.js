import {connectWallet, approveDAI, mintNFT, getDAIAllowance, web3, mintEvent, pullNFT, redeemDAI } from "./eth_code";

// Function to validate an email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Function to validate a Japanese phone number
function validateJapanesePhoneNumber(phoneNumber) {
    /*
    // Matches Japanese phone numbers, allowing for country code, and various formats
    const re = /^(?:\+81|0)\d{1,4}-?\d{1,4}-?\d{4}$/;
    return re.test(phoneNumber);
    */

    const regex = /^[^a-zA-Z]*$/; // Matches a string with no alphabetic characters
    return regex.test(phoneNumber);
}

// Function to validate if a value is an integer
function isInteger(value) {
    const re = /^\d+$/; // Regular expression to check if string consists only of digits (integer)
    return re.test(value);
}

// Function to validate a form
function validateMint(form, namedInputs) {
    let isValid = true;

    namedInputs.forEach(function(name) {
        const input = form.find(`input[name="${name}"]`);
        if (!input.length) {
            console.error("input not found: ", name);
            return;
        }

        const isEmpty = input.val().trim() === '';
        const isInvalidEmail = input.attr('type') === 'email' && !validateEmail(input.val());

        if ((input.attr('required') || isEmpty) ||
            (input.attr('type') === 'email' && !isEmpty && isInvalidEmail)) {
            isValid = false;
            input.addClass('is-danger');
        } else {
            input.removeClass('is-danger');
        }
    });

    return isValid;
}

// Function to validate a form with all fields required
function validateRedeem(form) {
    let isValid = true;

    $(form).find('input').each(function() {
        const input = $(this);
        const value = input.val().trim();
        let isInvalid = false;

        // Check if the field is empty
        if (value === '') {
            isInvalid = true;
        } else if (input.attr('type') === 'email' && !validateEmail(value)) {
            // Email validation
            isInvalid = true;
        } else if (input.attr('name') === 'phone' && !validateJapanesePhoneNumber(value)) {
            // Japanese phone number validation
            isInvalid = true;
        } else if (input.attr('name') === 'nft_id' && !isInteger(value)) {
            // Integer validation for nft_id
            isInvalid = true;
        }

        // Add or remove validation feedback
        if (isInvalid) {
            isValid = false;
            input.addClass('is-danger');
        } else {
            input.removeClass('is-danger');
        }
    });

    return isValid;
}


$(document).ready(function() {

    $('#connectWalletButton').on('click', function() {
        connectWallet();
    });

    const mintForm = $('#mint form');
    const redeemForm = $('#redeem form');

    if (mintForm.length > 0) {
        mintForm.on('submit', async function(event) {
            event.preventDefault();

            let namedInputsToValidate = ['email', 'tracking_number']; 
            if (!validateMint(mintForm, namedInputsToValidate)) {
                return; // Form is not valid, stop here
            }

            //connect to MetaMask
            const walletConnected = await connectWallet();
            if(!walletConnected) {
                return;
            }

            let NFT_COST = '1'; //for allowance only so it can stay front end
            let cost= web3.utils.toWei(NFT_COST, 'ether');
            let allowance = await getDAIAllowance();
            console.log("Allowance:", allowance.toString());
            
            // Then, the user can mint the NFT
            if(Number(allowance) < Number(cost)) {
                 // First, the user approves the DAI transfer
                await approveDAI(cost);
            }

           let tx_hash = await mintNFT();
           console.log(tx_hash);

            //get past `Transfer` events from block 18850576
            let nft_id = await mintEvent();
            console.log(nft_id.toString());

            
           if(nft_id || tx_hash) {
                $('#nft_id').val(nft_id);
                $('#tx_hash').val(tx_hash);

               mintForm.off('submit').submit();
            }


        });
    }

    if (redeemForm.length > 0) {
        redeemForm.on('submit', async function(event) {
            event.preventDefault();
                        
            //connect to MetaMask
            const walletConnected = await connectWallet();
            if(!walletConnected) {
                return;
            }

            //await redeemDAI();

            
        
            if (!validateRedeem(redeemForm)) {
                return; // Form is not valid, stop here
            }

            var nftId = $('input[name="nft_id"]').val();

            let tx_hash = await pullNFT(nftId);


            
            if(nftId || tx_hash) {
                $('#tx_hash').val(tx_hash);

                redeemForm.off('submit').submit();
            }



        });
    }

    // Set up the click event handler for the fat button
   // Assuming connectWallet() and redeemDAI() are defined elsewhere and return Promises

    $('#fat-button').click(async function() {
        // Connect to MetaMask
        const walletConnected = await connectWallet();
        if (!walletConnected) {
            console.log('Wallet connection failed or was refused.');
            return; // Exit if the wallet connection wasn't successful
        }

        // Proceed to redeem DAI only if the wallet is connected
        console.log("redeeming dai");
        await redeemDAI();
    });


});