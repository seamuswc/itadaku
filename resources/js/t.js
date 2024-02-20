export function mintNFT2() {
    return new Promise((resolve, reject) => {
        const nftContract = new web3.eth.Contract(config.ABI, config.CONTRACT_ADDRESS);
        nftContract.methods.mintNFT().estimateGas({ from: userAddress })
            .then(estimatedGas => nftContract.methods.mintNFT().send({ from: userAddress, gas: estimatedGas }))
            .then(transaction => resolve(transaction.transactionHash))
            .catch(reject);
    });
}

export function listenForMintEvent2() {
    return new Promise((resolve, reject) => {
        const nftContract = new web3.eth.Contract(config.ABI, config.CONTRACT_ADDRESS);
        const subscription = nftContract.events.NFTMinted({
            filter: { address: userAddress },
            fromBlock: 0,
            toBlock: 'latest'
        })
        .on('data', event => {
            resolve(event);
            subscription.unsubscribe();
        })
        .on('error', reject);
    });
}


/*

            mintNFT2()
                .then(tx_hash => {
                    console.log("mint hash: ", tx_hash);
                    return listenForMintEvent2();
                })
                .then(event => {
                    const nft_id = event.returnValues.tokenId;
                    const tx_hash = event.transactionHash;
                    console.log("Mint event detected, NFT ID: ", nft_id.toString());
                    console.log("Mint event detected, hash: ", tx_hash);
                    submitMint(tx_hash, nft_id); // Make sure this function can handle asynchronous operations if needed
                })
                .catch(error => {
                    console.error("Failed to mint or detect mint event:", error);
                    alert("DO NOT SUBMIT TRANSACTION, DATA WILL NOT BE PASSED TO OUR SQL. PAGE WILL RELOAD ON CLICK")
                    location.reload();
                });
*/