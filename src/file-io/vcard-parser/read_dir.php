<?php
require_once('../vcard-parser/vCard.php');
/**
* Change the path to your folder.
* This must be the full path from the root of your
* web space. If you're not sure what it is, ask your host.
*
* Name this file index.php and place in the directory.
*/
    // Define the full path to your folder from root
    $path = "/var/www/temp/vcard_to_import";
  
    // Open the folder
    $dir_handle = @opendir($path) or die("Unable to open $path");
  
    // Loop through the files
    while ($file = readdir($dir_handle)) {
  
    if($file == "." || $file == ".." || $file == ".vcf" )
  
        continue;
        echo $file;
        echo '<br/>';
        $vCard = new vCard(
                        '/var/www/temp/vcard_to_import/13.vcf', // Path to vCard file
                        false, // Raw vCard text, can be used instead of a file
                        array( // Option array
                            // This lets you get single values for elements that could contain multiple values but have only one value.
                            //  This defaults to false so every value that could have multiple values is returned as array.
                            'Collapse' => false
                        )
                    );

                    OutputvCard($vCard);
  
    }
    // Close
    closedir($dir_handle);


    function OutputvCard(vCard $vCard)
    {
  //      echo '<h2>'.$vCard -> FN[0].'</h2>';
$connect = mysql_connect("localhost","root","student");
mysql_select_db("sq_mail",$connect); //select the table

        foreach ($vCard -> N as $Name)
        {
            echo '<h3>Name: '.$Name['FirstName'].' '.$Name['FirstName'].'</h3>';
            mysql_query("INSERT INTO contact (contact_first, contact_last, contact_email) VALUES
                                (
                                    '".addslashes($Name['FirstName'])."',
                                    '".addslashes($Name['FirstName'])."',
                                    '".addslashes($Name['FirstName'])."'
                                )
                            ");
        }



        /*

        foreach ($vCard -> ORG as $Organization)
        {
            echo '<h3>Organization: '.$Organization['Name'].
                ($Organization['Unit1'] || $Organization['Unit2'] ?
                    ' ('.implode(', ', array($Organization['Unit1'], $Organization['Unit2'])).')' :
                    ''
                ).'</h3>';
        }

        if ($vCard -> TEL)
        {
            echo '<p><h4>Phone</h4>';
            foreach ($vCard -> TEL as $Tel)
            {
                if (is_scalar($Tel))
                {
                    echo $Tel.'<br />';
                }
                else
                {
                    echo $Tel['Value'].' ('.implode(', ', $Tel['Type']).')<br />';
                }
            }
            echo '</p>';
        }

        if ($vCard -> EMAIL)
        {
            echo '<p><h4>Email</h4>';
            foreach ($vCard -> EMAIL as $Email)
            {
                if (is_scalar($Email))
                {
                    echo $Email;
                }
                else
                {
                    echo $Email['Value'].' ('.implode(', ', $Email['Type']).')<br />';
                }
            }
            echo '</p>';
        }

        if ($vCard -> URL)
        {
            echo '<p><h4>URL</h4>';
            foreach ($vCard -> URL as $URL)
            {
                if (is_scalar($URL))
                {
                    echo $URL.'<br />';
                }
                else
                {
                    echo $URL['Value'].'<br />';
                }
            }
            echo '</p>';
        }

        if ($vCard -> IMPP)
        {
            echo '<p><h4>Instant messaging</h4>';
            foreach ($vCard -> IMPP as $IMPP)
            {
                if (is_scalar($IMPP))
                {
                    echo $IMPP.'<br />';
                }
                else
                {
                    echo $IMPP['Value'].'<br/ >';
                }
            }
            echo '</p>';
        }

        if ($vCard -> ADR)
        {
            foreach ($vCard -> ADR as $Address)
            {
                echo '<p><h4>Address ('.implode(', ', $Address['Type']).')</h4>';
                echo 'Street address: <strong>'.($Address['StreetAddress'] ? $Address['StreetAddress'] : '-').'</strong><br />'.
                    'PO Box: <strong>'.($Address['POBox'] ? $Address['POBox'] : '-').'</strong><br />'.
                    'Extended address: <strong>'.($Address['ExtendedAddress'] ? $Address['ExtendedAddress'] : '-').'</strong><br />'.
                    'Locality: <strong>'.($Address['Locality'] ? $Address['Locality'] : '-').'</strong><br />'.
                    'Region: <strong>'.($Address['Region'] ? $Address['Region'] : '-').'</strong><br />'.
                    'ZIP/Post code: <strong>'.($Address['PostalCode'] ? $Address['PostalCode'] : '-').'</strong><br />'.
                    'Country: <strong>'.($Address['Country'] ? $Address['Country'] : '-').'</strong>';
            }
            echo '</p>';
        }

        if ($vCard -> AGENT)
        {
            echo '<h4>Agents</h4>';
            foreach ($vCard -> AGENT as $Agent)
            {
                if (is_scalar($Agent))
                {
                    echo '<div class="Agent">'.$Agent.'</div>';
                }
                elseif (is_a($Agent, 'vCard'))
                {
                    echo '<div class="Agent">';
                    OutputvCard($Agent);
                    echo '</div>';
                }
            }
        }*/
    }
?>