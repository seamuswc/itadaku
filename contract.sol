// SPDX-License-Identifier: MIT 
pragma solidity ^0.8.0;

import "@openzeppelin/contracts/token/ERC721/ERC721.sol";
import "@openzeppelin/contracts/token/ERC20/IERC20.sol";
import "@openzeppelin/contracts/access/Ownable.sol";


contract DAIForNFT is ERC721, Ownable(msg.sender) {
    IERC20 public dai; //arb: 0xda10009cbd5d07dd0cecc66161fc93d7c9000da1
    uint32 public tokenCounter;
    uint256 public NFT_COST; // NFT cost, set in constructor

    constructor(address _dai, uint256 _nftCost) ERC721("ITADAKU", "ITA") {
        dai = IERC20(_dai);
        NFT_COST = _nftCost * 10**18; // Convert DAI to smallest unit
        tokenCounter = 0;
    }


    function viewApprovedAmount() public view returns (uint256) {
        return dai.allowance(msg.sender, address(this));
    }
    function viewDepositedAmount() public view returns (uint256) {
        return dai.balanceOf(address(this));
    }
    function withdrawDAI(uint256 daiAmount) public onlyOwner {
        uint256 weiAmount = daiAmount * 10**18; // Convert DAI amount to weiAmount
        require(dai.transfer(owner(), weiAmount), "DAI transfer failed");
    }


    event NFTMinted(uint32 indexed tokenId);

    function mintNFT() public {
        require(dai.transferFrom(msg.sender, address(this), NFT_COST), "DAI payment failed");
        
        tokenCounter++;
        _mint(msg.sender, tokenCounter);

        _setTokenURI(tokenCounter, "http://itadaku.xyz/meta.json");

        emit NFTMinted(tokenCounter);

    }

    mapping(uint32 => string) private _tokenURIs;


    function _setTokenURI(uint32 tokenId, string memory _tokenURI) internal virtual {
        require(ownerOf(tokenId) != address(0), "Token ID doesn't have a user: _setTokenURI");

        _tokenURIs[tokenId] = _tokenURI;
    }

    function tokenURI(uint32 tokenId) public view returns (string memory) {
        require(ownerOf(tokenId) != address(0), "Token ID doesn't have a user: tokenURI");

        string memory _tokenURI = _tokenURIs[tokenId];
        return _tokenURI;
    }

    function pullNFT(uint32 tokenId) public {
        require(ownerOf(tokenId) == msg.sender, "You must own the NFT to pull it");
        require(tokenId > 0 && tokenId <= tokenCounter, "Token ID is not valid or was not minted by this contract");
        
        // Transfer the NFT from the owner to the contract
        _transfer(msg.sender, address(this), tokenId);
    }

}

