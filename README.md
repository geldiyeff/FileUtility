# FileUtility PHP Trait

[![Latest Stable Version](https://poser.pugx.org/geldiyeff/fileutility/v/stable.svg)](https://packagist.org/packages/geldiyeff/fileutility)
[![License](https://poser.pugx.org/geldiyeff/fileutility/license.svg)](https://packagist.org/packages/geldiyeff/fileutility)
[![Total Downloads](https://poser.pugx.org/geldiyeff/fileutility/downloads)](https://packagist.org/packages/geldiyeff/fileutility)

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

#### `ZipExtract(string $zipFile, string $extractTo): bool`

Extracts files from a ZIP archive.

```php
$fileUtility = new YourClassUsingFileUtility();
$success = $fileUtility->ZipExtract('/path/to/archive.zip', '/path/to/extract');
```

#### `ZipCreate(string $zipFile, array $files): bool`

Creates a new ZIP archive and adds specified files.

```php
$fileUtility = new YourClassUsingFileUtility();
$success = $fileUtility->ZipCreate('/path/to/new-archive.zip', ['/path/to/file1.txt', '/path/to/file2.txt']);
```

#### `ZipAdd(string $zipFile, array $files): bool`

Adds files to an existing ZIP archive.

```php
$fileUtility = new YourClassUsingFileUtility();
$success = $fileUtility->ZipAdd('/path/to/archive.zip', ['/path/to/newfile.txt', '/path/to/anotherfile.txt']);
```

#### `saveFile(string $filePath, string $data): bool`

Saves data to a specified file path.

```php
$fileUtility = new YourClassUsingFileUtility();
$success = $fileUtility->saveFile('/path/to/file.txt', 'Some data to be saved.');
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Inspired by the need for efficient file and directory handling in PHP projects.