<?php

/**
 * Users Model
 *
 */
class Transaksi_harian extends Abstract_model {
	
	// $user_name = getVarClean('user_name','str','');
	// $sql = "select t_cust_account_id,npwd from sikp.f_get_npwd_by_username('WPHOTEL')";
	// $query = $this->db->query($sql);
	// $arr_npwd = $query->row_array();
	// print_r($arr_npwd);exit;
    public $table           = "users";
    public $pkey            = "id";
    public $alias           = "usr";

    public $fields          = array(
                                // 'id'                => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID User'),
                                // 'ip_address'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'IP Address'),
                                // 'username'          => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Username'),
                                // 'password'          => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Password'),
                                // 'salt'              => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Salt'),
                                // 'email'             => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Email'),
                                // 'activation_code'   => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Activation Code'),
                                // 'forgotten_password_code'   => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Forgoten Password Code'),
                                // 'forgotten_password_time'   => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Forgoten Password Time'),
                                // 'remember_code'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Remember Code'),
                                // 'created_on'        => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Created On'),
                                // 'last_login'        => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Last Login'),
                                // 'active'            => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Active'),
                                // 'first_name'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'First Name'),
                                // 'last_name'         => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Last Name'),
                                // 'company'           => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Company'),
                                // 'phone'             => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Phone'),
                                // 'location_id'       => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Location'),

                            );

    public $selectClause    = "select t_cust_account_id,npwd";
    public $fromClause      = "sikp.f_get_npwd_by_username('WPHOTEL')";

    public $refs            = array('users_groups' => 'user_id');

    function __construct() {
        parent::__construct();
    }

    public function transaction_query(){
		$user_name = getVarClean('user_name','str','');
		$sql = "select t_cust_account_id,npwd from sikp.f_get_npwd_by_username('". $user_name ."')";
		$query = $this->db->query($sql);
		$arr_npwd = $query->row_array();
		print_r($arr_npwd);exit;
		$sql = "SELECT '".$arr_npwd['npwd']."' as npwd,
                       		 t_cust_acc_dtl_trans.t_cust_account_id,
                       		 sum(t_cust_acc_dtl_trans.service_charge) as jum_trans,
                       		 sum(t_cust_acc_dtl_trans.vat_charge) as jum_pajak,
                                t_cust_acc_dtl_trans.p_vat_type_dtl_id,
                       		 p_finance_period.p_finance_period_id,
                       		 p_finance_period.code,
                       		 t_customer_order.p_order_status_id,
                       		 case when t_vat_setllement.start_period is null then p_finance_period.start_date else t_vat_setllement.start_period END as start_period,
                            case when t_vat_setllement.end_period is null then p_finance_period.end_date else t_vat_setllement.end_period END as end_period
                       FROM
                            t_cust_acc_dtl_trans
                       LEFT JOIN p_finance_period on to_char(trans_date, 'YYYY-MM') = to_char(p_finance_period.start_date, 'YYYY-MM')
                       LEFT JOIN t_vat_setllement on t_cust_acc_dtl_trans.t_cust_account_id = t_vat_setllement.t_cust_account_id and  p_finance_period.p_finance_period_id = t_vat_setllement.p_finance_period_id 
                       LEFT JOIN t_customer_order on t_customer_order.t_customer_order_id = t_vat_setllement.t_customer_order_id
                       WHERE
                            t_cust_acc_dtl_trans.t_cust_account_id = ".$arr_npwd['t_cust_account_id']." AND 
                       		 trans_date >= CASE
                       				WHEN  t_vat_setllement.start_period is null THEN p_finance_period.start_date
                       				ELSE t_vat_setllement.start_period
                       			END
                       		AND 
                       		trans_date <= CASE
                       				WHEN  t_vat_setllement.end_period is null THEN p_finance_period.end_date
                       				ELSE t_vat_setllement.end_period
                       			END
                       GROUP BY
                       		 t_cust_acc_dtl_trans.t_cust_account_id,
                                t_cust_acc_dtl_trans.p_vat_type_dtl_id,
                       		 p_finance_period.p_finance_period_id,
                       		 p_finance_period.code,
                       		 t_customer_order.p_order_status_id,
                       		 case when t_vat_setllement.start_period is null then p_finance_period.start_date else t_vat_setllement.start_period END,
                            case when t_vat_setllement.end_period is null then p_finance_period.end_date else t_vat_setllement.end_period END
                       ORDER BY 
                       		 case when t_vat_setllement.start_period is null then p_finance_period.start_date else t_vat_setllement.start_period END DESC";
							 
		
	}

}

/* End of file Users.php */