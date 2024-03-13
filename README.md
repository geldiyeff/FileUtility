# FileUtility PHP Trait

The **FileUtility** trait is a PHP trait that provides methods for handling JSON files, directories, and retrieving files based on file type.

## Installation

To use this trait in your PHP project, you can either manually download the `FileUtility.php` file and include it in your project, or you can use [Composer](https://getcomposer.org/) to install it.

### Composer Installation

Run the following command in your project directory:

```bash
composer require geldiyeff/fileutility
```

## Usage

To use the FileUtility trait in your PHP class, simply include it using the `use` statement:

```php
use Geldiyeff\Fileutility\FileUtility;
```

### Methods

#### `loadJsonFile(string $filePath): ?array`

Loads the content of a JSON file and returns it as an associative array.

```php
$fileUtility = new YourClassUsingFileUtility();
$data = $fileUtility->loadJsonFile('/path/to/file.json');
```

#### `saveJsonFile(string $filePath, array $data): bool`

Saves data as a JSON file with pretty-printing, ensuring readability.

```php
$fileUtility = new YourClassUsingFileUtility();
$success = $fileUtility->saveJsonFile('/path/to/file.json', $data);
```

#### `makeDir(string $path): void`

Creates a directory at the specified path if it does not already exist.

```php
$fileUtility = new YourClassUsingFileUtility();
$fileUtility->makeDir('/path/to/directory');
```

#### `getFiles(string $path, string $fileType = "*"): ?array`

Retrieves files from a specified directory based on a given file type.

```php
$fileUtility = new YourClassUsingFileUtility();
$files = $fileUtility->getFiles('/path/to/directory', 'txt');
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Inspired by the need for efficient file and directory handling in PHP projects.
