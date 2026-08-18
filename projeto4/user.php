<?php
class User{
    private $dbHost     = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName     = "escola";
    private $dbTable    = "users";

    public function __construct(){
        if(!isset($this->db)){
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
    }

    // Retorna linhas da base de dados com base nas condições
    // $tablename = string da tabela
    public function getRows($conditions = array()){
        $sql = "SELECT ";

        $sql .= array_key_exists("select",$conditions) ? $conditions["select"] : "*";
        $sql .= " FROM ".$this->dbTable;

        if(array_key_exists("where",$conditions)){
            $sql .= " WHERE ";
            $i = 0;
            foreach($conditions["where"] as $key => $value){
                $pre = ($i > 0) ? " AND " : "";
                $sql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }

        if(array_key_exists("order_by",$conditions)){
            $sql .= " ORDER BY ".$conditions["order_by"];
        }

        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= " LIMIT ".$conditions["start"].",".$conditions["limit"];
        }elseif(array_key_exists("limit",$conditions)){
            $sql .= " LIMIT ".$conditions["limit"];
        }

        $result = $this->db->query($sql);

        if(array_key_exists("return_type",$conditions) && $conditions["return_type"] != "all"){
            switch($conditions["return_type"]){
                case "count":
                    $data = $result->num_rows;
                    break;
                case "single":
                    $data = $result->fetch_assoc();
                    break;
                default:
                    $data = "";
            }
        }else{
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $data[] = $row;
                }
            }
        }

        return !empty($data)?$data:false;
    }

    // Inserir dados na base de dados
    // $params = ARRAY com dados a inserir na tabela
    public function insert($data){
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';

            if(array_key_exists("created",$data)){
                $data["created"] = date("Y-m-d H:i:s");
            }

            if(array_key_exists("modified",$data)){
                $data["modified"] = date("Y-m-d H:i:s");
            }

            foreach($data as $key => $val){
                $columns .= $key.",";
                $values  .= "'".$val."',";
            }

            $columns = rtrim($columns, ',');
            $values  = rtrim($values, ',');

            $query = "INSERT INTO ".$this->dbTable." (".$columns.") VALUES (".$values.")";
            $insert = $this->db->query($query);
            return $insert ? $this->db->insert_id : false;
        }else{
            return false;
        }
    }
}
?>
