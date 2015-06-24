<?php
namespace App\Model\Table;

use App\Model\Entity\Group;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Groups Model
 */
class GroupsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('groups');
        $this->displayField('acronym');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Users', [
            'foreignKey' => 'group_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'users_groups'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');
            
        $validator
            ->requirePresence('name_eng', 'create')
            ->notEmpty('name_eng');
            
        $validator
            ->requirePresence('name_fra', 'create')
            ->notEmpty('name_fra');
            
        $validator
            ->requirePresence('acronym', 'create')
            ->notEmpty('acronym');
            
        $validator
            ->allowEmpty('description_eng');
            
        $validator
            ->allowEmpty('description_fra');
            
        $validator
            ->add('is_removed', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_removed', 'create')
            ->notEmpty('is_removed');
            
        $validator
            ->add('created_by', 'valid', ['rule' => 'numeric'])
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');
            
        $validator
            ->add('modified_by', 'valid', ['rule' => 'numeric'])
            ->requirePresence('modified_by', 'create')
            ->notEmpty('modified_by');

        return $validator;
    }
}
