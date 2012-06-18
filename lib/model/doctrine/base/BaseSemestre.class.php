<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Semestre', 'doctrine');

/**
 * BaseSemestre
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property Doctrine_Collection $AssoMember
 * @property Doctrine_Collection $UserSemestre
 * 
 * @method string              getName()         Returns the current record's "name" value
 * @method Doctrine_Collection getAssoMember()   Returns the current record's "AssoMember" collection
 * @method Doctrine_Collection getUserSemestre() Returns the current record's "UserSemestre" collection
 * @method Semestre            setName()         Sets the current record's "name" value
 * @method Semestre            setAssoMember()   Sets the current record's "AssoMember" collection
 * @method Semestre            setUserSemestre() Sets the current record's "UserSemestre" collection
 * 
 * @package    simde
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSemestre extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('semestre');
        $this->hasColumn('name', 'string', 3, array(
             'type' => 'string',
             'length' => 3,
             ));

        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('AssoMember', array(
             'local' => 'id',
             'foreign' => 'semestre_id'));

        $this->hasMany('UserSemestre', array(
             'local' => 'id',
             'foreign' => 'semestre_id'));
    }
}