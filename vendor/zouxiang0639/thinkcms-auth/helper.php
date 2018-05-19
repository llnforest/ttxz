<?php
	/**
	 * @param string    $path
	 * @param array     $param
	 * @return bool
	 */
	function checkPath($path,$param=[]){
	    $result =  \thinkcms\auth\Auth::checkPath($path,$param);
	    return $result;
	}

    function checkAdmin(){
        $result =  \thinkcms\auth\Auth::checkAdmin();
        return $result;
    }
?>