<?php
class srms
{
    public $base_url = 'http://service.bilrigo.net/';
    public $connect;
    public $query;
    public $statement;
    public $now;

    function srms()
    {
        $this->connect = new PDO("mysql:host=localhost;dbname=admin", "root", "t6EuZwCHXH6tQr2yKNWCM4b9v3FGt6d3");
        session_start();
        $this->now = date("Y-m-d H:i:s",  STRTOTIME(date('h:i:sa')));
    }

    function execute($data = null)
    {
        $this->statement = $this->connect->prepare($this->query);
        if($data)
        {
            $this->statement->execute($data);
        }
        else
        {
            $this->statement->execute();
        }
    }

    function row_count()
    {
        return $this->statement->rowCount();
    }
    
    function lastInsertIdsave()
    {
        return $this->connect->lastInsertId();
    }

    function statement_result()
    {
        return $this->statement->fetchAll();
    }

    function get_result()
    {
        return $this->connect->query($this->query, PDO::FETCH_ASSOC);
    }

    function is_login()
    {
        if(isset($_SESSION['user_id']))
        {
            return true;
        }
        return false;
    }

    function is_master_user()
    {
        if(isset($_SESSION['user_type']))
        {
            if($_SESSION["user_type"] == '1')
            {
                return true;
            }
            return false;
        }
        return false;
    }

    function login_id()
    {
        $this->query = "
		SELECT * FROM management_account a 
		left JOIN management b on a.ac_code = b.cp_code
		WHERE ac_status='Enable' AND cp_status='Enable' AND ac_id = '".$_SESSION['user_id']."'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["ac_id"];
        }
    }

    function login_mame()
    {
        $this->query = "
		SELECT * FROM management_account a 
		left JOIN management b on a.ac_code = b.cp_code
		WHERE ac_status='Enable' AND cp_status='Enable' AND ac_id = '".$_SESSION['user_id']."'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["ac_name"];
        }
    }

    function level_check()
    {
        $this->query = "
		SELECT * FROM management_account a 
		left JOIN management b on a.ac_code = b.cp_code
		WHERE ac_status='Enable' AND cp_status='Enable' AND ac_id = '".$_SESSION['user_id']."'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["cp_level"];
        }
    }

    function cp_name_check()
    {
        $this->query = "
		SELECT * FROM management_account a 
		left JOIN management b on a.ac_code = b.cp_code
		WHERE ac_id = '".$_SESSION['user_id']."'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["cp_name"];
        }
    }

    function cmp_code()
    {
        $this->query = "
		SELECT * FROM management_account a 
		left JOIN management b on a.ac_code = b.cp_code
		WHERE ac_status='Enable' AND cp_status='Enable' AND ac_id = '".$_SESSION['user_id']."'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["cp_code"];
        }
    }
    function cmp_name()
    {
        $this->query = "
		SELECT * FROM management_account a 
		left JOIN management b on a.ac_code = b.cp_code
		WHERE ac_status='Enable' AND cp_status='Enable' AND ac_id = '".$_SESSION['user_id']."'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["cp_name"];
        }
    }
    

    function clean_input($string)
    {
        $string = trim($string);
        $string = stripslashes($string);
        $string = htmlspecialchars($string);
        return $string;
    }

    function Get_class_name($class_id)
    {
        $this->query = "
		SELECT class_name FROM class_srms 
		WHERE class_id = '$class_id'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["class_name"];
        }
    }

    function Get_Class_subject($class_id)
    {
        $this->query = "
		SELECT subject_name FROM subject_srms 
		WHERE class_id = '$class_id' 
		AND subject_status = 'Enable'
		";
        $result = $this->get_result();
        $data = array();
        foreach($result as $row)
        {
            $data[] = $row["subject_name"];
        }
        return $data;
    }

    function Get_user_name($user_id)
    {
        $this->query = "
		SELECT * FROM user_srms 
		WHERE user_id = '".$user_id."'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            if($row['user_type'] != 'Master')
            {
                return $row["user_name"];
            }
            else
            {
                return 'Master';
            }
        }
    }

    function Get_exam_name($exam_id)
    {
        $this->query = "
		SELECT exam_name FROM exam_srms 
		WHERE exam_id = '$exam_id'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["exam_name"];
        }
    }


    function Get_total_classes()
    {
        $this->query = "
		SELECT COUNT(class_id) as Total 
		FROM class_srms 
		WHERE class_status = 'Enable'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["Total"];
        }
    }

    function Get_total_subject()
    {
        $this->query = "
		SELECT COUNT(subject_id) as Total 
		FROM subject_srms 
		WHERE subject_status = 'Enable'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["Total"];
        }
    }

    function Get_total_student()
    {
        $this->query = "
		SELECT COUNT(student_id) as Total 
		FROM student_srms 
		WHERE student_status = 'Enable'
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["Total"];
        }
    }

    function Get_total_exam()
    {
        $this->query = "
		SELECT COUNT(exam_id) as Total 
		FROM exam_srms 
		WHERE exam_status = 'Enable' 
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["Total"];
        }
    }

    function Get_total_result()
    {
        $this->query = "
		SELECT COUNT(result_id) as Total 
		FROM result_srms 
		";
        $result = $this->get_result();
        foreach($result as $row)
        {
            return $row["Total"];
        }
    }

}

?>