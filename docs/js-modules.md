JavaScript Modules
==================

### Directory structure
* */assets/js/app*: all modules that are specific to the prototype webapp
* */assets/js/lib*: core libraries
* */assets/js/require* - RequireJS library and config files

### Notes
* All modules are written for RequireJS 
* All modules optional
* jQuery is loaded outside of RequireJS to make it available immediately on DOM ready.
* jQuery is also declared within RequireJS config as a supplement (see RequireJS readme)

### Modules

## util.js
**Description:** Provides general utilities such as console logging, date formatting, basic ajax loading, page scroll, etc.

####Public methods
* debug()
* format_date()
* ajax()
* scroll()

## mediator.js
**Description:** general pub/sub for coordinating different modules

####Public methods
* broadcast()
* add()
* rem()
* get()
* has()

## typeahead.js
**Description:** 

## modal.js
**Description:** standard modal window

## gallery.js
**Description:** standard photo gallery

## sticky_header.js
**Description:** basic shrinking persistent header

## expand_collapse.js
**Description:** basic shrinking persistent header
