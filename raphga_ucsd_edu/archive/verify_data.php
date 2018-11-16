<?php
#print_r($_POST);
#echo "<br>";
#print_r($_FILES);
#echo "<br>";
#print_r($_SERVER);
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    // If we just clearing the data,
    // remove the session and go back to the front page:
    if (isset($_POST['clear']))
    {
        if (isset($_SESSION['post_data']))
        {
            unset($_SESSION['post_data']);
            header("Location: index.php");
            exit();
        }
        else
        {
            echo "No session data found, nothing cleared.";
            header("Location: index.php");
            exit();
        }
    }

    $_SESSION['post_data'] = $_POST;
    if (isset($_POST['species']))
    {
        $species = $_POST['species'];
    }
    else
    {
        echo "ERROR: Missing species!";
        exit();
    }

    if (isset($_POST['analysis']))
    {
        $analysis = $_POST['analysis'];
    }
    else
    {
        echo "ERROR: Missing analysis type!";
        exit();
    }

    if (isset($_POST['type']))
    {
        $type = $_POST['type'];
    }
    else
    {
        echo "ERROR: Missing gene type!";
        exit();
    }

    if (isset($_POST['input']) && $_POST['input'] != "")
    {
        $input = $_POST['input'];
    }
    elseif (isset($_FILES['uploadedfile']))
    {
        $uploaded_name = $_FILES['uploadedfile']['name'];
        $uploaded_tmp_name = $_FILES['uploadedfile']['tmp_name'];

        $_SESSION['post_data']['uploaded_name'] = $uploaded_name;
        $_SESSION['post_data']['uploaded_tmp_name'] = $uploaded_tmp_name;
    }
    else
    {
        echo "ERROR: Missing gene data!";
        exit();
    }
}
elseif (isset($_SESSION['post_data']))
{
    // assign all the variables
    $species = $_SESSION['post_data']['species'];
    $analysis = $_SESSION['post_data']['analysis'];
    $type = $_SESSION['post_data']['type'];

    if (isset($_SESSION['post_data']['input']))
    {
        $input = $_SESSION['post_data']['input'];
    }
    else
    {
        $uploaded_name = $_SESSION['post_data']['uploaded_name'];
        $uploaded_tmp_name = $_SESSION['post_data']['uploaded_tmp_name'];
    }
}
else
{
    // nothing to do here
}

// Only preceed if we have data
if (isset($_SESSION['post_data']))
{
    $success = FALSE;
    $upload = FALSE;
    # Create a tmp file and save the data for input into R
    if (isset($input))
    {
        $temp_file = tempnam('/tmp', '');
        $fp = fopen($temp_file, "w");
        fwrite($fp, $input);
        fclose($fp);
    }
    elseif (isset($_FILES['uploadedfile']['name']) && $_FILES['uploadedfile']['name'] != "")
    {
        $target_path = "uploads/";

        $target_path = $target_path . basename($uploaded_name);

        if (move_uploaded_file($uploaded_tmp_name, $target_path)) 
        {
            $filename = basename($uploaded_name);
            $success = TRUE;
        }
        else
        {
            echo '<p class="error">There was an error uploading the file, please try again.</p>';
            session_destroy();
            exit();
        }

        if ($success)
        {
            $temp_file = $filename;
            $input = TRUE;
            $upload = TRUE;
        }
    }
    else
    {
        session_destroy();
        header("Location: result.php");
        exit();
    }

    # Get species displayable names
    if ($species == 'hsa')
    {
        $species_display = 'Human';
    }
    elseif ($species == 'mmu')
    {
        $species_display = 'Mus musculus';
    }
    elseif ($species == 'dme')
    {
        $species_display = 'Drosophila';
    }
    else
    {
        echo "ERROR: Unknown species type $species.<br/>";
        exit();
    }

    # Get analysis type displayable names
    if ($analysis == 'genome-based')
    {
        $analysis_display = 'genome-based';
    }
    elseif ($analysis == 'KEGG-based')
    {
        $analysis_display = 'KEGG pathways-based';
    }
    else
    {
        echo "ERROR: Unknown analysis type: $analysis.<br/>";
        exit();
    }

    # process the input data
    if ($analysis && $species && $input && $_SERVER['REQUEST_METHOD'] == 'POST')
    {

        ####################################################################
        # call the R script
        # We need to pass in:
        # 1) the species
        # 2) the analysis method
        # 3) 3 unique filenames
        #
        # We can't actually get anything returned from R, which is a pain.
        # Basically, we create temporary output file that we'll need to delete.
        # 
        # I think the R command should look something like:
        # Rscript analysis.r $species $analysis $file1 $file2 $file3
        ####################################################################
        $pathway_output = tempnam('/tmp', '');
        $hub_output_short = tempnam('/tmp', '');
        $hub_output_full = tempnam('/tmp', '');
        $pathway_output_full = tempnam('/tmp', '');
        $gene_nums_output = tempnam('/tmp', '');

        # These are the production locations
        $path_to_script = "/home/cmrg/raphga/scripts";
        $upload_dir = "/var/www/html/raphga_ucsd_edu/uploads";

        # These are the test locations
        //$path_to_script = "/var/www/html/lys_ucsd_edu/scripts";
        //$upload_dir = "/var/www/html/lys.ucsd.edu/zoe3/uploads";

        if ($upload)
        {
            $r_cmd = "/usr/local/bin/Rscript $path_to_script/analysis.r $species $analysis $type $upload_dir/$temp_file $pathway_output $hub_output_full $hub_output_short $gene_nums_output $pathway_output_full";
        }
        else
        {
            $r_cmd = "/usr/local/bin/Rscript $path_to_script/analysis.r $species $analysis $type $temp_file $pathway_output $hub_output_full $hub_output_short $gene_nums_output $pathway_output_full";
        }
        #$result = array();
        echo exec($r_cmd, $result);
        #echo "<br/>";
        #echo "This is result:<br/>";
        #print_r($result);
        if ($upload)
        {
            unlink("./uploads/$temp_file");
        }
        else
        {
            unlink($temp_file);
        }
        
    }

}

?>
