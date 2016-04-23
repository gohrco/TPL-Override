# TPL_Override
## Synopsis

Provide a simple means to override your template files in WHMCS without losing changes upon upgrading.

## Installation

To install, download the zip archive or clone the repo to your machine. Then...

1. Move the /modules/addons/tpl_override folder into your WHMCS root directory.
2. Log into your WHMCS Admin Control Panel and go to Setup > Addon Modules.
3. Find the row that corresponds to TPL Override and click Activate.


## Basic Usage

Using TPL Override is super simple.  Let's say you are working in the /templates/six folder, and you wish to create a custom header.tpl file, but when the next update from WHMCS comes out, you don't want those changes lost.  No problem!

1. Create a folder in your /templates/six folder called `custom` (so your path would be /templates/six/custom)
2. Copy the header.tpl file (or any file you wish to provide an override for) into the new custom folder.
3. Edit away!

## Things to be aware of

### Version support
This version of TPL_Override is tested against 6.3 and not very thoroughly.  There may be templates or there may be earlier versions that this will not work with.  Sorry!

### Template Cache
Be sure to clear your template cache in WHMCS > Utilities > System > Cleanup to ensure you are loading the latest version of your custom file.

### Support
This module is provided 100% free of charge and is not supported directly.  If you have any issues, please do post them in the Issues tab in Github.

### Enjoy!