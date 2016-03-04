<?php
session_start();
#print_r($_POST);
# Did we get our data?
if (isset($_POST['pathway_results']))
{
    #if (isset($_POST['pathway']))
    if (isset($_SESSION['post_data']['pathway_data_full']))
    {
        $data = $_SESSION['post_data']['pathway_data_full'];
        $filename = "pathway_analysis.csv";
    }
    else
    {
        echo "ERROR: Downloading data: pathway data not found.";
        exit();
    }
}
else if (isset($_POST['hub_results']))
{
    #if (isset($_POST['hub']))
    if (isset($_SESSION['post_data']['hub_data_full']))
    {
        $data = $_SESSION['post_data']['hub_data_full'];
        $filename = "hub_gene_analysis.csv";
    }
    else
    {
        echo "ERROR: Downloading data: hub data not found.";
        exit();
    }
}
else
{
    echo "ERROR: Missing data to download!";
    exit();
}

# create a tmp file
$temp_file = tempnam('/tmp', '');
$fp = fopen($temp_file, "w");
for ($index = 0; $index < sizeof($data); $index++)
{
    fwrite($fp, $data[$index]);
}
fclose($fp);

# download the file
if (file_exists($temp_file))
{
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($temp_file));
    readfile($temp_file);
}

# Remove the tmp file
unlink($temp_file);
?>
