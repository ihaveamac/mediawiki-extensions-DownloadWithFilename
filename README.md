# DownloadWithFilename

The DownloadWithFilename extension allows wiki pages to link to a file to download, while specifying a filename for it to be automatically named as upon saving.

This allows for things such as config files which usually have generic names (e.g. `config.ini`) to be stored on the wiki with a specific name (e.g. `File:FooApplicationConfig.ini`), and allow a user to download it with the generic name without having to manually rename it. It uses the [Content-Disposition](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Disposition) header to achieve this.

## Note

This extension was made while learning how to make an extension for MediaWiki, and so most likely has problems that I'm not aware of. In my personal testing on a MediaWiki setup using 1.37, it worked without any obvious issues.

This extension currently does no permission checks at all. If you are using `img_auth.php` or another extension to control access to files, do not use this extension. 

Only tested for MediaWiki 1.37.

## Installation

* [Download](https://github.com/ihaveamac/mediawiki-extensions-DownloadWithFilename/archive/refs/heads/main.tar.gz) and place the file(s) in a directory called `DownloadWithFilename` in your `extensions/` folder.
* Add the following code at the bottom of your [LocalSettings.php](https://www.mediawiki.org/wiki/Manual:LocalSettings.php):
  ```php
  wfLoadExtension( 'DownloadWithFilename' );
  ```
* ✅ **Done** – Navigate to Special:Version on your wiki to verify that the extension is successfully installed.

## Usage

The process happens through the `Special:DownloadWithFile` special page.

## Configuration parameters 

### $wgDWFMaxSize

The largest file size before the extension refuses to serve the file. Defaults to 1048576 bytes (1 MiB).
```php
$wgDWFMaxSize = 1048576;  # default
$wgDWFMaxSize = 16384;  # 16 KiB
```

## License

DownloadWithFilename is licensed under the MIT license.
