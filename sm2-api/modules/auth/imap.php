<?php

/* modules/auth/imap.php
 * Squirrelmail2 API
 * Copyright (c) 2001 Philippe Mingo
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: imap.php 1604 2001-10-24 10:10:32Z philippe_mingo $
 */

/**
 * ZkMod_auth_imap
 *
 */
class ZkMod_auth_imap {

    var $ver = '$Id: imap.php 1604 2001-10-24 10:10:32Z philippe_mingo $';
    var $name = 'auth/imap';

    var $srv;	  // backward pointer to the service
    var $info;	  // cargo
    var $banner;  // Server banner
    
    /**
     * Create a new ZkMod_auth_test with the given options.
     *
     * @param array $options an associative array that can pass options
     *                       to the authentication module
     */
    function ZkMod_auth_imap( $options, &$srv ) {
        $this->srv = &$srv;
    }

    /**
     * Check a username/password pair.
     *
     * @param string $username username with which to authenticate
     * @param string $password password with which to authenticate
     * @return bool indicates correct or incorrect password
     */
    function checkPassword( $username, $password ) {

        if( is_array( $this->srv->connector ) ) {
            
            if( $this->srv->connector['timeout'] == '' ) {
                $timeout = 15;
            } else {
                $timeout = $this->srv->connector['timeout'];
            }
            
            $sp = fsockopen( $this->srv->connector['host'], 
                             $this->srv->connector['port'],
                             $error_number, $error_string, 
                             $timeout );
            
            $this->info = "<b>Imap Session</b><br>";
            if( $sp ) {        
                socket_set_timeout( $sp, $timeout );
                $ret = TRUE;
                $this->banner = fgets( $sp, 1024 );
                // Check compatibilities in here
                // Identifies the user
                $ret = $this->query( $sp, 'LOGIN "' . quoteIMAP($username) .
                                     '" "' . quoteIMAP($password) . '"' );
                $this->query( $sp, 'LOGOUT' );
            } else {
                $this->info = 'Could not connect.';
                $ret = FALSE;
            }
            
        } else {
            $ret = FALSE;
        }
        
        return( $ret );
    }
    
    function query( $sp, $cmd ) {

        $buffer = 'AAA';
        $a = array( '*', 'NOPE' );
        $isid = substr( session_id(), -4 );
        if( $isid == '' ) {
            // Not sessionized
            $isid = rand( 1000, 9999 );
        }
        
        $this->info .= '<p><i>' . $cmd . '</i> ';
        
        fputs( $sp, $isid . ' ' . $cmd . "\r\n" );
        
        while( !eregi( ".*$a[1]-", 'OK-BAD-NO-' ) && 
               $buffer <> '' ) {
            $buffer = fgets( $sp, 1024 );
            $a = explode( ' ', $buffer );
            $this->info .= $buffer . '<br>';
        }
        
        $this->info .= '</p>';
        return( $a[1] == 'OK' );
    }    
    
}

?>