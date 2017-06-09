# BladeBTC
This application is a Bot for telegram. The goal of this Bot is to create a Bitcoin exchange platform. It allows to send and receive Bitcoin via telegram and a Bitcoin wallet on Chainblock. It guarantees a profit on investments. Users can invest and withdraw their Bitcoin at any time via the telegram interface.

## Installation

This application require `Blockchain Wallet API V2` to send and receive Bitcoin.
Yous must install this service first.

## Blockchain Wallet API V2

[`nodejs`](https://nodejs.org) and [`npm`](https://npmjs.com) are required to install and use this API service. Installation:

#### node.js / npm

```sh
$ curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
$ sudo apt-get install -y nodejs
$ sudo npm install -g npm
$ sudo apt-get install -y build-essential
```

#### Wallet

```sh
$ npm install -g blockchain-wallet-service
```

For the best stability and performance, make sure you are always using the latest version.

To check your version:

```sh
$ blockchain-wallet-service -V
```

To update to the latest version:

```sh
$ npm update -g blockchain-wallet-service
```

Requires:

  * node >= 6.0.0
  * npm >= 3.0.0

If you have issues with the installation process, see the troubleshooting section below.

## Troubleshooting

Installation errors:

  * If you are getting `EACCESS` or permissions-related errors, it might be necessary to run the install as root, using the `sudo` command.

  * If you are getting errors concerning node-gyp or python, install with `npm install --no-optional`

Startup errors:

  * If startup fails with `/usr/bin/env: node: No such file or directory`, it's possible node is not installed, or was installed with a different name (Ubuntu, for example, installs node as nodejs). If node was installed with a different name, create a symlink to your node binary: `sudo ln -s /usr/bin/nodejs /usr/bin/node`, or install node through [Node Version Manager](https://github.com/creationix/nvm).

Runtime errors:

  * If you are seeing a `TypeError` claiming that an object `has no method 'compare'`, it is because you are on a version of Node older than 0.12, before the `compare` method was added to Buffers. Try upgrading to at least Node version 0.12.

  * If you are getting wallet decryption errors despite having correct credentials, then it's possible that you do not have Java installed, which is required by a dependency of the my-wallet-v3 module. Not having Java installed during the `npm install` process can result in the inability to decrypt wallets. Download the JDK from [here for Mac](https://support.apple.com/kb/DL1572) or by running `apt-get install default-jdk` on debian-based linux systems.

Timeout Errors:

  * If you are getting a timeout response, additional authorization from your blockchain wallet may be required. This can occur when using an unrecognized browser or IP address. An email authorizing the API access attempt will be sent to the registered user that will require action in order to authorize future requests.

If this section did not help, please open a github issue or visit our [support center](https://blockchain.zendesk.com).

## Usage

After installing the service, the command `blockchain-wallet-service` will be available for use.

### Options

  * `-h, --help` - output usage information
  * `-V, --version` - output the version number
  * `-c, --cwd` - use the current directory as the wallet service module (development only)

### Commands

#### start

Usage: `blockchain-wallet-service start [options]`

This command will start the service, making Blockchain Wallet API V2 available on a specified port.

Command options:

  * `-h, --help` - output usage information
  * `-p, --port` - port number to run the server on (defaults to `3000`)
  * `-b, --bind` - bind to a specific ip (defaults to `127.0.0.1`, note that binding to an ip other than this can lead to security vulnerabilities)

### Examples

To start the Wallet API service on port 3000:

```sh
$ blockchain-wallet-service start --port 3000
```