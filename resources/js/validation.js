import {connectWallet, approveDAI, mintNFT, getDAIAllowance, web3, mintEvent } from "./eth_code";

// Function to validate an email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Function to validate a form
function validateForm(form, namedInputs) {
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
            if (!validateForm(mintForm, namedInputsToValidate)) {
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
        redeemForm.on('submit', function(event) {
            if (!validateForm(redeemForm)) {
                event.preventDefault();
            }

            //nftContract.methods.approve(contractAddress, tokenId).send({ from: userAddress });

        });
    }
});