<?php
namespace EAccess\Auth;

use Cake\Auth\BaseAuthenticate;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;

class ShibbolethAuthenticate extends BaseAuthenticate
{

    public function authenticate(Request $request, Response $response)
    {
        return $this->getShibboleth();
    }

    public function getUser(Request $request)
    {
        $username = $request->env('PHP_AUTH_USER');
        $pass = $request->env('PHP_AUTH_PW');

        if (!is_string($username) || $username === '' || !is_string($pass) || $pass === '') {
            return false;
        }
        return $this->_findUser($username, $pass);
    }


    public function implementedEvents()
    {
        return [
            'Auth.afterIdentify' => 'afterIdentify',
            'Auth.logout' => 'logout'
        ];
    }

    public function logout()
    {
        return true;
    }

    public function afterIdentify()
    {
        //debug("User has been identified by Shibboleth");
        return true;
    }


    public function isShibbolethInstalled()
    {
        //list of eAccess header variables fields to look for
        $keys = array("partyId", "email", "ntPrincipal", "legalGivenNames", "legalFamilyName", "activeDirectoryGroup");
        $isValid = 1;

        foreach ($keys as $k) {
            //if value not set, return false
            if (!isset($_SERVER[$k])) {
                $isValid = 0;
                break;
            } //if empty value, return false
            else {
                if ($_SERVER[$k] == "") {
                    $isValid = 0;
                    break;
                }
            }
        }

        return $isValid;
    }

    private function getShibboleth()
    {
        $userModel = $this->_config['userModel'];
        list(, $model) = pluginSplit($userModel);
        $fields = $this->_config['fields'];

        $users = TableRegistry::get($userModel);
        $system_id = Configure::read('eaccess.system-id');

        //Check presence of Shibboleth headers
        if (!$this->isShibbolethInstalled()) {
            if (\Cake\Core\Configure::read('debug')) {
                echo '<div class="alert alert-danger"><p>Could not find the eAccess header data. Make sure you have
                        <code>"partyId", "email", "ntPrincipal", "legalGivenNames", "legalFamilyName",
                        "activeDirectoryGroup"</code> in your header information.</p></div>';
            } else {
                echo '<div class="alert alert-danger"><p>'.__d('e_access', 'Error: Cannot log you in using eAccess [Missing headers].').'</p></div>';
            }
            return false;
        } //Shibboleth headers present, continue
        else {
            $data = array();

            //Prepare data
            $data["id"] = $_SERVER["partyId"];
            $data["email"] = strtolower($_SERVER["email"]);
            $data["username"] = strtolower($_SERVER["ntPrincipal"]);
            $data["first_name"] = $_SERVER["legalGivenNames"];
            $data["last_name"] = $_SERVER["legalFamilyName"];
            $data["is_deactivated"] = 0;
            $data["created_by"] = $system_id;
            $data["modified_by"] = $system_id;

            if (isset($_SERVER["activeDirectoryGroup"])) {
                $data["ad_groups"] = $_SERVER["activeDirectoryGroup"];
            }

            $userEntity = $users->newEntity($data);

            if ($userEntity->accessible('id') == false) {
                if (\Cake\Core\Configure::read('debug')) {
                    echo '<div class="alert alert-danger"><p>Your User model must allow access to the ´id´ field.
                        Add <code>&quot;id&quot; => true,</code> to your
                        User entity <code>src/Model/Entity/User.php</code>.</p></div>';
                } else {
                    echo '<div class="alert alert-danger"><p>'.__d('e_access', 'Error: Cannot log you in using eAccess').'.</p></div>';
                }
                return false;
            } else {
                $req = Request::createFromGlobals(); // Get session information

                if (Configure::read("eaccess.auto-save") && $req->session()->check('Auth.User') == false) {

                    if ($users->save($userEntity)) {
                        //debug("user saved...");
                    }
                    $this->updateGroups();
                }


                if (isset($_SERVER["partyId"])) {
                    $party_id = $_SERVER["partyId"];
                } else {
                    $party_id = 0;
                }
                $user = TableRegistry::get($userModel)->find('all');
                $conditions = [$model . '.' . "id" => $party_id];
                $result = $user
                    ->where($conditions)
                    ->first();

                if (empty($result)) {
                    // No user found...
                    return false;
                }

                $result->unsetProperty($fields['password']);

                $user = $result->toArray();
                // Add Shibboleth header information to user information
                $user['ntPrincipal'] = $_SERVER["ntPrincipal"];
                $user['ad_groups'] = explode(";", $_SERVER["activeDirectoryGroup"]);
                $user['eAccessLanguage'] = $_SERVER["lang"];

                $user['groups'] = $this->getGroups();

                return $user;
            }

        }
    }

    /**
     * Update user's groups based on the list of groups in the eAccess configuration 'eaccess.groups'
     * Adds groups which contains corresponding AD groups from Shibboleth for the logged in user
     */
    private function updateGroups() {
        if (is_array(Configure::read("eaccess.groups"))) {
            $user_id = $_SERVER["partyId"];
            $user_ad_groups = explode(";", $_SERVER["activeDirectoryGroup"]);
            $eaccess_groups = Configure::read('eaccess.groups');

            $system_id = Configure::read('eaccess.system-id');

            $user_group_table = TableRegistry::get('UsersGroups');

            $user_groups = $user_group_table->find('list', [
                'conditions' => ['user_id' => $user_id, "created_by" => $system_id],
                'keyFields' => 'id',
                'valueField' => 'group_id'
                ])->toArray();

            // Contains a list of group_ids to remove
            $remove_ids = [];

            // Remove groups which user doesn't have access anymore
            foreach ($user_groups as $key => $group_id) {
                // If group is not in Config list remove it
                if (isset($eaccess_groups[$group_id]) == false) {
                    $remove_ids[] = $key;
                }
                // If user is not part of the AD group for a eAccess Config Group remove it
                else {
                    $found = false;
                    foreach ($user_ad_groups as $ad) {
                        if (in_array($ad, $eaccess_groups[$group_id])) {
                            $found = true;
                        }
                    }
                    if ($found == false) {
                        $remove_ids[] = $key;
                    }
                }
            }


            if (count($remove_ids) > 0) {
                $user_group_table->deleteAll(['id IN ('.implode(',',$remove_ids).')']);
            }

            // Add groups which the user is not already saved
            // Looping through the config eAccess groups
            foreach ($eaccess_groups as $group_id => $ad_list) {
                // User is not in the current group
                if (in_array($group_id, $user_groups) == false) {
                    foreach ($ad_list as $ad) {
                        // Current group is found in the user's AD groups
                        if (in_array($ad, $user_ad_groups)) {
                            $user_group = $user_group_table->newEntity();
                            $user_group->group_id = $group_id;
                            $user_group->user_id = $user_id;
                            $user_group->created = date('Y-m-d H:i:s');
                            $user_group->modified = date('Y-m-d H:i:s');
                            $user_group->created_by = $system_id;
                            $user_group->modified_by = $system_id;
                            if ($user_group_table->save($user_group)) {
                                //debug("Group Saved... (".$group_id.")");
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Get the list of groups a user belongs to from the DB
     *
     * @return Array
     */
    private function getGroups() {

            $user_id = $_SERVER["partyId"];
            $user_ad_groups = explode(";", $_SERVER["activeDirectoryGroup"]);

            $user_group_table = TableRegistry::get('UsersGroups');

            $groups = $user_group_table->find('list', [
                'conditions' => ['user_id' => $user_id],
                'keyField' => 'id',
                'valueField' => 'group_id'
            ]);

            return $groups->toArray();

    }

}