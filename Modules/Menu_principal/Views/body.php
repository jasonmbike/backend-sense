<?php

echo view('\Modules\Menu_principal\Views\header', $datalibrary);
echo view('\Modules\Menu_principal\Views\index', $datalibrary);


if (is_array($datalibrary['vista'])) {
    foreach ($datalibrary['vista'] as $vista) {
        echo view($vista);
    }
}


echo view('\Modules\Menu_principal\Views\footer', $datalibrary);

?>
 
 