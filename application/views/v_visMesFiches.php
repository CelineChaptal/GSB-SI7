<?php
$this->load->helper('url');
?>
<div id="contenu">
	<h2>Liste de mes fiches de frais</h2>
	 	
	<?php if(!empty($notify)) echo '<p id="notify" >'.$notify.'</p>';?>
	 
	<table class="listeLegere">
		<thead>
			<tr>
				<?php  if($this->session->userData('type') == "comptable")
				{ ?>
				<th >Visiteur</th> <?php
				} ?>
				<th >Mois</th>
				<th >Etat</th>  
				<th >Montant</th>  
				<th >Date modif.</th>  
				<th  colspan="4">Actions</th>              
			</tr>
		</thead>
		<tbody>
          
		<?php    
			foreach( $mesFiches as $uneFiche) 
			{
				if($this->session->userData('type') == "visiteur")
				{
					$modLink = '';
					$signeLink = '';

					if ($uneFiche['idEtat'] == 'CR') {
						$modLink = anchor('c_visiteur/modFiche/'.$uneFiche['mois'], 'modifier',  'title="Modifier la fiche"');
						$signeLink = anchor('c_visiteur/signeFiche/'.$uneFiche['mois'], 'signer',  'title="Signer la fiche"  onclick="return confirm(\'Voulez-vous vraiment signer cette fiche ?\');"');
					}
					
					echo 
					'<tr>
						<td class="date">'.anchor('c_visiteur/voirFiche/'.$uneFiche['mois'], $uneFiche['mois'],  'title="Consulter la fiche"').'</td>
						<td class="libelle">'.$uneFiche['libelle'].'</td>
						<td class="montant">'.$uneFiche['montantValide'].'</td>
						<td class="date">'.$uneFiche['dateModif'].'</td>
						<td class="action">'.$modLink.'</td>
						<td class="action">'.$signeLink.'</td>
					</tr>';
				}
				else if($this->session->userData('type') == "comptable")
				{
					$modLink = '';
					$signeLink = '';

					if ($uneFiche['idEtat'] == 'CL') {
						$modLink = anchor('c_visiteur/validFiche/'.$uneFiche['idVisiteur'].'/'.$uneFiche['mois'], 'valider',  'title="Valider la fiche" onclick="return confirm(\'Voulez-vous vraiment valider cette fiche ?\');"');
						$signeLink = anchor('c_visiteur/refusFiche/'.$uneFiche['idVisiteur'].'/'.$uneFiche['mois'], 'refuser',  'title="Refuser la fiche"  onclick="return confirm(\'Voulez-vous vraiment refuser cette fiche ?\');"');
					}
					
					echo 
					'<tr>
						<td class="id">'.$uneFiche['idVisiteur'].'</td>
						<td class="date">'.anchor('c_visiteur/voirFiche/'.$uneFiche['mois'], $uneFiche['mois'],  'title="Consulter la fiche"').'</td>
						<td class="libelle">'.$uneFiche['libelle'].'</td>
						<td class="montant">'.$uneFiche['montantValide'].'</td>
						<td class="date">'.$uneFiche['dateModif'].'</td>
						<td class="action">'.$modLink.'</td>
						<td class="action">'.$signeLink.'</td>
					</tr>';
				}
			}
		?>	  
		</tbody>
    </table>

</div>