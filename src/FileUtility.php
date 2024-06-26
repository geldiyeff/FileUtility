<?php

namespace Geldiyeff\Fileutility;

use ZipArchive;

/**
 * The FileUtility trait provides methods for handling JSON files, directories, and managing ZIP archives.
 */
trait FileUtility
{
    /**
     * Loads the content of a JSON file and returns it as an associative array.
     *
     * @param string $filePath The path to the JSON file.
     *
     * @return array|null The decoded JSON content as an associative array, or null on failure.
     */
    public function loadJsonFile(string $filePath): ?array
    {
        // Check if the file exists
        if (!file_exists($filePath)) {
            return null;
        }

        // Read JSON content from the file
        $jsonContent = file_get_contents($filePath);

        // Decode JSON content
        $data = json_decode($jsonContent, true);

        // Check for decoding errors
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $data;
    }

    /**
     * Saves data as a JSON file with pretty-printing, ensuring readability.
     *
     * @param string $filePath The path to the JSON file.
     * @param array  $data     The data to be encoded and saved to the file.
     *
     * @return bool True on successful file write, false otherwise.
     */
    public function saveJsonFile(string $filePath, array $data): bool
    {
        // Encode data as JSON with pretty-printing and unicode support
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Check if encoding was successful
        if ($jsonContent === false) {
            return false;
        }

        // Create directory if it doesn't exist
        $this->makeDir(dirname($filePath));

        // Write JSON content to the file
        return file_put_contents($filePath, $jsonContent) !== false;
    }

    /**
     * Creates a directory at the specified path if it does not already exist.
     *
     * @param string $path The path of the directory to be created.
     *
     * @return void
     */
    public function makeDir(string $path): void
    {
        // Check if the directory doesn't exist, then create it
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * Saves data to a specified file path.
     *
     * This method writes the provided data to the specified file path.
     *
     * @param string $filePath The path where the data will be saved.
     * @param string $data The data to be saved.
     * @return bool Returns true if the data was successfully saved, false otherwise.
     */
    public function saveFile(string $filePath, string $data): bool
    {
        // Check if the file path is empty
        if (empty($filePath)) {
            return false;
        }

        // Create directory if it doesn't exist
        $directory = dirname($filePath);
        $this->makeDir($directory);

        // Check if directory is writable
        if (!is_writable($directory)) {
            return false;
        }

        // Determine flags based on file existence
        $flags = 0;
        if (file_exists($filePath)) {
            $flags = FILE_APPEND;
        }

        // Write data to the file
        $result = file_put_contents($filePath, $data, $flags);

        // Check if writing was successful
        if ($result === false) {
            return false;
        }

        return true;
    }

    /**
     * Retrieves files from a specified directory based on a given file type.
     *
     * @param string $path     The path of the directory to search for files.
     * @param string $fileType The file type (extension) to filter the results. Use "*" for all file types.
     *
     * @return array|null An array of file paths matching the criteria, or null if no files are found.
     */
    public function getFiles(string $path, string $fileType = "*"): ?array
    {
        // Scan directory for files
        $files = scandir($path);
        $files = array_diff($files, array('.', '..'));

        $result = [];

        // Iterate over files
        foreach ($files as $file) {
            if (is_dir($path . '/' . $file)) {
                // Recursively call getFiles for directories
                $result = array_merge($result, $this->getFiles($path . '/' . $file, $fileType));
            } else {
                // Add file to result if file type matches or all types are allowed
                if ($fileType === "*" || pathinfo($file, PATHINFO_EXTENSION) === $fileType) {
                    $result[] = $path . '/' . $file;
                }
            }
        }

        return $result;
    }

    /**
     * Removes the specified files.
     *
     * @param array $files An array of file paths to be removed.
     *
     * @return bool True if all files were successfully removed, false otherwise.
     */
    public function removeFiles(array $files): bool
    {
        // Iterate over files and remove them
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }

    /**
     * Extracts files from a ZIP archive.
     *
     * @param string $zipFile    The path to the ZIP archive file.
     * @param string $extractTo  The directory where files should be extracted.
     *
     * @return bool True if extraction was successful, false otherwise.
     */
    public function zipExtract(string $zipFile, string $extractTo): bool
    {
        // Check if the ZIP archive file exists and is readable
        if (!file_exists($zipFile) || !is_readable($zipFile)) {
            return false;
        }

        // Create extraction directory if it doesn't exist
        $this->makeDir($extractTo);

        // Open the ZIP archive
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === true) {
            // Extract files from the ZIP archive
            $extractResult = $zip->extractTo($extractTo);
            $zip->close();

            return $extractResult;
        } else {
            // Unable to open the ZIP archive
            return false;
        }
    }

    /**
     * Creates a new ZIP archive and adds specified files.
     *
     * @param string $zipFile The path to the new ZIP archive to create.
     * @param array  $files   An array of file paths to be added to the archive.
     *
     * @return bool True if ZIP archive creation was successful, false otherwise.
     */
    public function zipCreate(string $zipFile, array $files): bool
    {
        // Create a new ZIP archive
        $zip = new ZipArchive;
        if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
            // Add files to the ZIP archive
            foreach ($files as $file) {
                $zip->addFile($file);
            }
            $zip->close();
            return true;
        } else {
            // Unable to create the ZIP archive
            return false;
        }
    }

    /**
     * Adds files to an existing ZIP archive.
     *
     * @param string $zipFile The path to the existing ZIP archive.
     * @param array  $files   An array of file paths to be added to the archive.
     *
     * @return bool True if files were successfully added to the ZIP archive, false otherwise.
     */
    public function zipAdd(string $zipFile, array $files): bool
    {
        // Open the existing ZIP archive
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === true) {
            // Add files to the ZIP archive
            foreach ($files as $file) {
                $zip->addFile($file);
            }
            $zip->close();
            return true;
        } else {
            // Unable to open the ZIP archive
            return false;
        }
    }
}
