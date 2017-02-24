<?php
class Bank extends CI_Controller
{
	public function create_user()
	{
		$this->load->view('bank/create_acc');
	}
	public function insert_db()
	{
		$b1 = $_POST['uname'];
		$b2 = $_POST['email'];
		$b3 = $_POST['mob_no'];
		$b4 = $_POST['psw'];

		$create_info = array('user_name' => $b1, 'email' => $b2, 'mob_no' => $b3, 'password' => $b4);
		$this->load->model('bank_model');
		$db_stat = $this->bank_model->insert_into_db($create_info);

		if ($db_stat == 1) {
			$this->load->view('bank/login');  
		}
		else{
			echo "Database error.Please Sign up again.";
		}
	}

	public function login()
	{
		$this->load->view('bank/login');  
	}

	public function error()
	{
		$this->load->view('bank/error');  
	}

	public function authenticate()
	{
		$n1= $_POST['mob_no'];
    	$n2= $_POST['psw'];

    	$user_info= array('mob_no' => $n1, 'password' => $n2
		);
		$this->load->model('bank_model');

		$db_pass= $this->bank_model->verify_email($user_info);

		if ($db_pass == 1) 
		{
			$this->load->view('bank/hpg');
		}

		else
		{
			$this->load->view('bank/error');
		}
	}

	public function homepage()
	{
		$this->load->view('bank/hpg');
	}

	public function deposit()
	{
		$this->load->view('bank/deposit');
	}

	public function dep_auth()
	{
		$dep1 = $_POST['mob_no'];   //Transaction to
		$dep2 = $_POST['amt'];
		$dep3 = $_POST['mob_no'];	//Transaction from
		$dep4 = "Deposit";			//Transaction type


		if($dep2 < 0)
		{
			$new_amt = 0;
		}
		else
		{
			$new_amt = $dep2;
		}

		$this->load->model('bank_model');
		$bank_info = array('mob_no' => $dep1);
		$curr_user = $this->bank_model->getbank_data($bank_info);
		foreach ( $curr_user as $bal1) 
		{
        	$new_bal = $bal1['balance'] + $new_amt;
        }
		
		$dep_info = array('mob_no' => $dep1, 'balance' =>$new_bal);
		$this->load->model('bank_model');
		$bank_stat = $this->bank_model->bank_db($dep_info);


		$this->load->model('bank_model');
		$curr_id = $this->bank_model->getuser_data($bank_info);

		foreach ( $curr_id as $id1) 
		{
        	$new_id = $id1['user_id'];
        }
        $txn_info = array(
        			'user_id' => $new_id,
        			'from_mob' => $dep3,
        			'to_mob' => $dep1,
        			'txn_amt' => $dep2,
        			'txn_type' => $dep4,
        			);

		$this->load->model('bank_model');
		$txn_stat = $this->bank_model->txn_db($txn_info);	


		if($bank_stat == 1 && $txn_stat == 1)
		{
			$this->load->view('bank/hpg');
		}

		else
		{
			$this->load->view('bank/error');
		}


	}
}

