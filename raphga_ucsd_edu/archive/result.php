<?php
session_start();
include("verify_data.php")
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>KEGG Pathway-Based Gene Analysis</title>
<link rel="stylesheet" href="./styles/managesheet.css" />
</head>

<body>
<table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:1000px" cellpadding="3" cellspacing="3">
<tr>
  <td colspan="5" style="vertical-align: top"><a id="TOP"><img src="images/logo.png"></a><img src="images/page_title.png"></td>
</tr>
<tr>
  <td colspan="5"><img src="images/spacer_1x1.png" height="1" width="600">
                  <a href="index.php"><img src="images/analysis.png"></a>
                  <img src="images/spacer_1x1.png" height="1" width="15">
                  <a href="result.php"><img src="images/result.png"></a>
                  <img src="images/spacer_1x1.png" height="1" width="15">
                  <a href="help.php"><img src="images/help.png"></a>
  </td>
</tr>
<tr>
  <td colspan="5"><img src="images/horiz_line.png"></td>
</tr>
<!-- ########## The beginning of the main area ############ -->
<tr>
  <td>
  <?php
  if(!isset($_SESSION['post_data']))
  {
      echo '<table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80%" cellpadding="3" cellspacing="3">';
      echo '<tr> <td colspan="4" style="text-align: center">'; 
      echo '<p class="large_text_bold_blue_1">No input data detected.  Please submit your data on the analysis page.</p>';
      exit();
  }
  ?>
    <!-- ############## THIS IS THE INPUT SUMMARY AREA ###################### -->
    <?php
    /***********************************************
     ********** GET GENE SUMMARY DATA **************
     ***********************************************/
    if (isset($gene_nums_output))
    {
        $output_fp = fopen($gene_nums_output, "r");
        if ($output_fp)
        {
            while (($buffer = fgets($output_fp, 4096)) != false)
            {
                $text = explode(",", trim($buffer));
            }
            $_SESSION['post_data']['gene_text'] = $text;

            if (!feof($output_fp))
            {
                echo "ERROR: Unexpected fgets() fail\n";
            }
            fclose($output_fp);
            unlink($gene_nums_output);
        }
    }
    elseif (isset($_SESSION['post_data']['gene_text']))
    {
        $text = $_SESSION['post_data']['gene_text'];
    }
    ?>
    <table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80%" cellpadding="3" cellspacing="3">
    <tr>
      <td colspan="5" style="text-align: center">
        <p class="large_text_bold_blue_1">Input Summary</p>
      </td>
    </tr>
    <tr>
      <td>
      <span class="result_text_bold">
      species:</span> <?php echo $species_display;?>
      </td>
      <td>
      <span class="result_text_bold">analysis method:</span> <?php echo $analysis_display;?>
      </td>
      <td>
      <span class="result_text_bold"># total input gene:</span> <?php echo $text[0];?>
      </td>
      <td>
      <span class="result_text_bold"># identified:</span> <?php echo $text[1];?>
      </td>
      <td>
      <span class="result_text_bold">
      # in KEGG: </span><?php echo $text[2];?>
      </td>
    </tr>
    <tr>
    <td colspan="5">
    <img src="images/dashed_line.png">
    </td>
    </tr>
    </table>
    <!-- ############## THIS IS THE END OF INPUT SUMMARY AREA ###################### -->

    <!-- ############## THIS IS THE DATA DOWNLOAD AREA ###################### -->
    <form name="save_file" method="post" action="save_data.php" target="_blank">
    <table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80%" cellpadding="3" cellspacing="3">
    <tr>
      <td colspan="2" style="text-align: center">
        <span class="large_text_bold_blue_1">
        Result Summary
        </span>
      </td>
    </tr>
    <tr>
      <td style="text-align: center">
      <span class="result_text_bold">
      Download entire pathway result here
      </span>
      </td>
      <td style="text-align: center">
      <span class="result_text_bold">
      Download entire hub gene result here
      </span>
      </td>
    </tr>
    <tr>
      <td style="text-align: center">
      <input type="submit" name="pathway_results" value="Download Results">
      </td>
      <td style="text-align: center">
      <input type="submit" name="hub_results" value="Download Results">
      </td>
    </tr>
    <tr>
    <td colspan="5">
    <img src="images/horiz_line_2.png">
    </td>
    </tr>
    </table>
    <!-- ############## THIS IS THE END OF DATA DOWNLOAD AREA ###################### -->

    <!-- ############## THIS IS THE HUB RESULT DISPLAY AREA ###################### -->
    <?php include("display_hub_results.php");?>
    <!-- ############## THIS IS THE END OF HUB RESULT DISPLAY AREA ###################### -->

    <!-- ############## THIS IS THE PATHWAY DISPLAY AREA ###################### -->
    <?php include("display_pathway_results.php");?>
    <!-- ############## THIS IS THE END OF PATHWAY SUMMARY AREA ###################### -->
    </form> <!-- closing the form for the data download area -->
  </td>
</tr>
</table>
</body>
</html>
