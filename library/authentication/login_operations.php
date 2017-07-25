<?php
/**
 * This is a library of commonly used functions for managing data for authentication
 * 
 * Copyright (C) 2013 Kevin Yeh <kevin.y@integralemr.com> and OEMR <www.oemr.org>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Kevin Yeh <kevin.y@integralemr.com>
 * @link    http://www.open-emr.org
 */

require_once("$srcdir/authentication/common_operations.php");



/**
 * 
 * @param type $username
 * @param type $password    password is passed by reference so that it can be "cleared out"
 *                          as soon as we are done with it.
 * @param type $provider
 */
function validate_user_password($username,&$password,$provider)
{
    $ip=$_SERVER['REMOTE_ADDR'];
    //print_r($ip);
    //exit();
    $valid=false;
    $getUserSecureSQL= " SELECT " . implode(",",array(COL_ID,COL_PWD,COL_SALT))
                       ." FROM ".TBL_USERS_SECURE
                       ." WHERE ".COL_UNM."=?";
                       // Use binary keyword to require case sensitive username match
    
    $userSecure=privQuery($getUserSecureSQL,array($username));
    //print_r($userSecure);
    //exit();
    if(is_array($userSecure))
    {
        $phash=oemr_password_hash($password,$userSecure[COL_SALT]);
        //print_r($phash);
        //exit();
        if($phash!=$userSecure[COL_PWD])
        {
            
            return false;
        }
        $valid=true;
    }
    else
    {
        if((!isset($GLOBALS['password_compatibility'])||$GLOBALS['password_compatibility']))    // use old password scheme if allowed.
        {
            $getUserSQL="select username,id, password from users where username = ?";
            $userInfo = privQuery($getUserSQL,array($username));
            if($userInfo===false)
            {
                return false;
            }
                
            $username=$userInfo['username'];
            $dbPasswordLen=strlen($userInfo['password']);
            if($dbPasswordLen==32)
            {
                $phash=md5($password);
                $valid=$phash==$userInfo['password'];
            }
            else if($dbPasswordLen==40)
            {
                $phash=sha1($password);
                $valid=$phash==$userInfo['password'];
            }
            if($valid)
            {
                $phash=initializePassword($username,$userInfo['id'],$password);
                purgeCompatabilityPassword($username,$userInfo['id']);
                $_SESSION['relogin'] = true;
            }
            else
            {
                return false;
            }
        }
        
    }
    $getUserSQL="select id, authorized, see_auth".
                        ", cal_ui, active ".
                        " from users where username = ?";
    $userInfo = privQuery($getUserSQL,array($username));
     //print_r($userInfo);
     //exit();
    if ($userInfo['active'] != true) {
        newEvent( 'login', $username, $provider, 0, "failure: $ip. user not active or not found in users table");
        $password='';
     //   echo "ok";
        return false;
    }
    // exit();
    // Done with the cleartext password at this point!
    $password='';
    //print_r($valid);
    //exit();
    //$username='akmar';

    if($valid){
        //print_r($authGroup);
        //exit();
        /**
          ini command where
        */
            // print_r($username);
            // print_r($provider);
            // exit();
        if ($authGroup = privQuery("select * from groups where userr=? and name=?",array($username,$provider)))
        {
             // print_r($authGroup);
             // exit(); 
            $_SESSION['authUser'] = $username;
            $_SESSION['authPass'] = $phash;
            $_SESSION['authGroup'] = $authGroup['name'];
            $_SESSION['authUserID'] = $userInfo['id'];
            $_SESSION['authProvider'] = $provider;
            $_SESSION['authId'] = $userInfo{'id'};
            $_SESSION['cal_ui'] = $userInfo['cal_ui'];
            $_SESSION['userauthorized'] = $userInfo['authorized'];
             //print_r($userInfo['see_auth']);
             //exit();
            // Some users may be able to authorize without being providers:
            if ($userInfo['see_auth'] > '2') $_SESSION['userauthorized'] = '1';
            newEvent( 'login', $username, $provider, true, "success: $ip");
            $valid=true;
        } else {
            newEvent( 'login', $username, $provider, false, "failure: $ip. user not in group: $provider");
            $valid=false;
        }
        
        
        
    }
    return $valid;
}

function verify_user_gacl_group($user)
{
    global $phpgacl_location;
    if (isset ($phpgacl_location)) {
      if (acl_get_group_titles($user) == false) {
          newEvent( 'login', $user, $provider, false, "failure: $ip. user not in any phpGACL groups. (bad username?)");
	  return false;
      }
    }
    return true;
}
?>
