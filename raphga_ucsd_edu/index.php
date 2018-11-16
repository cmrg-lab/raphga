<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>KEGG Pathway-Based Gene Analysis</title>
<link rel="stylesheet" href="./styles/managesheet.css" />
</head>

<body>
<!--
<table border="1" align="center" style="background-color:#DFEAF4;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80" cellpadding="3" cellspacing="3">
-->
<table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:1000px" cellpadding="3" cellspacing="3">
<tr>
  <td colspan="5" style="vertical-align: top"><img src="images/logo.png"><img src="images/page_title.png"></td>
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
  <td colspan="5">
    <!-- ############## THIS IS THE ANALYSIS STEPS AREA ###################### -->
    <!--
    <table border="1" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80%" cellpadding="3" cellspacing="3">
    -->
    <table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:90%" cellpadding="3" cellspacing="3">
    <tr>
      <td style="vertical-align:top" width="50%">
        <p class="large_text_bold_underline_1">Analysis Steps</p>
        <!--
        <ul class="font_2" style="font-family: comic sans ms,comic-sans-w01-regular,comic-sans-w02-regular,comic-sans-w10-regular,cursive; font-size: 24px;">
        -->
        <ul style="line-height:80%">
          <li>
          <p class="main_text">select species</p>
          </li>
          <li>
          <p class="main_text">select analysis method</p>
          </li>
          <li>
          <p class="main_text">select input type</p>
          </li>
          <li>
          <p class="main_text">load your input</p>
          </li>
          <li>
          <p class="main_text">submit analysis</p>
          </li>
        </ul>

        <p>&nbsp;</p>
        <p class="large_text_bold_underline_1">Analysis Method</p>
        <p class="main_text_italics">KEGG pathways-based:</p>
        <p class="main_text_shifted">The genes in KEGG pathways are considered as the background genes and the input genes in KEGG pathways are considered as the input genes for statistic analysis. The genes in KEGG pathways are considered as the background genes and the input genes in KEGG pathways are considered as the input genes for statistical analysis.</p>
        <p class="main_text_italics">genome-based:</p>
        <p class="main_text_shifted">All genes in the genome as the background genes and all input genes are considered as the input for statistical analysis.</p>

        <p>&nbsp;</p>
        <p class="large_text_bold_underline_1">Load Genes</p>
        <p class="main_text_italics">What to load:</p>
        <p class="main_text_shifted">You can input either Entrez gene ID (NCBI_ID) or the official gene name (Symbol) as the input genes.</p>
        <p class="main_text_italics">How to load:</p>
        <p class="main_text_shifted">The genes can be loaded by pasting into the 
        input text box (one gene per line) directly or upload a *.txt file 
        containing NCBI IDs or gene symbols.</p>
      </td>
  <!-- ############################# HORIZONTAL SPACING LINE ############################# -->
  <td>
    <img src="images/spacer_1x1.png" width="30" height="1">
  </td>
  <!-- ############################# VERTICAL LINE ############################# -->
  <td style="vertical-align:top">
    <img src="images/grey_vert_line.png" width="3" height="750">
  </td>
  <!-- ############################# HORIZONTAL SPACING LINE ############################# -->
  <td>
    <img src="images/spacer_1x1.png" width="30" height="1">
  </td>
  <!-- ############################# INPUT AREA ############################# -->
  <td style="vertical-align:top">
    <!--
    <table border="0" align="center" style="background-color:#DFEAF4;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80%" cellpadding="3" cellspacing="3">
    -->
    <form enctype="multipart/form-data" name="create_entry" method="post" action="result.php">
    <table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80%" cellpadding="3" cellspacing="3">
    <tr>
    <td align="left">
      <p class="large_text_bold_blue_1">Species:</p>
    </td>
    <td>
      <select name="species">
      <?php
      // See if things have previously been set
      if (isset($_SESSION['post_data']['species']))
      {
          if ($_SESSION['post_data']['species'] == 'hsa')
          {
              echo '<option value="hsa" selected="selected">Homo sapians</option>';
              echo '<option value="mmu">Mus musculus</option>';
              echo '<option value="dme">Drosophila melanogaster</option>';
          }
          elseif ($_SESSION['post_data']['species'] == 'mmu')
          {
              echo '<option value="hsa">Homo sapians</option>';
              echo '<option value="mmu" selected="selected">Mus musculus</option>';
              echo '<option value="dme">Drosophila melanogaster</option>';
          }
          else
          {
              // must be dme
              echo '<option value="hsa">Homo sapians</option>';
              echo '<option value="mmu">Mus musculus</option>';
              echo '<option value="dme" selected="selected">Drosophila melanogaster</option>';
          }
      }
      else
      {
          echo '<option value="hsa">Homo sapians</option>';
          echo '<option value="mmu">Mus musculus</option>';
          echo '<option value="dme">Drosophila melanogaster</option>';
      }
      ?>
      </select>
    </td>
    </tr>
    <tr>
    <td align="left">
      <p class="large_text_bold_blue_1">Analysis Method:</p>
    </td>
    <td>
      <select name="analysis">
      <?php
        if (isset($_SESSION['post_data']['analysis']))
        {
            if ($_SESSION['post_data']['analysis'] == 'KEGG-based')
            {
                echo '<option value="KEGG-based" selected="selected">KEGG pathways-based</option>';
                echo '<option value="genome-based">genome-based</option>';
            }
            else
            {
                echo '<option value="KEGG-based">KEGG pathways-based</option>';
                echo '<option value="genome-based" selected="selected">genome-based</option>';
            }
        }
        else
        {
            echo '<option value="KEGG-based">KEGG pathways-based</option>';
            echo '<option value="genome-based">genome-based</option>';
        }
      ?>
      </select>
    </td>
    </tr>
    <tr>
    <td align="left"> 
      <p class="large_text_bold_blue_1">Input Type:</p>
    </td>
    <td>
        <select name="type">
        <?php
        if (isset($_SESSION['post_data']['type']))
        {
            if ($_SESSION['post_data']['type'] == 'NCBI_id')
            {
                echo '<option value="NCBI_id" selected="selected">NCBI ID</option>';
                echo '<option value="symbol">Symbol</option>';
            }
            else
            {
                echo '<option value="NCBI_id">NCBI ID</option>';
                echo '<option value="symbol" selected="selected">Symbol</option>';
            }
        }
        else
        {
            echo '<option value="NCBI_id">NCBI ID</option>';
            echo '<option value="symbol">Symbol</option>';
        }
        ?>
        </select>
    </td>
    </tr>
    <tr>
    <td colspan="2" align="center">
      <p class="large_text_bold_blue_1">Load your list:</p>
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
        <input type="file" name="uploadedfile">
    </td>
    </tr>
    <tr>
    <td colspan="2" align="center">
    <p class="main_text_centered">Or input your list below...</p>
    <?php
    if (isset($_SESSION['post_data']['input']))
    {
        echo '<textarea name="input" rows="20" cols="40">'.$_SESSION["post_data"]["input"].'</textarea>';
    }
    else
    {
        echo '<textarea name="input" rows="20" cols="40"></textarea>';
    }
    ?>
    </td>
    </tr>
    <tr>
      <td align="center"><input type="submit" name="clear" value="Clear data"></td>
      <td align="center"><input type="submit" name="submit" value="Submit"></td>
    </tr>
    </table>
    </form>

  </td>
  </tr>
  </table>
  </td>
</tr>
</table>
</body>
</html>
