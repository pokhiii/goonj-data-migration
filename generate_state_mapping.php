<?php

// Check if the CSV file path is provided as a command line argument
if ($argc < 2) {
    die("Usage: php map_states.php <path_to_csv_file>\n");
}

// Get the CSV file path from the command line arguments
$csvFile = $argv[1];

// Open the CSV file
if (($handle = fopen($csvFile, 'r')) !== false) {
    // Read the header line
    $header = fgetcsv($handle);

    // Find the index of the "Mailing State/Province" column
    $stateColumnIndex = array_search('Mailing State/Province', $header);

    if ($stateColumnIndex === false) {
        die("The column 'Mailing State/Province' was not found in the CSV file.\n");
    }

    // Initialize the mapping array
    $mapping = [];

    // Function to prompt the user for input
    function prompt($prompt_text) {
        echo $prompt_text;
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        return trim($line);
    }

    // Loop through each row in the CSV
    while (($row = fgetcsv($handle)) !== false) {
        // Get the state name from the specified column
        $stateName = $row[$stateColumnIndex];

        // Check if the state name is already mapped to a standard name
        if (!isset($mapping[$stateName])) {
            // Prompt the user for the standard state name
            $standardName = prompt("Enter the standard name for '$stateName': ");

            // Store the mapping
            $mapping[$stateName] = $standardName;
        }

        // Use the standard name for the current state
        $standardStateName = $mapping[$stateName];

        // Do something with the standard state name (e.g., print it or store it)
        echo "Standard name for '$stateName' is '$standardStateName'\n";
    }

    // Close the CSV file
    fclose($handle);

    // Optionally, save the mapping array to a file for future use
    file_put_contents('state_mapping.json', json_encode($mapping));
} else {
    die("Could not open the CSV file.\n");
}

?>
