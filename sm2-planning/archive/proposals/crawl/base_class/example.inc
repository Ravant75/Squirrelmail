<?php
/*
 *  $Id: example.inc 4113 2002-11-06 04:17:06Z sizzlingmercury $
 *  $Author: sizzlingmercury $
 *  $Date: 2002-11-06 05:17:06 +0100 (mié 06 de nov de 2002) $
 *
 *   example.inc
 *   an example of a core file
 */
 
   class example extends sq {
   
//      var $string
   
   // now you have the functions we defined
   // the session is already started
   // The vars we always need are already here
   // Lets do something stupid with them.
   
      function example($string) { // start constructor
      
         // print the reverse of the string
         $string = strrev($string);
         print $string;
      } // end constructor
      
      function done(){ // start done
      
         // some other function
         print "I am done";
      } // end done
      
   } // end example
               

