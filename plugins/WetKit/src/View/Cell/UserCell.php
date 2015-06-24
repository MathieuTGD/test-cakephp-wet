<?php
namespace WetKit\View\Cell;

use Cake\Core\Exception\Exception;
use Cake\I18n\Time;
use Cake\View\Cell;

class UserCell extends Cell
{
    public function whoDidIt ($options)
    {

        $options += [
            'created' => null,
            'created_by' => null,
            'modified' => null,
            'modified_by' => null
        ];

        $this->loadModel('Users');

        if (is_int($options['created_by'])){
            try {
                $user = $this->Users->get($options['created_by']);
                $options['created_by'] = trim($user->first_name . ' ' . $user->last_name);
            } catch (\Exception $e) {
                $options['created_by'] = null;
            }
        }

        if ($options['created_by'] === $options['modified_by']) {
            $options['modified_by'] = $options['created_by'];
        } else if (is_int($options['modified_by'])){
            try {
                $user = $this->Users->get($options['modified_by']);
                $options['modified_by'] = trim($user->first_name . ' ' . $user->last_name);
            } catch (\Exception $e) {
                $options['modified_by'] = null;
            }
        }


        if (is_object($options['created'])) {
            $options['created'] = $options['created']->i18nFormat($options['dateTimeFormat']);
        }
        if (is_object($options['modified'])) {
            $options['modified'] = $options['modified']->i18nFormat($options['dateTimeFormat']);
        }

        $this->set('data', $options);
    }
}