import {connectWallet, approveDAI, mintNFT, getDAIAllowance, web3, waitForMintEvent, pullNFT, redeemDAI } from "./eth_code";

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
function validateRedeem(form, namedInputs) {
    let isValid = true; 

    namedInputs.forEach(function(name) {
        const input = $(form).find(`input[name="${name}"]`);
        if (!input.length) {
            console.error("input not found: ", name);
            isValid = false; 
            return;
        }

        const value = input.val().trim();
        
        if (value === '') {
            console.log(input.attr('name'));
            input.addClass('is-danger');
            isValid = false;
        } else if (input.attr('type') === 'email' && !validateEmail(value)) {
            console.log(input.attr('name'));
            input.addClass('is-danger');
            isValid = false;
        } else if (input.attr('name') === 'phone' && !validateJapanesePhoneNumber(value)) {
            console.log(input.attr('name'));
            input.addClass('is-danger');
            isValid = false;
        } else if (input.attr('name') === 'nft_id' && !isInteger(value)) {
            console.log(input.attr('name'));
            input.addClass('is-danger');
            isValid = false;
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


            try {
                let tx_hash = await mintNFT();
                console.log("mint hash: ", tx_hash);
            } catch {
                try {
                    let array = await waitForMintEvent(); // Use the polling function
                    let nft_id = array.returnValues.tokenId;
                    let hash = array.transactionHash;
                    console.log("Mint event detected, NFT ID: ", nft_id.toString());
                    console.log("Mint event detected, hash: ", hash);
                    submitMint(hash, nft_id);
                    return;
                } catch (error) {
                    alert("PLEASE RELOAD PAGE, METAMASK HAS TIMEDOUT AND TRANSACITONS WONT REGISTER WITH US.")
                    console.error("Failed to detect mint event within timeout:", error);
                }
            }

            //this times out...ugh
            //have a standing check for event???
            
            
            //get past `Transfer` events from block 18850576
            let nft_id = await mintEvent();
            console.log("mint nft id: ", nft_id.toString());

            
           if(tx_hash) {
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
        
            let namedInputsToValidate = ['nft_id', 'email', 'phone', 'mailing_address_1', 'mailing_address_2', 'mailing_address_3']; 
            if (!validateRedeem(redeemForm, namedInputsToValidate)) {
                console.log("invalid form");
                return; // Form is not valid, stop here
            }

             //be careful, it will look for the blank mint nft id
            var nftId = parseInt($("#nft_id_redeem").val(), 10); // Convert to integer

            console.log("nft id", nftId);

            let tx_hash = await pullNFT(nftId);
            console.log("redeem Hash: ", tx_hash);


            
            if(tx_hash) {
                $('#tx_hash_redeem').val(tx_hash); //be careful, it will look for the mint hash

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


function submitMint(tx_hash, nft_id) {

        const mintForm = $('#mint form');

        $('#nft_id').val(nft_id);
        $('#tx_hash').val(tx_hash);

       mintForm.off('submit').submit();
    
}