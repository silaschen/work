<?php
namespace app\model;
/**
 * Model
 * 可以把某些具体的业务数据处理在某个model内完成
 */
// use Illuminate\Database\Capsule\Manager as DB;
use easy\db\DB;
use easy\Page;
class Index extends \easy\db\postgres
{
	


	public function getLesson(){

		// var_dump(count(DB::table('user_alerts')->orderby("id asc")->limit(29)->get()));
		// var_dump(DB::query("select * from qliksense_users where userid='chensw10'"));
		// var_dump(DB::query("select * from qliksense_users_attributes where userid=$1",array('lantian3')));
// DB::exec("insert into partners (partnerid,partnername,description,info) values ($1,$2,$3,$4)",['abcsaff11112324234143214143','test','sss',json_encode(['ip'=>['0.0.0.0']])]);

		// var_dump(DB::table("partners")->where(['id'=>20])->update(['partnername'=>'love u','info'=>json_encode(['iplist'=>['0.0.0.0/0']])]))
		;

		// DB::table('partners')->insert(['partnerid'=>'11111','description'=>'eee','partnername'=>'SSS','info'=>json_encode(['iplist'=>['0.0.0.0']])]);
		// return $this->query("select * from qliksense_users where userid in ('chensw10')");
		// return $this->Count('qliksense_users');
		return $this->page('qliksense_users_attributes','*');
	
	}






}