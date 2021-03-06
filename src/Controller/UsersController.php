<?php
namespace App\Controller;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;


class UsersController extends AppController
{
    public function isAuthorized()
    {
        return true;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['logout']);
    }

    /*
     * Fonctions de pages
     */
    public function inscription(){}

    public function reinitialiserMotDePasse(){}

    public function profil(){}

    /*
     * Fonctions du controller
     */
    public function logout()
    {
        $this->Flash->success('Vous êtes déconnecté');
        return $this->redirect($this->Auth->logout());
    }

    public function login(){
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            else
                $this->Flash->error('Identifiants incorrects');
        }
    }

    public function creer()
    {
        if ($this->request->is('post'))
        {
            $this->loadModel('Users');
            $user = $this->Users->newEntity();
            $user = $this->Users->patchEntity($user, $this->request->getData());

            $entered_pass = $this->request->getData('password');
            $confirm_pass = $this->request->getData('password_confirm');

            if($entered_pass == $confirm_pass)
            {
                $user[$confirm_pass] = (new DefaultPasswordHasher)->hash($user[$confirm_pass]);
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('Compte crée'));
                    return $this->redirect(['action' => 'index']);
                }
                else
                    $this->Flash->error(__('Echec lors de la création du compte'));
            }
            else
                $this->Flash->error(__('Les deux mots de passe ne sont pas identique'));
        }
        $this->Flash->error("pas entré dedans");
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }


}