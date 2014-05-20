<?php

/**
 * CreneauOffTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CreneauOffTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CreneauOffTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CreneauOff');
    }
    
    public function getAllCreneauoff()
    {
    		return $this->createQuery()->orderby('date');
    }
    
    public function getOldCreneauoff()
    {
    		return $this->createQuery()
    					->where('date<date(now())'); // Fonction de mysql
    }
    
    public function isCreneauoffExist($date)
    {
    		$c = $this->createQuery()->where("date=?",$date)->execute();
    		
    		return (count($c) > 0);
    }
}