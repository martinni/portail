<?php use_stylesheet("reservation.css") ?>

<?php include_component("reservation","insideMenu",array("param" => $param)) ?>

<h2>Modification de la salle</h2>
	
<p><?php echo link_to ('<< retour', 'reservation_salles') ?></p>

<form action="<?php echo url_for('reservation_salles_update',array('id' => $id)) ?>" method="post">

	 <?php echo $form ?>
	 <br />
	 <input type="submit" />

</form>
