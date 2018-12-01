<?php
echo '<table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80%" cellpadding="3" cellspacing="3">';
echo '<tr> <td colspan="4" style="text-align: center">';
echo '<p class="large_text_bold_blue_1">Hub Gene Result (p<0.05)';
echo '</p></td></tr></table>';
/*****************************************************
*        READ IN AND DISPLAY THE RESULTS
*          FIRST THE HUB GENE RESULTS
****************************************************/
if(isset($hub_output_short))
{
    $hub_data_short = array();
    $wrote_data = FALSE;
    $output_fp = fopen($hub_output_short, "r");
    if ($output_fp)
    {
      echo '<table border="1" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:1px solid #000000;color:#000000;width:80%" cellpadding="5" cellspacing="5">';
      $row_count = 2;
      while (($buffer = fgets($output_fp, 4096)) != false)
      {
          $wrote_data = TRUE;
          echo '<tr>';
          $text = explode(",", trim($buffer));
          if ($row_count % 2 == 0)
              $bgcolor = '#DFEAF4';
          else
              $bgcolor = '#FFFFFF';
          if ($row_count == 2 )
          {
              for ($index = 0; $index < 5; $index++)
              {
                  echo '<td align="center" bgcolor="'.$bgcolor.'"><b>'.$text[$index].'</b></td>';
              }
          }
          else
          {
              for ($index = 0; $index < 5; $index++)
              {
                  echo '<td align="center" bgcolor="'.$bgcolor.'">'.$text[$index].'</td>';
              }
          }

          echo '</tr>';
          $hub_data_short[] = $buffer;
          $row_count += 1;
      }
      $_SESSION['post_data']['hub_data_short'] = $hub_data_short;
      $_SESSION['post_data']['wrote_hub_data'] = $wrote_data;
      echo '</table>';

      if ($wrote_data == FALSE)
      {
          echo '<p class="result_text_bold" style="text-align:center">No hub gene results to display. Please check your input species and data type settings.</p>';
      }

      if (!feof($output_fp))
      {
          echo "ERROR: Unexpected fgets() fail\n";
      }
      fclose($output_fp);
      unlink($hub_output_short);
    }
}
elseif (isset($_SESSION['post_data']['hub_data_short']))
{
    echo '<table border="1" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:1px solid #000000;color:#000000;width:80%" cellpadding="5" cellspacing="5">';
    $row_count = 2;
    foreach ($_SESSION['post_data']['hub_data_short'] as $value)
    {
      echo '<tr>';
      $text = explode(",", trim($value));
      if ($row_count % 2 == 0)
          $bgcolor = '#DFEAF4';
      else
          $bgcolor = '#FFFFFF';
      if ($row_count == 2 )
      {
          for ($index = 0; $index < 5; $index++)
          {
              echo '<td align="center" bgcolor="'.$bgcolor.'"><b>'.$text[$index].'</b></td>';
          }
      }
      else
      {
          for ($index = 0; $index < 5; $index++)
          {
              echo '<td align="center" bgcolor="'.$bgcolor.'">'.$text[$index].'</td>';
          }
      }

      echo '</tr>';
      $row_count += 1;
    }
    echo '</table>';
    if ($_SESSION['post_data']['wrote_hub_data'] == FALSE);
    {
        echo '<p class="result_text_bold" style="text-align:center">No hub gene results to display. Please check your input species and data type settings.</p>';
    }
}
else
{
    echo "Sorry, no Hub gene data to display. Please submit data on the analysis page.";
}

#**************  FULL HUB GENE FILE  *****************
if (isset($hub_output_full))
{
    $output_fp = fopen($hub_output_full, "r");
    $hub_data = array();
    if ($output_fp)
    {
        while (($buffer = fgets($output_fp, 4096)) != false)
        {
            $hub_data[] = $buffer;
            $_SESSION['post_data']['hub_data_full'] = $buffer;
        }
        $_SESSION['post_data']['hub_data_full'] = $hub_data;

        if (!feof($output_fp))
        {
            echo "ERROR: Unexpected fgets() fail\n";
        }
        fclose($output_fp);
        unlink($hub_output_full);
    }
}
?>
