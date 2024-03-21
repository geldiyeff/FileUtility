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
        $jsonContent = file_get_contents($filePath);
        return json_decode($jsonContent, true);
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
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
        return file_put_contents($filePath, $jsonContent) !== false;
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
        return file_put_contents($filePath, $data) !== false;
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
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
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
        $files = scandir($path);
        $files = array_diff($files, array('.', '..'));

        $result = [];

        foreach ($files as $file) {
            if (is_dir($path . '/' . $file)) {
                $result = array_merge($result, $this->getFiles($path . '/' . $file, $fileType));
            } else {
                if ($fileType === "*") {
                    $result[] = $path . '/' . $file;
                } else {
                    if (pathinfo($file, PATHINFO_EXTENSION) === $fileType) {
                        $result[] = $path . '/' . $file;
                    }
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
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            $zip->extractTo($extractTo);
            $zip->close();
            return true;
        } else {
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
        $zip = new ZipArchive;
        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file);
            }
            $zip->close();
            return true;
        } else {
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
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile($file);
            }
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}
