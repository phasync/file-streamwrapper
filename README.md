# phasync/file-streamwrapper

`phasync/file-streamwrapper` is a PHP package that makes all disk IO operations asynchronous transparently, when used within a phasync coroutine. This package allows you to perform file operations like reading and writing files asynchronously, improving the efficiency of your I/O-bound tasks.

## Installation

You can install the package via Composer. There is configuration needed as it automatically configures itself to be enabled inside coroutines and disables itself outside of coroutines.

```bash
composer require phasync/file-streamwrapper
```

## Usage

When installing this package, async file:// IO is automatically enabled inside phasync coroutines. It does not interfere with IO outside of coroutines.

## Example

Here's an example of how to use phasync/file-streamwrapper within the phasync coroutine framework:

```php
<?php

require 'vendor/autoload.php';

// Example usage within phasync coroutine framework
phasync::run(function() {
    phasync::go(function() {
        $data = file_get_contents("some-path");
        // Handle the data
        echo "Data from some-path: " . $data . PHP_EOL;
    });

    phasync::go(function() {
        $data = file_get_contents("other-path");
        // Handle the data
        echo "Data from other-path: " . $data . PHP_EOL;
    });
});
```

In this example, two files are read asynchronously using file_get_contents within phasync coroutines. The custom stream wrapper ensures that these file operations are non-blocking and efficient.

## License

This package is open-source and licensed under the MIT License.

## Contributing

Contributions are welcome! Please submit pull requests or open issues for any bugs or feature requests.

## Contact

For any questions or inquiries, please open an issue on the GitHub repository.