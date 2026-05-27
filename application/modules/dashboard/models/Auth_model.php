<?php defined('BASEPATH') OR exit('No direct script access allowed');
 #------------------------------------    
    # Author: Bdtask Ltd
    # Author link: https://www.bdtask.com/
    # Dynamic style php file
    # Developed by :Isahaq
    #------------------------------------    

class Auth_model extends CI_Model {


	public function checkUser($data = array())
	{
        $encryption_key = Config::$encryption_key;

		return $this->db->select("a.*,b.*,a.logo as image,CONCAT_WS(' ', a.first_name, a.last_name) AS fullname,IF (b.user_type=1, 'Admin', 'User') as user_level,
        b.user_type as user_level2,screen,c.password_enable,b.startdate,b.enddate,b.temporary,
        AES_DECRYPT(c.header_text,'" . $encryption_key . "') as header_text,
        AES_DECRYPT(c.footer_text,'" . $encryption_key . "') as footer_text,
        CONVERT(AES_DECRYPT(b.encrypted_password,'" . $encryption_key . "') USING utf8mb4) AS plain_pw")
			->from('users a')
			->join('user_login b','b.user_id = a.user_id')
            ->join('company_information c','c.company_id = b.user_type')
            ->group_start()
            ->where(
                "CAST(AES_DECRYPT(b.encrypted_password,'" . $encryption_key . "') AS CHAR(255)) = " . $this->db->escape($data['password']),
                NULL,
                FALSE
            )
            ->or_where('b.password', md5('gef' . $data['password']))
            ->group_end()
			->where('b.username', $data['email'])
            ->where('b.status',1)
            ->where('c.status',1)
            ->where_in('b.user_type', [1,2,3])
			->get();
	}

    public function insert_device_info($userid,$browser,$operatingsystem,$devicetype,$ipaddress)
    {
        $data = [
            'userid'            => $userid,
            'browser'          => $browser,
            'operating_system' => $operatingsystem,
            'device_type'      => $devicetype,
            'ip_address'       =>$ipaddress,
        ];

        $this->db->insert('user_login_info', $data);

        return $this->db->insert_id();
    }


	 public function userPermissionadmin($id = null)
    {
        
        return $this->db->select("
            sub_module.directory, 
            role_permission.fk_module_id, 
            role_permission.create, 
            role_permission.read, 
            role_permission.update, 
            role_permission.delete
            ")
            ->from('role_permission')
            ->join('sub_module', 'sub_module.id = role_permission.fk_module_id', 'full')
            ->where('role_permission.role_id', $id)
            ->where('sub_module.status', 1)
            ->group_start()
                ->where('create', 1)
                ->or_where('read', 1)
                ->or_where('update', 1)
                ->or_where('delete', 1)
            ->group_end()
            ->get()
            ->result();
    }

    public function userPermission($id = null)
    {
        

        $userrole=$this->db->select('sec_userrole.*,sec_role.*')->from('sec_userrole')->join('sec_role','sec_userrole.roleid=sec_role.id')->where('sec_userrole.user_id',$id)->get()->result();
        $roleid = array();
        foreach ($userrole as $role) {
            $roleid[] =$role->roleid;
        }
    
        if(!empty($roleid)){
         return $result =  $this->db->select("

                    role_permission.fk_module_id, 
                    sub_module.directory,
                    IF(SUM(role_permission.create) >= 1,1,0) AS 'create', 
                    IF(SUM(role_permission.read) >= 1,1,0) AS 'read', 
                    IF(SUM(role_permission.update) >= 1,1,0) AS 'update', 
                    IF(SUM(role_permission.delete) >= 1,1,0) AS 'delete'
                ")
                ->from('role_permission')
                ->join('sub_module', 'sub_module.id = role_permission.fk_module_id', 'full')
                ->where_in('role_permission.role_id',$roleid)
                ->where('sub_module.status', 1)
                ->group_by('role_permission.fk_module_id')
                ->group_start()
                    ->where('create', 1)
                    ->or_where('read', 1)
                    ->or_where('update', 1)
                    ->or_where('delete', 1)
                ->group_end()
                
                ->get()
                ->result();
            }else{

            return $this->db->select("
            sub_module.directory, 
            role_permission.fk_module_id, 
            role_permission.create, 
            role_permission.read, 
            role_permission.update, 
            role_permission.delete
            ")
            ->from('role_permission')
            ->join('sub_module', 'sub_module.id = role_permission.fk_module_id', 0)
            ->where('role_permission.role_id', 0)
            ->where('sub_module.status', 1)
            ->group_start()
                ->where('create', 1)
                ->or_where('read', 1)
                ->or_where('update', 1)
                ->or_where('delete', 1)
            ->group_end()
            ->get()
            ->result();
            }
    }

    public function settings_data(){
	  return $this->db->select('*')	
			->from('web_setting')
			->get()
			->result_array();
    }

    public function checkfinancialyear(){

        $data = $this->db->select("*")
            ->from("financial_year")
            ->where("startDate<=",date("Y-m-d"))
            ->where("endDate>=",date("Y-m-d"))
            ->where("status",1)
            ->get()->row();
        return $data;
    }


}
 