<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity.
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'id' => true,
        'first_name' => true,
        'last_name' => true,
        'email' => true,
        'username' => true,
        'ad_groups' => true,
        'is_deactivated' => true,
        'created_by' => true,
        'modified_by' => true,
        'demo' => true,
        'groups' => true,
    ];
}
