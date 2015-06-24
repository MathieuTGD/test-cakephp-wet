<?php
namespace WetKit\View\Helper;

use Cake\View\Helper;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\View\Cell;

class WetHelper extends Helper
{

    /**
     * Returns the field corresponding to the current language.
     *
     * @param $data - Data object or array containing returned schema values
     * @param $field - Name of the field without the language suffix _en or _fr
     * @param null $lang - *Optional force a language
     * @return string
     */
    public function field($data, $field, $lang = null)
    {
        $f = null;
        if ($lang) {
            $f = $data[$field . '_' . $lang];
        } else {
            $f = $data[$field . '_' . Configure::read('wetkit.lang')];
        }

        if ($f == null) {
            return $data[$field];
        } else {
            return $f;
        }
    }


    /**
     * Returns the WET modified date
     *
     * ** Options
     *
     * `label` - String Optional - Replaces the default 'Modified' string
     * `dateFormat` - String Optional - Date Format string
     *
     * @param $date - Time object or date string
     * @param array $options
     * @return string
     */
    public function modified(array $options = [])
    {
        $options += [
            'date' => Configure::read('wetkit.modified'),
            'label' => __d('wet_kit', 'Date modified:'),
            'dateFormat' => Configure::read('wetkit.dateFormat'),
        ];

        $date = $options['date'];

        if ($date === null) {
            $modified = __d('wet_kit', 'Undefined');
        } else {
            if (!is_object($date)) {
                $date = new Time($date);
            }
            $modified = $date->i18nFormat($options['dateFormat']);
        }

        return '
        <div class="clearfix"></div>
        <dl id="wb-dtmd">
            <dt>' . $options['label'] . '&#32;</dt>
            <dd><time property="dateModified">' . $modified . '</time></dd>
        </dl>
        ';
    }



    /**
     * Returns the modification data for a schema record based on
     * Created, Modified, Created_by, Modified_by fields
     *
     * ** Options
     *
     * `class` - Div class attribute
     * `style` - Div style attribute
     * `dateTimeFormat` - DateTime Format string
     * `created` - String or Object - Created Date
     * `created_by` - String or int - Full name or user_id
     * `modified` - String or Object - Modified Date
     * `modified_by` - String or Int - Full name or user_id
     *
     * @param $data - Object - Schema returned object
     * @param array $options
     * @return string
     */
    public function whoDidIt($data=null, array $options = array())
    {
        $options += [
            'class' => 'text-muted',
            'style' => 'margin-top: 20px;',
            'dateTimeFormat' => Configure::read('wetkit.dateTimeFormat'),
            'modified'=>(isset($data->modified))?$data->modified:null,
            'created'=>(isset($data->created))?$data->created:null,
            'modified_by'=>(isset($data->modified_by))?$data->modified_by:null,
            'created_by'=>(isset($data->created_by))?$data->created_by:null
        ];
        return $this->_View->cell('WetKit.User::whoDidIt', [$options]);
    }


    public function glyphDanger()
    {
        return '<span class="glyphicon glyphicon-lock"></span> ';
    }

    public function showEncryptedNotice()
    {
        return '<span class="glyphicon glyphicon-lock"></span> ';
    }

    public function iconValid()
    {
        return '<span class="glyphicon glyphicon-ok-sign text-success"></span>';
    }

    public function iconInvalid()
    {
        return '<span class="glyphicon glyphicon-remove-sign text-danger"></span>';
    }

}