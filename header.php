<?php
 $stranka = basename($_SERVER['SCRIPT_NAME']);
?>
<header class="row tm-welcome-section">
    <h2 class="col-12 text-center tm-section-title">

        <?php  if ($stranka == 'index.php' ) {?> Vitajte  <?php }
        elseif ($stranka == 'contact.php' ) { ?> Kontakt  <?php }
        elseif ($stranka == 'about.php' ) { ?> Niečo málo o nás <?php } ?>
    </h2>
    <p class="col-12 text-center">
    <?php  if ($stranka == 'index.php' ) {?> Vitajte na naších stránkach. Reštaurácia je spojením škandinávskeho dizajnu a kulinárskeho umenia. Príjemná atmosféra a prostredie predurčujú k tomu, ako si najlepšie vychutnať skvelé jedlo s vašimi rodinami či obchodnými partnermi. <?php }
    ?>
    </p>
</header>