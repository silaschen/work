<?php

namespace easy\db;
use easy\Config;
class postgres {

    private $cfg;
    private $con;

     function __construct($condb = null) {
    	$db = Config::get('pgsql','app');

        $this->cfg = is_null($condb) ? $db : $condb;
        try {
            $connection_string = sprintf("host=%s dbname=%s", $this->cfg["host"], $this->cfg["database"]);
            if (array_key_exists("port", $this->cfg)) {
                $connection_string .= sprintf(" port=%d", $this->cfg["port"]);
            }
            if (array_key_exists("username", $this->cfg)) {
                $connection_string .= sprintf(" user=%s", $this->cfg["username"]);
            }
            if (array_key_exists("password", $this->cfg)) {
                $connection_string .= sprintf(" password=%s", $this->cfg["password"]);
            }
            $this->con = pg_connect($connection_string);
        } catch (\Exception $ex) {
            print $ex->getMessage();
          
        }
    }

   


    public function Count($table,$map=array()){
 
        $sql = sprintf("select count(*) as total from %s",$table);
        $params = [];
        if(!empty($map)){
            $sql .= " WHERE ";
            foreach ($map as $key => $value) {
                if (is_array($value)) {
                    $sql .= $key." ".$value[0]." $".(count($params)+1)." AND ";
                    $value=$value[1];
                }else{
                    $sql .= $key."=$".(count($params)+1)." AND ";
                }
               
                array_push($params, $value);

            }
            $sql = mb_substr($sql, 0,strripos($sql, 'AND')-1);
        }
        $rs = pg_query_params($this->con,$sql,$params);
        $ret = pg_fetch_assoc($rs);
        return $ret['total'];
    }



    public function getDataList_byPage($table,$field='*',$map=array(),$size=false,$start=false){
        $sql = sprintf("select %s from %s",$field,$table);

        $params = [];
        if(!empty($map)){
            $sql .= " WHERE ";
            foreach ($map as $key => $value) {
                if (is_array($value)) {
                    $sql .= $key." ".$value[0]." $".(count($params)+1)." AND ";
                    $value=$value[1];
                }else{
                    $sql .= $key."=$".(count($params)+1)." AND ";
                }
                array_push($params, $value);
            }
            $sql = mb_substr($sql, 0,strripos($sql, 'AND')-1);
        }

        if ($size !== false && $start !== false) {    
                array_push($params, $size,$start);
                $sql .= " limit $".(count($params)-1)." offset $".count($params);
        }
        $res = [];
        $rs = pg_query_params($this->con, $sql,$params);
        while($row = pg_fetch_assoc($rs)){
            array_push($res, $row);
        }
        pg_free_result($rs);
        return $res;
    }

    public function updateById($table,$data=[],$id){
        if(empty($data)){
            return false;
        }
        $sql = sprintf("UPDATE %s SET",$table);
        $params = [];
        foreach ($data as $key => $value) {
            $sql .= " ".$key."=$".(count($params)+1).",";
            array_push($params, $value);
        }
        $sql = mb_substr($sql, 0,strripos($sql, ","));
        $sql .= " WHERE id='$id'";
        pg_query_params($this->con,$sql,$params);
        pg_query($this->con,'commit');
    }

    public function update($table,$data=[],$map=array()){
        if(empty($data)){
            return false;
        }
        $sql = sprintf("UPDATE %s SET",$table);
        $params = [];
        foreach ($data as $key => $value) {
            $sql .= " ".$key."=$".(count($params)+1).",";
            array_push($params, $value);
        }
        $sql = mb_substr($sql, 0,strripos($sql, ","));
        $sql .= " WHERE ";
        $filter = [];
       foreach ($map as $key => $val) {
            $sql .= $key."=$".(count($params)+count($filter)+1)." AND ";
            array_push($filter, $val);
        }
        $data = array_merge($params,$filter);
        $sql = mb_substr($sql, 0,strripos($sql, " AND"));
        pg_query_params($this->con,$sql,$data);
        pg_query($this->con,'commit');
    }

    /**
    ***insert into table
    **format $data = array('column1'=>$data1,'column2'=>$data2.....);
    **
    */
     public function Insert($table,$data=[]){
        if(empty($data)){
            return false;
        }
        $column = implode(',',array_keys($data));
        $sql = sprintf("INSERT  INTO %s (%s) VALUES (", $table,$column);
        $params = [];
        foreach ($data as $key => $value) { 
            $sql .= "$".(count($params)+1).",";
            $params[] = $value;
        }
        $sql = mb_substr($sql, 0,strripos($sql, ",")).")";   
        pg_query_params($this->con,$sql,$params);
        pg_query($this->con,'commit');
    }


    public function page($table,$fields='*',$map=[]){
        $page = filter_input(INPUT_GET,'p') ? filter_input(INPUT_GET, 'p') : 1;

        $count = $this->Count($table,$map);
        $pageOBJ = new \easy\Page($count,5,$page,\easy\Config::get('pagesize','app'));

        $list = $this->getDataList_byPage($table,$fields,$map,$pageOBJ->size,$pageOBJ->start_num);

        $page11 = $pageOBJ->showPages(1);

      
        return [$page11,$list];
    }



    public function query($sql){
        $rs = pg_query($this->con, $sql);
        $res = [];
        while ($row = pg_fetch_assoc($rs)) {
            $res[] = $row;
        }
        pg_free_result($rs);
        return $res;
    }

    public function execute($sql){
        pg_query($this->con,$sql);
        pg_query($this->con,'commit');

    }

   
}
