import config from "./config";
import dai from "./dai";

export let web3;
export let userAddress;

async function switchToArbitrum() {
    const arbitrumParams = {
        chainId: '0xa4b1', // Arbitrum One's chain ID in hexadecimal
        chainName: 'Arbitrum One',
        nativeCurrency: {
            name: 'Ethereum',
            symbol: 'ETH',
            decimals: 18
        },
        rpcUrls: ['https://arb1.arbitrum.io/rpc'],
        blockExplorerUrls: ['https://arbiscan.io/']
    };

    try {
        await window.ethereum.request({
            method: 'wallet_switchEthereumChain',
            params: [{ chainId: arbitrumParams.chainId }],
        });
        return true;
    } catch (error) {
        if (error.code === 4902) {
            try {
                await window.ethereum.request({
                    method: 'wallet_addEthereumChain',
                    params: [arbitrumParams],
                });
                return true;
            } catch (addError) {
                console.error('Failed to add Arbitrum network', addError);
                return false;
            }
        } else {
            console.error('Failed to switch to Arbitrum network', error);
            return false;
        }
    }
}

export async function connectWallet() {
    if (typeof window.ethereum !== 'undefined') {
        web3 = new Web3(window.ethereum);

        const switched = await switchToArbitrum();
        if (!switched) {
            alert("Failed to switch to or add Arbitrum network!");
            return false;
        }

        try {
            const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
            userAddress = accounts[0];
            console.log("ADDRESS: ", userAddress);

            // Assuming that the switch was successful, the network should now be Arbitrum
            $("#walletAddress").text(userAddress);
            $('#networkConnected').text("Arbitrum");

            return true;
        } catch (error) {
            console.error("User denied account access");
            return false;
        }
    } else {
        console.error("Ethereum browser not detected!");
        return false;
    }
}

export async function approveDAI(amount) {

    const daiContract = new web3.eth.Contract(dai.ABI, dai.CONTRACT_ADDRESS);

    let estimatedGas = await daiContract.methods.approve(dai.CONTRACT_ADDRESS, amount).estimateGas({ from: userAddress });

    await daiContract.methods.approve(config.CONTRACT_ADDRESS, amount).send({ from: userAddress, gas: estimatedGas });
}

export async function mintNFT() {
    const nftContract = new web3.eth.Contract(config.ABI, config.CONTRACT_ADDRESS);

    let estimatedGas = await nftContract.methods.mintNFT().estimateGas({ from: userAddress });

    let tx = await nftContract.methods.mintNFT().send({ from: userAddress, gas: estimatedGas });
    return tx.transactionHash;
}

export async function getDAIAllowance() {
    const daiContract = new web3.eth.Contract(dai.ABI, dai.CONTRACT_ADDRESS);

    try {
        const allowance = await daiContract.methods.allowance(userAddress, config.CONTRACT_ADDRESS).call();
        return allowance;
    } catch (error) {
        console.error("Error in checking DAI allowance:", error);
        throw error; // or return some error indicator
    }
}

export async function mintEvent() {
    // Initialize the contract with ABI and address
    const nftContract = new web3.eth.Contract(config.ABI, config.CONTRACT_ADDRESS);
    const currentBlockNumber = await web3.eth.getBlockNumber();
     // Assume a reasonable range or use 'fromBlock: 0' to 'toBlock: 'latest'' for the complete history
     const fromBlock = BigInt(currentBlockNumber) - BigInt(500);
     const toBlock = 'latest';
    // Fetch the events within a reasonable block range
    const events = await nftContract.getPastEvents('NFTMinted', {
        fromBlock: fromBlock,
        toBlock: toBlock,
        address: userAddress // Filtering by emitter address
    });

    return events[events.length - 1].returnValues.tokenId || null;
}


export async function pullNFT(tokenId) {
    const nftContract = new web3.eth.Contract(config.ABI, config.CONTRACT_ADDRESS);
    let estimatedGas = await nftContract.methods.pullNFT(tokenId).estimateGas({ from: userAddress });
    let tx = await nftContract.methods.pullNFT(tokenId).send({ from: userAddress, gas: estimatedGas });
    return tx.transactionHash;

}

export async function redeemDAI() {
            const nftContract = new web3.eth.Contract(config.ABI, config.CONTRACT_ADDRESS);

            //let e = await nftContract.methods.viewDepositedAmount().estimateGas({ from: userAddress });
            let amount = await nftContract.methods.viewDepositedAmount().call();
            console.log("amount: ", amount.toString());

            let dai = Web3.utils.fromWei(amount, 'ether'); // 'ether' unit is used here since 1 DAI = 1 Ether for conversion purposes

            let estimatedGas = await nftContract.methods.withdrawDAI(dai).estimateGas({ from: userAddress });
            let tx = await nftContract.methods.withdrawDAI(dai).send({ from: userAddress, gas: estimatedGas });
            console.log(tx.transactionHash);
}