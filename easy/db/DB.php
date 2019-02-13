<?php
namespace easy\db;
/**
 * 数据库操作
 *链式操作DB::table($name)->where(['id'=>20])->limit(21)->fields(['id','userid'])->get()
 *提供静态方法DB::query();//select  DB::exec();//update and insert
 * Jan 31st,2019
 */

class DB 
{
	
	private $table; 
	static $DB;//instance
	private $con;
	private $query;
	private $query_params=[]; 

    private $fields = '*';

    private $where;
    private $limit;
    private $orderby;
    const QUERY='query';
    const UPDATE='update';
    const INSERT = 'insert';


	private function __construct($condb = null) {
    	$db = \easy\Config::get('pgsql','app');
    	// print_r("我被加载了");
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

    static private function getInsDB(){
    	if (!self::$DB) {
    		self::$DB = new self();
    	}
    	return self::$DB;
    }

	static public function table($name){
		$db = self::getInsDB();
		$db->table = $name;
		return $db;
	}


	public function where($map=array()){

		if(!empty($map)){

            $this->where .= " WHERE ";

            foreach ($map as $key => $value) {
                if (is_array($value)) {
                    $this->where .= $key." ".$value[0]." $".(count($this->query_params)+1)." AND ";
                    $value=$value[1];
                }else{
                    $this->where .= $key."=$".(count($this->query_params)+1)." AND ";
                }
               
                array_push($this->query_params, $value);

            }
            $this->where = mb_substr($this->where, 0,strripos($this->where, 'AND')-1);

        }
        return self::$DB;
	}


	public function limit($num){

		$this->limit= " limit $".(count($this->query_params)+1);
		array_push($this->query_params, $num);
		return self::$DB;

	}


    public function fields($fields=array()){
            // if ($fields) {
            //         $this->query=preg_replace("/\*(?=\s*from\w*)/", implode(",", $fields), $this->query);
            // }
        if ($fields) {
            $this->fields = implode(",", $fields);
        }
            return self::$DB;
    }


    public function orderby($order){

        if ($order) {
            $this->orderby = sprintf(" order by %s ",$order);
        }
       
        // if ($order) {
 
        //         if (preg_match('/\s(?=\s*limit\s*\$\d+\s*)/', $this->query)) {

        //             $this->query = preg_replace('/\s(?=\s*limit\s*\$\d*\s*)/', sprintf(" order by %s ",$order), $this->query);
        //         }else{
        //         $this->query.= sprintf(" order by %s ",$order);
        //     }
        // }

        return self::$DB;
    }

    private function createSQL($sqltype){

        switch ($sqltype) {
            case self::QUERY:
                $this->query = sprintf("select %s from %s",$this->fields,$this->table);
                $this->query=sprintf("%s%s%s%s",$this->query,$this->where,$this->orderby,$this->limit);
                break;
            case self::UPDATE:
                $this->query = sprintf("UPDATE %s SET {data} ",$this->table);
                $this->query = sprintf("%s%s",$this->query,$this->where);
                break;
            case self::INSERT:
                $this->query = sprintf("INSERT INTO %s {fields} VALUES {value}",$this->table);
                break;
        }

    }

	public function get(){
        $this->createSQL(self::QUERY);
		$res = pg_query_params($this->con,$this->query,$this->query_params);
		$resarr = [];
		while($row = pg_fetch_assoc($res)){
			$resarr[] = $row;
		}
        pg_free_result($res);
		return count($resarr) === 1 ? $resarr[0] : $resarr;
	}


    /**
     * static function query sql
     */
    static public function query($sql,$query_params=[]){
        $instance = self::getInsDB();
        $res = pg_query_params($instance->con,$sql,$query_params);

        $resarr = [];
        while($row = pg_fetch_assoc($res)){
            $resarr[] = $row;
        }
        pg_free_result($res);
        return count($resarr) === 1 ? $resarr[0] : $resarr;
    }



    /**
     * static function updaye or update or delete sql
     */
    static public function exec($sql,$query_params=[]){
        $instance = self::getInsDB();

        pg_query_params($instance->con,$sql,$query_params);
        pg_query($instance->con,'commit');
    }


    /**
     * update table data
     *DB::table()->where()->update();
     */

    public function update($data=array()){
        $this->createSQL(self::UPDATE);
        $update = '';
        if ($data) {
            foreach ($data as $key => $value) {
                $update .= sprintf("%s=#%d,",$key,count($this->query_params)+1);
                array_push($this->query_params, $value);
            }

        $replace =  preg_replace('/,(?=\s*$)/', '', $update);
        $this->query = preg_replace("/#/", '$', preg_replace('/{data}/', $replace , $this->query));
        }else{
            throw new \Exception("update need data", 1);
            
        }
        self::exec($this->query,$this->query_params);

    }



     public function insert($data=array()){
        $this->createSQL(self::INSERT);

        $values='';
        if ($data) {
            $fields = array_keys($data);  
            foreach ($data as $key => $value) {
                $values.= "#".(count($this->query_params)+1).",";
                array_push($this->query_params, $value);
            }
        $values =  preg_replace('/,(?=\s*$)/', '', $values);
        $fields = "(".implode(",", $fields).")";
        
        $this->query = preg_replace('/{fields}/', $fields , $this->query);
        $this->query = preg_replace('/#/','$',preg_replace('/{value}/', sprintf("(%s)",$values) , $this->query));
        }else{
            throw new \Exception("insert need data", 1);
            
        }
        self::exec($this->query,$this->query_params);
    }




}