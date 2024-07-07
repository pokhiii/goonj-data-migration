<?php

// Check if the CSV file path and JSON file path are provided as command line arguments
if ($argc < 3) {
    die("Usage: php modify_csv.php <path_to_csv_file> <path_to_json_file>\n");
}

// Get the CSV file path and JSON file path from the command line arguments
$csvFile = $argv[1];
$jsonFile = $argv[2];

// Read the JSON mapping file
$mapping = json_decode(file_get_contents($jsonFile), true);

if ($mapping === null) {
    die("Could not read or parse the JSON mapping file.\n");
}

// Open the input CSV file
if (($handle = fopen($csvFile, 'r')) !== false) {
    // Read the header line
    $header = fgetcsv($handle);

    // Find the index of the "Mailing State/Province" column
    $stateColumnIndex = array_search('Mailing State/Province', $header);

    if ($stateColumnIndex === false) {
        die("The column 'Mailing State/Province' was not found in the CSV file.\n");
    }

    // Add a new column for the standard state names
    $header[] = 'Standard State Name';

    // Create an array to hold the modified CSV data
    $modifiedData = [];
    $modifiedData[] = $header;

    // Loop through each row in the input CSV
    while (($row = fgetcsv($handle)) !== false) {
        // Get the state name from the specified column
        $stateName = $row[$stateColumnIndex];

        // Get the standard state name from the mapping array
        $standardStateName = isset($mapping[$stateName]) ? $mapping[$stateName] : '';

        // Add the standard state name to the row
        $row[] = $standardStateName;

        // Add the modified row to the array
        $modifiedData[] = $row;
    }

    // Close the input CSV file
    fclose($handle);

    // Define the path to the output CSV file
    $outputCsvFile = 'modified_' . basename($csvFile);

    // Open the output CSV file for writing
    if (($outputHandle = fopen($outputCsvFile, 'w')) !== false) {
        // Write the modified data to the output CSV file
        foreach ($modifiedData as $modifiedRow) {
            fputcsv($outputHandle, $modifiedRow);
        }

        // Close the output CSV file
        fclose($outputHandle);

        echo "Modified CSV file has been written to '$outputCsvFile'\n";
    } else {
        die("Could not open the output CSV file for writing.\n");
    }
} else {
    die("Could not open the input CSV file.\n");
}

?>
