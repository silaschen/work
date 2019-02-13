<?php  
namespace easy;
/**
 * 分页类
 *2/11/2019
 */
class Page{  
    protected $count;       
    protected $showPages;  
    protected $countPages; 
    protected $currPage;  
    public $size;     
    protected $href; 
    public $start_num;
    protected $page_arr=array();
   
    public function __construct($count,$showPages,$currPage,$size,$href=''){  
        $this->count=$count;  
        $this->showPages=$showPages;  
        $this->currPage=$currPage;  
        $this->size=$size; 
        $this->start_num = ($this->currPage-1)*($this->size);
        if(empty($href)){ 
                $url = $_SERVER['REQUEST_URI']; 
                if (preg_match("/\?/", $url)) {
                    $url = substr($url,0,strpos($url, '?'));
                }
            $this->href=htmlentities($url);   
        }else{  
            $this->href=$href;  
        }  
        $this->Create_Page();  
    }  


    public function getPages(){  
        return $this->page_arr;  
    }  
   

    public function showPages($style=1){  
        $func='pageStyle'.$style;  
        return $this->$func();  
    }  
   

    protected function pageStyle1(){  
        $pageStr = "";
        $_GET['p'] = 1;  
        $pageStr.='<li><a href="'.$this->href.'?'.http_build_query($_GET).'">first</a> </li>';  
        if($this->currPage>1){  
            $_GET['p'] = $this->currPage-1;  
            $pageStr.='<li><a href="'.$this->href.'?'.http_build_query($_GET).'">prev</a></li>';  
        }  
   
        foreach ($this->page_arr as $k => $v) {  
            $_GET['p'] = $k;  
            $pageStr.='<li><a href="'.$v.'">'.$k.'</a></li>';  
        }  

        if($this->currPage<$this->countPages){  
            $_GET['p'] = $this->currPage+1;  
            $pageStr.='<li><a href="'.$this->href.'?'.http_build_query($_GET).'">next</a></li>';  
        }  
        $_GET['p'] = $this->countPages;  
        $pageStr.='<li><a href="'.$this->href.'?'.http_build_query($_GET).'">end</a></li>'; 
        $pattern = sprintf("/<li>(?=<a href=\"[^><]*\">%d<\/a><\/li>)/",$this->currPage); 
        return preg_replace($pattern,"<li class='active'>",$pageStr);  
    }  


    protected function Create_Page(){  
        $this->countPages=ceil($this->count/$this->size);  
        $leftPage_num=ceil($this->showPages/2);  
        $rightPage_num=$this->showPages-$leftPage_num;  
        $left=$this->currPage-$leftPage_num;  
        $left=max($left,1); 
        $right=$left+$this->showPages-1; 
        $right=min($right,$this->countPages);  
        $left=max($right-$this->showPages+1,1);
        unset($_GET[$this->href]);
        for ($i=$left; $i <= $right; $i++) {  
            $_GET['p'] = $i;  
            $this->page_arr[$i]=$this->href.'?'.http_build_query($_GET);  
        }  
    }  
}  