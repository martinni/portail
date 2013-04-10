<?php

/**
 * VoteListeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class VoteListeTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object VoteListeTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('VoteListe');
    }

    public function getCurrentLists() {
      $q = $this->createQuery('q')
                ->select('q.*')
                ->where('q.semestre_id = ?', sfConfig::get('app_portail_current_semestre'));
      return $q;
    }
}