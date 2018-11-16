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
    <table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80%" cellpadding="3" cellspacing="3">
    <tr>
      <td style="vertical-align:top" width="50%">
      <!--
<p class="font_8"><span style="font-family:comic sans ms,comic-sans-w01-regular,comic-sans-w02-regular,comic-sans-w10-regular,cursive;font-size:13px">RAPHGA is a KEGG pathway-based hub gene analysis application. </span></p>
-->
<p class="main_text">RAPHGA is developed to identify potential hub genes whose expression changes are predicted to induce differential gene expression mediated by multiple pathways.</p>

<p class="main_text">The hub genes were selected by evaluating the possibility of a specific gene appearing in multiple significantly-regulated pathways using a modified Fisher's exact test.</p>

<p class="main_text">Three species are available in RAPHGA: homo sapians, mus musculus, and drosophila melanogaster.</p>

<p class="main_text">Two statistical methods are available: KEGG pathways-based method and genome-based method. KEGG pathways-based method uses genes in KEGG pathway database as the valid genes, while genome-based method uses all input genes as the valid genes regardless whether they are in KEGG pathway database.</p>

<p class="main_text">Entrez gene ID (NCBI_ID) or official symbol is allowed as the input. Please input one gene per line, and don't include duplicates.</p>

<p class="main_text">Hub gene result lists the identified hub genes. The result is a table containing a list of gene id, gene symbol, the total number of pathways containing this gene, the number of significant pathways (p&lt;0.05) containing this gene and a p value. The result webpage shows the hub genes with p<0.05 and #mapped pathways>1. The downloadable csv file contains a full list of hub gene result.</p>

<p class="main_text">Pathway result lists the pathway mapping result. The result table contains a list of pathway id, pathway name, the number of genes in the specific pathway, the number of mapped genes in the pathway, a p value and the detailed gene list. The result webpage shows the pathways with p<0.05. The downloadable csv file contains a full list of pathway result.</p>

<p class="small_centered_text_1">RAPHGA is designed by Zhuohui Gan and impletmented in R.<br/>
The website is implemented by Jeff Van Dorn.<br/>
May 2015<br/>
raphga.help@gmail.com</p>

      </td>
    </tr>
  </table>
  </td>
</tr>
</table>
</body>
</html>
