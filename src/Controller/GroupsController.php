<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 */
class GroupsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('groups', $this->paginate($this->Groups));
        $this->set('_serialize', ['groups']);
    }

    /**
     * View method
     *
     * @param string|null $id Group id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $group = $this->Groups->get($id, [
            'contain' => ['Users']
        ]);
        $this->set('group', $group);
        $this->set('_serialize', ['group']);

        // Set the WET modified date
        if ($group->modified) {
            $this->WetKit->setModified($group->modified);
        } else if ($group->created) {
            $this->WetKit->setModified($group->created);
        }
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $group = $this->Groups->newEntity();
        if ($this->request->is('post')) {
            $group = $this->Groups->patchEntity($group, $this->request->data);
            $user_id = 0;
            if ( $this->Auth->user('id') ) {
                $user_id = $this->Auth->user('id');
            }
            $group->created_by = $user_id;
            $group->modified_by = $user_id;
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('The form has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->WetKit->flashError(__('The form could not be saved because error(s) were found.'), $group);
            }
        }
        $users = $this->Groups->Users->find('list', ['limit' => 200]);
        $this->set(compact('group', 'users'));
        $this->set('_serialize', ['group']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Group id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $group = $this->Groups->get($id, [
            'contain' => ['Users']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $group = $this->Groups->patchEntity($group, $this->request->data);
            $user_id = 0;
            if ( $this->Auth->user('id') ) {
                $user_id = $this->Auth->user('id');
            }
            $group->modified_by = $user_id;
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('The form has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->WetKit->flashError(__('The form could not be saved because error(s) were found.'), $group);
            }
        }
        $users = $this->Groups->Users->find('list', ['limit' => 200]);
        $this->set(compact('group', 'users'));
        $this->set('_serialize', ['group']);

        // Set the WET modified date
        if ($group->modified) {
            $this->WetKit->setModified($group->modified);
        } else if ($group->created) {
            $this->WetKit->setModified($group->created);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Group id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $group = $this->Groups->get($id);
        if ($this->Groups->delete($group)) {
            $this->Flash->success('The group has been deleted.');
        } else {
            $this->Flash->error(__('The record could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
