<?php
echo '<table border="0" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:0px solid #000000;color:#000000;width:80%" cellpadding="3" cellspacing="3">';
echo '<tr> <td colspan="4" style="text-align: center">';
echo '<p class="large_text_bold_blue_1">Pathway Result (p<0.05)';
echo '</p></td></tr></table>';
/*****************************************************
***************  PATHWAY RESULTS  *******************
****************************************************/
$pathway_data = array();

if (isset($pathway_output))
{
    $output_fp = fopen($pathway_output, "r");
    if ($output_fp)
    {
      echo '<a href="#TOP">| back to top |</a>';
      echo '<table border="1" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:1px solid #000000;color:#000000;width:80%" cellpadding="5" cellspacing="5">';
      $row_count = 2;
      while (($buffer = fgets($output_fp, 4096)) != false)
      {
          echo '<tr>';
          $text = explode(",", trim($buffer));
          if ($row_count % 2 == 0)
              #$bgcolor = '#E7E7E7';
              $bgcolor = '#DFEAF4';
          else
              $bgcolor = '#FFFFFF';
          if ($row_count == 2 )
          {
              for ($index = 0; $index < 6; $index++)
              {
                  echo '<td align="center" bgcolor="'.$bgcolor.'"><b>'.$text[$index].'</b></td>';
              }
          }
          else
          {
              for ($index = 0; $index < 6; $index++)
              {
                  if ($index == 2 || $index == 3)
                      echo '<td align="center" bgcolor="'.$bgcolor.'">'.$text[$index].'</td>';
                  else
                      echo '<td bgcolor="'.$bgcolor.'">'.$text[$index].'</td>';
              }
          }

          # save this line of data for later access
          $pathway_data[] = $buffer;
          echo '</tr>';
          $row_count += 1;
      }
      # Store the data in a session variable for easy access later
      $_SESSION['post_data']['pathway_data'] = $pathway_data;
      echo '</table>';

      if (!feof($output_fp))
      {
          echo "ERROR: Unexpected fgets() fail\n";
      }
      fclose($output_fp);
      echo '<a href="#TOP">| back to top |</a>';
      unlink($pathway_output);
      
      # I still think I would prefer to serialize the data
      # when passing it to the new page, so I'll leave these
      # lines for now, but I wasn't able to get them to work.
      # Also, if I find that the site is slowing due to the 
      # passing over the arrays between pages, I think just 
      # creating tmp files with a known prefix on the fly 
      # would be the other solution with a crontab deleting
      # the files every so often
      #echo '<input type="hidden" name="pathway_data" value="'.json_encode($pathway_data).'">';
      #echo '<input type="hidden" name="hub_data" value="'.json_encode($hub_data).'">';
    }
}
elseif (isset($_SESSION['post_data']['pathway_data']))
{
    echo '<a href="#TOP">| back to top |</a>';
    echo '<table border="1" align="center" style="background-color:#FFFFFF;border-collapse:collapse;border:1px solid #000000;color:#000000;width:80%" cellpadding="5" cellspacing="5">';
    $row_count = 2;
    foreach($_SESSION['post_data']['pathway_data'] as $value)
    {
      echo '<tr>';
      $text = explode(",", trim($value));
      if ($row_count % 2 == 0)
          #$bgcolor = '#E7E7E7';
          $bgcolor = '#DFEAF4';
      else
          $bgcolor = '#FFFFFF';
      if ($row_count == 2 )
      {
          for ($index = 0; $index < 6; $index++)
          {
              echo '<td align="center" bgcolor="'.$bgcolor.'"><b>'.$text[$index].'</b></td>';
          }
      }
      else
      {
          for ($index = 0; $index < 6; $index++)
          {
              if ($index == 2 || $index == 3)
                  echo '<td align="center" bgcolor="'.$bgcolor.'">'.$text[$index].'</td>';
              else
                  echo '<td bgcolor="'.$bgcolor.'">'.$text[$index].'</td>';
          }
      }

      echo '</tr>';
      $row_count += 1;
    }
    echo '</table>';
    echo '<a href="#TOP">| back to top |</a>';
}
else
{
    echo "Sorry, no Pathway data to display. Please submit data on the analysis page if you forgot to input your gene list.<br/>If you have submitted a list of genes in the corrected format, then there are no significant pathways for your input genes.";
}

#**************  FULL PATHWAY FILE  *****************
if (isset($pathway_output_full))
{
    $output_fp = fopen($pathway_output_full, "r");
    $pathway_data = array();
    if ($output_fp)
    {
        while (($buffer = fgets($output_fp, 4096)) != false)
        {
            $pathway_data[] = $buffer;
            $_SESSION['post_data']['pathway_data_full'] = $buffer;
        }
        $_SESSION['post_data']['pathway_data_full'] = $pathway_data;

        if (!feof($output_fp))
        {
            echo "ERROR: Unexpected fgets() fail\n";
        }
        fclose($output_fp);
        unlink($pathway_output_full);
    }
}


?> 
