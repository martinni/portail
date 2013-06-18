<?php

/**
 * InfoJob actions.
 *
 * @package    simde
 * @subpackage infojob
 * @author     Soukaina B, Maureen C, Yoan T
 */
class infojobActions extends sfActions {

  public function executeIndex(sfWebRequest $request)
  {
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->annonce = $this->getRoute()->getObject();
  }

  public function executeOffres(sfWebRequest $request)
  { 
	  $this->filters = new InfoJobOffreFormFilter();
    if($request->getMethod() == sfRequest::POST) {
      $this->filters->bind($request->getParameter($this->filters->getName()));
      if($this->filters->isValid())
      	$query = $this->filters->buildQuery($this->filters->getValues());
    }
    else {
      $query = InfoJobOffreTable::getInstance()->getLastOffreList();
    }
    $this->annonces = $query->execute();

  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new InfoJobOffreForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->form = new InfoJobOffreForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $annonces = InfoJobOffreTable::getInstance()->getOffreByEmailKey($request->getParameter('key'))->execute();
    // TODO Quand même afficher les annonces archivées, signalées ou expirées, mais ne pas permettre de les modifier.
    $this->forward404Unless(count($annonces), sprintf('L\'annonce n\'existe pas (%s).', $request->getParameter('key')));
    $this->form = new InfoJobOffreForm($annonces[0]);
    // Si l'annonce n'a pas encore été validée.
    if($annonces[0]->getValidationDate() == NULL)
      $this->getUser()->setFlash('warning', 'Cette annonce n\'a pas encore été validée. Veuillez suivre le lien envoyé par email pour que l\'annonce soit publiée.');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $annonces = InfoJobOffreTable::getInstance()->getOffreByEmailKey($request->getParameter('key'))->execute();
    $this->forward404Unless(count($annonces), sprintf('L\'annonce n\'existe pas (%s).', $request->getParameter('key')));
    $this->form = new InfoJobOffreForm($annonce[0]);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $annonces = InfoJobOffreTable::getInstance()->getOffreByEmailKey($request->getParameter('key'))->execute();
    $this->forward404Unless(count($annonces), sprintf('L\'annonce n\'existe pas (%s).', $request->getParameter('key')));
    $annonces[0]->delete();
    $this->redirect('infojob/index');
  }
  
  public function executeEmail(sfWebRequest $request)
  {
    // TODO
  }

  public function executeSignal(sfWebRequest $request)
  {
    $this->form = new InfoJobSignalementForm();
    $this->form->setDefault('offre_id', $request->getParameter('id'));
    $this->form->getObject()->setOffreId($request->getParameter('id'));
  }


  public function executeSignaldo(sfWebRequest $request)
  { 
  	$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->form = new InfoJobSignalementForm();
    $this->processFormSignalement($request, $this->form);
    $this->setTemplate('signal');
  }
  
  
  public function executeMonprofil(sfWebRequest $request)
  {
    $this->form = new InfoJobAbonnementCategorieForm();
 	//   $this->processForm($request, $this->form);

    //mettre la partie abonnement disponibilité du formulaire
	  $this->form2 = new InfoJobAbonnementDisponibiliteForm();
    //$this->processForm($request, $this->form2);

     /*
    $query = Doctrine_Core::getTable('InfoJobABonnementCategorie')
            ->createQuery('a')
            ->limit(5)
            ->orderBy('a.created_at DESC');
        $this->annonces = $query->execute();
      /*
    $query2= Doctrine_Core::getTable('InfoJobABonnementDisponibilite')
            ->createQuery('a')
            ->limit(5)
            ->orderBy('a.created_at DESC');
        $this->annonces = $query2->execute();
    */
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid())
    {
      $isNew = $form->getObject()->isNew();
      if($isNew) {
        // TODO Vérifier que la clé générée est unique.
        $form->getObject()->setEmailkey(md5(microtime().rand()));
        $form->getObject()->setValidationkey(md5(microtime().rand()));
        if($this->getUser()->isAuthenticated())
          $form->getObject()->setUserId($this->getUser()->getGuardUser()->getId());
        // Ajouter la date de création et de mise à jour.
        $form->getObject()->setDateTimeObject('created_at', new DateTime());
      	$form->getObject()->setDateTimeObject('updated_at', new DateTime());
      }
      // TODO Faire en sorte que 'expiration_date' soit mis à NULL si aucune valeur n'est renseignée (== 0000-00-00 00:00:00).
      $annonce = $form->save();
      if($isNew) {
        // Si l'annonce vient d'être créée.
        $this->getUser()->setFlash('success', 'L\'annonce a bien été créée. Vous allez recevoir prochainement un email pour valider sa publication.');
        // Envoyer l'email de validation.
        $this->sendValidationEmail($annonce);
      }
      else
        $this->getUser()->setFlash('success', 'L\'annonce a bien été mise à jour.');
      $this->redirect('infojob_offre_edit', array('key' => $annonce->getEmailkey()));
    }
  }
  
  protected function processFormSignalement(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if($form->isValid())
    {
      // Ajouter la date de création et de mise à jour.
      $form->getObject()->setDateTimeObject('created_at', new DateTime());
    	$form->getObject()->setDateTimeObject('updated_at', new DateTime());
      $signalement = $form->save();
      $this->getUser()->setFlash('success','L\'annonce a bien été signalée et sera vérifiée prochainement par le BDE-UTC.');
      $this->redirect('infojob_offre_show', array('id' => $signalement->getOffreId()));
    }
  }

  protected function sendValidationEmail($annonce) {
    // TODO
  }
}

