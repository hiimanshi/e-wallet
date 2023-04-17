<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ewallet extends CI_Controller {
    public function transfer_view(){
        $this->load->view('transfer');
    }
    public function register() {
    if ($this->input->method() == 'post') {

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));
        $balance = 0;
        
        $this->db->insert('users', array(
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'balance' => $balance,
        ));
        
        redirect('login');
    }
    
    $this->load->view('register');
    }
    public function login() {
        if ($this->input->method() == 'post') {
          $email = $this->input->post('email');
          $password = md5($this->input->post('password'));
          
          $user = $this->db->get_where('users', array(
            'email' => $email,
            'password' => $password,
          ))->row();
          
          if ($user) {
            $this->session->set_userdata('user_id', $user->id);
            redirect('dashboard');
          } else {
            $data['error'] = 'Invalid email or password';
            $this->load->view('login', $data);
          }
        }
        
        $this->load->view('login');
      }
      public function dashboard() {
        $user_id = $this->session->userdata('user_id');
        
        if (!$user_id) {
          redirect('login');
        }
        
        $user = $this->db->get_where('users', array(
          'id' => $user_id,
        ))->row();
        
        $data['user'] = $user;
        
        $this->load->view('dashboard', $data);
      }
      public function transfer() {
        $user_id = $this->session->userdata('user_id');
        
        if (!$user_id) {
          redirect('login');
        }
        
        if ($this->input->method() == 'post') {
            
          $receiver_id = $this->input->post('receiver_id');
          $amount = $this->input->post('amount');
          
          $sender = $this->db->get_where('users', array(
            'id' => $user_id,
          ))->row();
          
          $receiver = $this->db->get_where('users', array(
            'id' => $receiver_id,
          ))->row();
         
          if ($receiver) {
            
            if ($sender->balance >= $amount) {
              $this->db->trans_start();
             
              $this->db->update('users', array(
                'balance' => $sender->balance - $amount,
              ), array(
                'id' => $sender->id,
              ));
              
              $this->db->update('users', array(
                'balance' => $receiver->balance + $amount,
              ), array(
                'id' => $receiver->id,
              ));
              
              $this->db->insert('transactions', array(
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
                'created_at' => date('Y-m-d H:i:s'),
              ));
              
              $this->db->trans_complete();
             

              if ($this->db->trans_status() != 1) {
            
                echo 'An error occurred while transferring money';
              } else {
               echo 'Money transferred successfully';
              }
            } else {
              echo 'Insufficient balance';
            }
          }
          
         echo "<br><a href=".base_url('dashboard').">Back to Dashbord</a>";
        } 
    }           
    public function get_full_name() {
        $receiver_id = $this->input->post('receiver_id');
        
        $receiver = $this->db->get_where('users', array(
          'id' => $receiver_id,
        ))->row();
        
        if ($receiver) {
          echo $receiver->name;
        } else {
          echo '';
        }
    }
    public function get_receiver_name($receiver_id) {        
        $receiver = $this->db->get_where('users', array(
          'id' => $receiver_id,
        ))->row();
        
        if ($receiver) {
          echo $receiver->name;
        } else {
          echo '';
        }
    }
    public function history() {
    $user_id = $this->session->userdata('user_id');
    
    if (!$user_id) {
        redirect('login');
    }
    
    $transactions = $this->db->get_where('transactions', array(
        'sender_id' => $user_id,
    ))->result();
    
    $data['transactions'] = $transactions;
    $this->load->view('history', $data);
    }
            
}