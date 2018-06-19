[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/nicelife90/BladeBTC.svg)](http://isitmaintained.com/project/nicelife90/BladeBTC "Average time to resolve an issue")
[![Percentage of issues still open](http://isitmaintained.com/badge/open/nicelife90/BladeBTC.svg)](http://isitmaintained.com/project/nicelife90/BladeBTC "Percentage of issues still open")

# BladeBTC
This application is a telegram bot. The goal of this bot is to create a Bitcoin exchange platform. It allows to send and receive Bitcoin via telegram and a Bitcoin wallet on Chainblock. It guarantees a profit on investments. Users can invest, reinvest and withdraw their Bitcoin at any time via the telegram interface.

## Prerequisites 

This application is designed to be installed on a Linux server on which you have administrator access.
This application and the installation script have been tried on a Ubuntu 14.04 LTS server.

- Linux server (Ubuntu 14.04 LTS).
- Root privileges.
- Public / Private static IP on this server (recommended).
- Open ports 80, 443, 10000 (Before using the install script).
- Latest release of this application. [Download Here](https://github.com/nicelife90/BladeBTC/releases)

> This script will only work for Debian based Linux distributions.

> Most recent version of Ubuntu may not work because of PHP 7.1 

> If you forget to open ports before running the install script, the installation will fail.

> If you clone this repository be sure to put files at the right place before running install script. Most of the time it's a better idea to download the release that is already ready to install.

## Installation

#### Telegram Bot

- Use BotFather on Telegram to create new bot. [BotFather](https://telegram.me/BotFather)
- Customize and put your new bot inline with BotFather.

#### Blockchain Wallet

- Create new Wallet on Blockchain website. [BlockChain](https://blockchain.info/fr/wallet/#/signup)
- In Settings / Security - Setup second password for your wallet.
- In Settings / Security / Advance settings - Withelist your public IP.

#### Domain Name / No-IP

- Register new domain name at [GoDaddy](https://ca.godaddy.com/) or any registrar.
- Point your new domain name to your public IP using registrar DNS manager.

> If you don't want to pay for a domaine name you could use [No-IP](https://www.noip.com/) to create free host.

#### Server

> Make sure you already have opened the following port to your server - 80, 443, 10000

On a fresh install of Ubuntu 14.04.5 LTS do the following commands :

- Upload install.zip to ``/var/tmp``

> Use apache2 when the installer.sh ask you for a choice!

```sh
$ cd /var/tmp
$ sudo apt-get install -y unzip dos2unix
$ unzip install.zip
$ cd install/
$ dos2unix installer.sh
$ chmod 777 installer.sh
$ sudo ./installer.sh
````

- Follow the script instruction and give all the required data.
- Reboot your server.

## Hidden commands

- /gwb - To get current blockchain wallet balance.
- /sms [your message without brace] - To send a Telegram message to all account.

## Warnings and Disclaimers 

This application was created for educational purposes only. It is forbidden to copy, sell and distribute this application in any way. The principle behind this application remains illegal and it is forbidden to make any real use of it. If you decide to break the law, only you can be held responsible and you can ``not`` deny the fact.
