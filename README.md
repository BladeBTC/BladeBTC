# BladeBTC
Telegram Bot

## Installation

[`nodejs`](https://nodejs.org) and [`npm`](https://npmjs.com) are required to install and use this API service. Installation:

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
  * `--ssl-key` - the path to your ssl key (optional)
  * `--ssl-cert` - the path to your ssl certificate (optional)

To open the service to all incoming connections, bind to `0.0.0.0`.

#### start-rpc

Usage: `blockchain-wallet-service start-rpc [options]`

This command will start the JSON RPC server.

Options:

  * `-k, --key` - api code to use for server requests (required option)
  * `-p, --rpcport` - rpc server port (default: 8000)
  * `-b, --bind` - bind to a specific ip (defaults to `127.0.0.1`, note that binding to an ip other than this can lead to security vulnerabilities)

Get an API code [here](https://blockchain.info/api/api_create_code).

### Examples

To start the Wallet API service on port 3000:

```sh
$ blockchain-wallet-service start --port 3000
```