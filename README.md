
# BladeBTC (UBUNTU 18.04.2 LTS)
This application is a telegram bot. The goal of this bot is to create a Bitcoin exchange platform. It allows to send and receive Bitcoin via telegram and a Bitcoin wallet on Blockchain. It guarantees a profit on investments. Users can invest, reinvest and withdraw their Bitcoin at any time via the telegram interface.

## Prerequisites 

This application is designed to be installed on a Linux server on which you have administrator access.
This application and the installation script have been tried on a Ubuntu 18.04 LTS server.

- PHP 7.x
- Linux server (Ubuntu 18.04.2 LTS).
- Root privileges.
- Static IP (Public & Private).
- Open ports 80, 443 (Before using the install script).

```diff
- This script will only work for Debian based Linux distributions.
```

```diff
- Don't forget to open ports before running the install script otherwise the installation will fail.
```

## Installation

#### Telegram Bot

- Use BotFather on Telegram to create new bot. [BotFather](https://telegram.me/BotFather)

Use this commande in BotFather chat to create new Bot:

```sh
/newbot
```

- Save your Telegram Bot API Key for later.

Your API Key should look like this:

```sh
801650799:AAEYIthu4KWV14ZzKauXb5KdF8cKHRzluRE
```

#### Blockchain Wallet

- Create new Wallet on Blockchain website. [BlockChain](https://blockchain.info/fr/wallet/#/signup)
- In Settings / Security - Setup second password for your wallet.
- In Settings / Security / Advance settings - Withelist your public IP.
- Save your Wallet ID for later.

Your wallet ID should look like this:

```sh
cd6c4470-1195-4c44-83d7-7b223a2f8ggd
```

#### No-IP

- Register new free hostname at [No-IP](https://www.noip.com/) pointing on your server public IP.

#### Server (Installation)

- Download and Install [UBUNTU 18.04.2 LTS](https://www.ubuntu.com/download/server/thank-you?version=18.04.2&architecture=amd64)
> During the installation process of Ubuntu only select the SSH package.

```diff
- Make sure to open the following port to your server - 80, 443 before running the install script.
```

On your fresh install of Ubuntu 18.04.2 LTS do the following commands :

```sh
sudo -s
apt-get install git -y
cd /var/tmp
git clone https://github.com/nicelife90/BladeBTC.git
cd BladeBTC/
chmod 550 install.sh
./install.sh
````

```diff
- Select option 1 - Install
```

- Follow the script instruction and give all the required data.

```diff
- IMPORTANT - Go to your blockchain wallet and withelist your server IP. 
```


## Updating / Bot file & GUI file

When the bot was installed for the first time you can now get update from github like this:

```sh
sudo -s
cd /var/tmp/BladeBTC
./install.sh
````

```diff
- Select option 2 - Update
```

## GUI / ADMIN PANEL

The install script enable multiple admin tool to help you to manage your server.

#### PHPMyAdmin
From PHPMyAdmin you can manage all database required by your bot and by the GUI.

**URL:** https://[your_domain]/phpmyadmin

#### BladeBTC GUI
From BladeBTC GUI you can manage multiple options about your Bot.


```diff
- Default Username: bladebtc
- Default Password: bladebtc
```

- User (ToDo)
- Investment Plan
- Ban (ToDo)
- Logs

**URL:** https://[your_domain]/gui

## Hidden commands

- /gwb - To get current blockchain wallet balance.

## Warnings and Disclaimers 

This application was created for educational purposes only. It is forbidden to copy, sell and distribute this application in any way. The principle behind this application remains illegal and it is forbidden to make any real use of it. If you decide to break the law, only you can be held responsible and you can ``not`` deny the fact.
