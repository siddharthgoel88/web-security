<?php
    $vCard = "BEGIN:VCARD
    VERSION:3.0
    N:Gump;Forrest
    FN:Forrest Gump
    ORG:Bubba Gump Shrimp Co.
    TITLE:Shrimp Man
    PHOTO;VALUE=URL;TYPE=GIF:http://www.example.com/dir_photos/my_photo.gif
    TEL;TYPE=WORK,VOICE:(111) 555-1212
    TEL;TYPE=HOME,VOICE:(404) 555-1212
    ADR;TYPE=WORK:;;100 Waters Edge;Baytown;LA;30314;United States of America
    LABEL;TYPE=WORK:100 Waters Edge\nBaytown, LA 30314\nUnited States of America
    ADR;TYPE=HOME:;;42 Plantation St.;Baytown;LA;30314;United States of America
    LABEL;TYPE=HOME:42 Plantation St.\nBaytown, LA 30314\nUnited States of America
    EMAIL;TYPE=PREF,INTERNET:forrestgump@example.com
    REV:20080424T195243Z
    END:VCARD";

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"vCard.vcf\"");
    echo $vCard;
?>