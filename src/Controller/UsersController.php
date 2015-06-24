<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Groups']
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);

        // Set the WET modified date
        if ($user->modified) {
            $this->WetKit->setModified($user->modified);
        } else if ($user->created) {
            $this->WetKit->setModified($user->created);
        }
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            $user_id = 0;
            if ( $this->Auth->user('id') ) {
                $user_id = $this->Auth->user('id');
            }
            $user->created_by = $user_id;
            $user->modified_by = $user_id;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The form has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->WetKit->flashError(__('The form could not be saved because error(s) were found.'), $user);
            }
        }
        $groups = $this->Users->Groups->find('list', ['limit' => 200]);
        $this->set(compact('user', 'groups'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Groups']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            $user_id = 0;
            if ( $this->Auth->user('id') ) {
                $user_id = $this->Auth->user('id');
            }
            $user->modified_by = $user_id;
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The form has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->WetKit->flashError(__('The form could not be saved because error(s) were found.'), $user);
            }
        }
        $groups = $this->Users->Groups->find('list', ['limit' => 200]);
        $this->set(compact('user', 'groups'));
        $this->set('_serialize', ['user']);

        // Set the WET modified date
        if ($user->modified) {
            $this->WetKit->setModified($user->modified);
        } else if ($user->created) {
            $this->WetKit->setModified($user->created);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success('The user has been deleted.');
        } else {
            $this->Flash->error(__('The record could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
    * Login method
    *
    * @return void
    */
    public function login()
    {
        if (!$this->Auth->user()) {
            $this->Flash->error(
                __("You are not authorized to access this site."),
                'default',
                [],
                'auth'
            );
            $this->redirect("/");
        }
    }
}
