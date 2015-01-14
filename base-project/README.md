# Getting Started

## Installation

### Node

Make / Model uses the Node’s package manager to install packages like [Grunt](http://gruntjs.com/) and [Bower](http://bower.io/). Information on installing Node can be found at [nodejs.org](http://nodejs.org/).

`npm` will install the packages defined in `package.json`. The default `package.json` includes:

- [Bower](http://bower.io/): A package manager for front-end assets like jQuery or Twitter Bootstrap
- [Grunt](http://gruntjs.com/): A JavaScript task runner used in this project to start a server, watch for changes, and update static assets
- [grunt-contrib-watch](https://github.com/gruntjs/grunt-contrib-watch): Grunt plugin to watch files for changes
- [grunt-contrib-less](https://github.com/gruntjs/grunt-contrib-less): Grunt plugin to compile LESS to CSS

To install the node packages for this base project navigate to the project’s directory and run the following in Terminal:

```bash
npm install
```

To install additional packages run:

```bash
npm install <name> --save-dev
```

This will install the module as well as add it to the `package.json` file.

### Bower

Make / Model uses Bower to install common front-end assets like jQuery or Twitter Bootstrap. Bower will install the packages defined in `bower.json`.

```bash
bower install
```

Additional packages can be found at [bower.io/search/](http://bower.io/search/). To install a new package run:

```bash
bower install <name> --save
```

## Usage

### Grunt

Grunt is used to start the PHP web server and the watch task for file changes.

To start the default task run:

```bash
grunt
```

Use the key combination ctrl-c to exit the task.