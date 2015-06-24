<?php
namespace WetKit\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\Core\Configure;
use Cake;

class BilingualFieldsBehavior extends Behavior
{

    protected $_defaultConfig = [
        'abbr-en' => 'en',
        'abbr-fr' => 'fr',
    ];

    /**
     * @param Model $model
     * @param array $config
     *
     * NOTE: There is also a bilingual field check for form data
     *       in AppController->formBilingualField()
     */
    function initialize(array $config)
    {
        /*
        $config = array_merge($this->config(), $config);

        debug($this->_table->registryAlias());
        $entity = $this->_table->newEntity();

        debug($this->_table->schema()->columns());
        debug($entity->_properties);

        $fields = array();
        $columns = $this->_table->schema()->columns();
        foreach ($columns as $field_name) {
            // Looking for fields ending in _eng
            if (substr($field_name, -3) == '_'.$config['abbr-en']) {
                // Looking if French variant also exists
                if (in_array(substr($field_name, 0, strlen($field_name) - 3) . '_'.$config['abbr-fr'], $columns)) {
                    $fields[] = substr($field_name, 0, strlen($field_name) - 3);
                } // If both exists add to bilingual fields list
            }
        }

        debug($fields);

        foreach ($fields as $key => $name) {
            $virtualFields = $entity->virtualProperties();
            if (!is_array($virtualFields)) {
                $virtualFields = array();
            }

            if (!in_array($name, $virtualFields)) {
                $virtualField = 'name_'.Configure::read('wetkit.lang');
                //$virtualFields[$name] = $virtualField;
                //$model->virtualFields = $virtualFields;
                $entity->virtualProperties(['double_name']);
            }
        }

        debug($entity->virtualProperties());
        */

    }



    public function beforeFind(Cake\Event\Event $event, Cake\ORM\Query $query, \ArrayObject $options, $primary)
    {
        if ($primary){
            //debug($query->count());
        }
    }

}