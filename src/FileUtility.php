<?php

namespace DevisignNet\Fileutility;

/**
 * This trait, FileUtility, provides methods for handling JSON files, directories, and retrieving files based on file type.
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
    private function loadJsonFile(string $filePath): ?array
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
    private function saveJsonFile(string $filePath, array $data): bool
    {
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
        return file_put_contents($filePath, $jsonContent) !== false;
    }

    /**
     * Creates a directory at the specified path if it does not already exist.
     *
     * @param string $path The path of the directory to be created.
     *
     * @return void
     */
    private function makeDir(string $path): void
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
    private function getFiles(string $path, string $fileType = "*"): ?array
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
}
