<?php
function send_response_to_api($ArrData,$errors='',$success_message='')
{
     //header("Access-Control-Allow-Origin: *");
     
    if( (is_array($ArrData) && count($ArrData)>0) || $success_message!='') {
        $ArrResponse = array('is_successful' => '1' ,'error_code' => -1,'data' => $ArrData,'errors' =>'','success_message' =>$success_message); 
        $myJSON = json_encode($ArrResponse);
        header('Content-Type: application/json');
        echo $myJSON;
    }
    else{
        $ArrError = array('is_successful' => '0','error_code' => 400,'data' =>null,'errors' =>$errors,'success_message'=>'');
        $myJSON = json_encode($ArrError);
        header('Content-Type: application/json');
        echo $myJSON;
    }
}
function check_oauth_key($oauth_key)
{
    
    if($oauth_key!='F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT')
    {
        $errors = "Error: The api oAuth key is not valid.";
        
        $ArrError = array('is_successful' => '0','error_code' => 400,'data' =>null,'errors' =>$errors);
        $myJSON = json_encode($ArrError);
        header('Content-Type: application/json');
        echo $myJSON;
    }
    // else if(getallheaders()['Content-Type'] != "application/json")
    // {
    // 	$errors = "Request Must Be In JSON Format ";
        
    // 	$ArrError = array('is_successful' => '0','error_code' => 400,'data' =>null,'errors' =>$errors);
    // 	$myJSON = json_encode($ArrError);
    // 	header('Content-Type: application/json');
    // 	echo $myJSON;
    // }
    else
    {
        return true;
    }
}

function check_user_token($user_token)
{
    return true;
    /*if($user_token!='')
    {
        $ArrUser = $this->user_login_model->getUserByAPIToken($user_token);
        
        if(is_array($ArrUser) && count($ArrUser)>0)
        {
            return true;
        }
        else
        {
            $errors = "Error: The user token is not valid.";
        
            $ArrError = array('is_successful' => '0','error_code' => 400,'data' =>null,'errors' =>$errors);
            $myJSON = json_encode($ArrError);
            header('Content-Type: application/json');
            echo $myJSON;
        }
    }
    else
    {
        $errors = "Error: The user token is not valid.";
        
        $ArrError = array('is_successful' => '0','error_code' => 400,'data' =>null,'errors' =>$errors);
        $myJSON = json_encode($ArrError);
        header('Content-Type: application/json');
        echo $myJSON;
    }*/
}


/* VMS Helper */

function xssclean($post)
	{
		$rtn = true; 
		if($post) {
			foreach ($post as $key => $data) {
				if (preg_match("/</i", $data, $match))  {
   					$rtn = false; 
   				}
			}
		}
		return $rtn;
	}

	function output($data) 
	{
		if(isset($data)) {
			return html_escape($data);
		} else {
			return '';
		}
	}
	function pointInPolygon($point, $polygon, $pointOnVertex = true) {
	    $pointOnVertex = $pointOnVertex;
	    $point = pointStringToCoordinates($point);
	    $vertices = array(); 
	    foreach ($polygon as $vertex) {
	        $vertices[] = pointStringToCoordinates($vertex); 
	    }
	    if ($pointOnVertex == true and pointOnVertex($point, $vertices) == true) {
	        return "vertex";
	    }
	    $intersections = 0; 
	    $vertices_count = count($vertices);
	    for ($i=1; $i < $vertices_count; $i++) {
	        $vertex1 = $vertices[$i-1]; 
	        $vertex2 = $vertices[$i];
	        if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
	            return "boundary";
	        }
	        if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
	            $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
	            if ($xinters == $point['x']) { // Check if lat lng is on the polygon boundary (other than horizontal)
	                return "boundary";
	            }
	            if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
	                $intersections++; 
	            }
	        } 
	    } 
	    if ($intersections % 2 != 0) {
	        return "inside";
	    } else {
	        return "outside";
	    }
	}

	function pointOnVertex($point, $vertices) {
	  foreach($vertices as $vertex) {
	      if ($point == $vertex) {
	          return true;
	      }
	  }
	}
	function pointStringToCoordinates($pointString) {
	    $coordinates = explode(" ", $pointString);
	    return array("x" => $coordinates[0], "y" => $coordinates[1]);
	}

	function sitedata() {
	  $CI =& get_instance();
	  $CI->db->from('vms_settings');
	  $query = $CI->db->get();
	  $siteinfo = $query->result_array();
	  if(count($siteinfo)>=1) {
	    return $siteinfo[0];
	  } 
    }

    function userpermission($link) {
    	$permissons = $_SESSION['userroles'];
    	if($permissons[$link]==1) {
    		return true;
    	} else {
    		return false;
    	}
    }