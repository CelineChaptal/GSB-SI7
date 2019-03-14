<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Contr�leur du module VISITEUR de l'application
 */
class C_visiteur extends CI_Controller {
	
	/**
	 * Aiguillage des demandes faites au contr�leur
	 * La fonction _remap est une fonctionnalit� offerte par CI destin�e � remplacer
	 * le comportement habituel de la fonction index. Gr�ce � _remap, on dispose
	 * d'une fonction unique capable d'accepter un nombre variable de param�tres.
	 *
	 * @param $action : l'action demand�e par le visiteur
	 * @param $params : les �ventuels param�tres transmis pour la r�alisation de cette action
	 */
	public function _remap($action, $params = array())
	{
		// chargement du mod�le d'authentification
		$this->load->model('authentif');
		
		// contr�le de la bonne authentification de l'utilisateur
		if (!$this->authentif->estConnecte())
		{
			// l'utilisateur n'est pas authentifi�, on envoie la vue de connexion
			$data = array();
			$this->templates->load('t_connexion', 'v_connexion', $data);
		}
		else
		{
			// Aiguillage selon l'action demand�e
			// CI a trait� l'URL au pr�alable de sorte � toujours renvoyer l'action "index"
			// m�me lorsqu'aucune action n'est exprim�e
			if ($action == 'index')				// index demand� : on active la fonction accueil du mod�le visiteur
			{
				$this->load->model('a_visiteur');
				
				// on n'est pas en mode "modification d'une fiche"
				$this->session->unset_userdata('mois');
				
				$this->a_visiteur->accueil();
			}
			elseif ($action == 'mesFiches')		// mesFiches demand� : on active la fonction mesFiches du mod�le visiteur
			{
				if($this->session->userdata('type') == 'visiteur')
				{
					$this->load->model('a_visiteur');
					
					// on n'est pas en mode "modification d'une fiche"
					$this->session->unset_userdata('mois');
					
					$idVisiteur = $this->session->userdata('idUser');
					$this->a_visiteur->mesFiches($idVisiteur);
				}
				else if($this->session->userdata('type') == 'comptable')
				{
					$this->load->model('a_visiteur');
					
					// on n'est pas en mode "modification d'une fiche"
					$this->session->unset_userdata('mois');
					
					$idVisiteur = $this->session->userdata('idUser');
					$this->a_visiteur->mesFichesV();
				}
			}
			elseif ($action == 'deconnecter')	// deconnecter demand� : on active la fonction deconnecter du mod�le authentif
			{
				$this->load->model('authentif');
				$this->authentif->deconnecter();
			}
			elseif ($action == 'voirFiche')		// voirFiche demand� : on active la fonction voirFiche du mod�le authentif
			{	// TODO : contr�ler la validit� du second param�tre (mois de la fiche � consulter)
				
				$this->load->model('a_visiteur');
				
				// obtention du mois de la fiche � modifier qui doit avoir �t� transmis
				// en second param�tre
				$mois = $params[0];
				// m�morisation du mode modification en cours
				// on m�morise le mois de la fiche en cours de modification
				$this->session->set_userdata('mois', $mois);
				// obtention de l'id utilisateur courant
				$idVisiteur = $this->session->userdata('idUser');
				
				$this->a_visiteur->voirFiche($idVisiteur, $mois);
			}
			elseif ($action == 'modFiche')		// modFiche demand� : on active la fonction modFiche du mod�le authentif
			{	// TODO : contr�ler la validit� du second param�tre (mois de la fiche � modifier)
				
				$this->load->model('a_visiteur');
				
				// obtention du mois de la fiche � modifier qui doit avoir �t� transmis
				// en second param�tre
				$mois = $params[0];
				// m�morisation du mode modification en cours
				// on m�morise le mois de la fiche en cours de modification
				$this->session->set_userdata('mois', $mois);
				// obtention de l'id utilisateur courant
				$idVisiteur = $this->session->userdata('idUser');
				
				$this->a_visiteur->modFiche($idVisiteur, $mois);
			}
			elseif ($action == 'signeFiche') 	// signeFiche demand� : on active la fonction signeFiche du mod�le visiteur ...
			{	// TODO : contr�ler la validit� du second param�tre (mois de la fiche � modifier)
				$this->load->model('a_visiteur');
				
				// obtention du mois de la fiche � signer qui doit avoir �t� transmis
				// en second param�tre
				$mois = $params[0];
				// obtention de l'id utilisateur courant et du mois concern�
				$idVisiteur = $this->session->userdata('idUser');
				$this->a_visiteur->signeFiche($idVisiteur, $mois);
				
				// ... et on revient � mesFiches
				$this->a_visiteur->mesFiches($idVisiteur, "La fiche $mois a �t� sign�e. <br/>Pensez � envoyer vos justificatifs afin qu'elle soit trait�e par le service comptable rapidement.");
			}
			elseif($action == 'validFiche')
			{
				$this->load->model('a_visiteur');
				
				$idVisiteur = $params[0];
				$mois = $params[1];
				
				$this->a_visiteur->validFiche($idVisiteur, $mois);
				
				$this->a_visiteur->mesFichesV("La fiche $mois du visiteur $idVisiteur a �t� valid�e.");
			}
			elseif($action == 'refusFiche')
			{
				$this->load->model('a_visiteur');
				
				$idVisiteur = $params[0];
				$mois = $params[1];
				
				$this->a_visiteur->refusFiche($idVisiteur, $mois);
				
				$this->a_visiteur->mesFichesV("La fiche $mois du visiteur $idVisiteur a �t� refus�e.");
			}
			elseif ($action == 'majForfait') // majFraisForfait demand� : on active la fonction majFraisForfait du mod�le visiteur ...
			{	// TODO : conr�ler que l'obtention des donn�es post�es ne rend pas d'erreurs
				// TODO : dans la dynamique de l'application, contr�ler que l'on vient bien de modFiche
				
				$this->load->model('a_visiteur');
				
				// obtention de l'id du visiteur et du mois concern�
				$idVisiteur = $this->session->userdata('idUser');
				$mois = $this->session->userdata('mois');
				
				// obtention des donn�es post�es
				$lesFrais = $this->input->post('lesFrais');
				
				$this->a_visiteur->majForfait($idVisiteur, $mois, $lesFrais);
				
				// ... et on revient en modification de la fiche
				$this->a_visiteur->modFiche($idVisiteur, $mois, 'Modification(s) des �l�ments forfaitis�s enregistr�e(s) ...');
			}
			elseif ($action == 'ajouteFrais') // ajouteLigneFrais demand� : on active la fonction ajouteLigneFrais du mod�le visiteur ...
			{	// TODO : conr�ler que l'obtention des donn�es post�es ne rend pas d'erreurs
				// TODO : dans la dynamique de l'application, contr�ler que l'on vient bien de modFiche
				
				$this->load->model('a_visiteur');
				
				// obtention de l'id du visiteur et du mois concern�
				$idVisiteur = $this->session->userdata('idUser');
				$mois = $this->session->userdata('mois');
				
				// obtention des donn�es post�es
				$uneLigne = array(
						'dateFrais' => $this->input->post('dateFrais'),
						'libelle' => $this->input->post('libelle'),
						'montant' => $this->input->post('montant')
				);
				
				$message = 'Ligne hors forfait ajout�e...';
				
				$res = $this->a_visiteur->ajouteFrais($idVisiteur, $mois, $uneLigne);
				
				switch ($res) {
					case '-1':
						$message = "Montant invalide";
						break;
					case '-2':
						$message = "Date invalide";
						break;
					case '-3':
						$message = "Il faut remplir tous les champs";
						break;
					default:
						$message = "Okay tu as g�r�";
						break;
				}
				
				// ... et on revient en modification de la fiche
				$this->a_visiteur->modFiche($idVisiteur, $mois, $message);
			}
			elseif ($action == 'supprFrais') // suppprLigneFrais demand� : on active la fonction suppprLigneFrais du mod�le visiteur ...
			{	// TODO : contr�ler la validit� du second param�tre (mois de la fiche � modifier)
				// TODO : dans la dynamique de l'application, contr�ler que l'on vient bien de modFiche
				
				$this->load->model('a_visiteur');
				
				// obtention de l'id du visiteur et du mois concern�
				$idVisiteur = $this->session->userdata('idUser');
				$mois = $this->session->userdata('mois');
				
				// Quel est l'id de la ligne � supprimer : doit avoir �t� transmis en second param�tre
				$idLigneFrais = $params[0];
				$this->a_visiteur->supprLigneFrais($idVisiteur, $mois, $idLigneFrais);
				
				// ... et on revient en modification de la fiche
				$this->a_visiteur->modFiche($idVisiteur, $mois, 'Ligne "Hors forfait" supprim�e ...');
			}
			else								// dans tous les autres cas, on envoie la vue par d�faut pour l'erreur 404
			{
				show_404();
			}
		}
	}
}
