<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{

    public function searchEnc()
    {
        if(isset($_GET['querySearch'])){
        $text = $_GET['querySearch'];
        $str = $this->encryptTxt($text);
        return view('card.research', compact('str'));
        }
        return view('card.research');

        //$text = "احمد عبد الرزاق يحيى";
    }

    public function searchDEC()
    {
        if(isset($_GET['queryDec'])){
        $txtEnc = $_GET['queryDec'];
        $strDec = $this->decryptTxt($txtEnc);
        return view('card.research', compact('strDec'));
        }
        return view('card.research');

        //$text = "احمد عبد الرزاق يحيى";
    }



    public function encryptTxt($string)
    {
     $st = "";
     //int x = st.Count();
     //$string = "قرآن";
     
     $charArr = mb_str_split($string,1, "UTF-8");
     foreach ($charArr as $ch)
     {
         switch ($ch)
         {
             case ' ':
                 $st .= "*";
                 break;
             case 'ا':
                 $st .= "A";
                 break;
             case 'ب':
                 $st .= "B";
                 break;
             case 'ت':
                 $st .= "C";
                 break;
             case 'ث':
                 $st .= "D";
                 break;
             case 'ج':
                 $st .= "E";
                 break;
             case 'ح':
                 $st .= "F";
                 break;
             case 'خ':
                 $st .= "G";
                 break;
             case 'د':
                 $st .= "H";
                 break;
             case 'ذ':
                 $st .= "I";
                 break;
             case 'ر':
                 $st .= "J";
                 break;
             case 'ز':
                 $st .= "K";
                 break;
             case 'س':
                 $st .= "L";
                 break;
             case 'ش':
                 $st .= "M";
                 break;
             case 'ص':
                 $st .= "N";
                 break;
             case 'ض':
                 $st .= "O";
                 break;
             case 'ط':
                 $st .= "P";
                 break;
             case 'ظ':
                 $st .= "Q";
                 break;
             case 'ع':
                 $st .= "R";
                 break;
             case 'غ':
                 $st .= "S";
                 break;
             case 'ف':
                 $st .= "T";
                 break;
             case 'ق':
                 $st .= "U";
                 break;
             case 'ك':
                 $st .= "V";
                 break;
             case 'ل':
                 $st .= "W";
                 break;
             case 'م':
                 $st .= "X";
                 break;
             case 'ن':
                 $st .= "Y";
                 break;
             case 'ه':
                 $st .= "Z";
                 break;
             case 'و':
                 $st .= "a";
                 break;
             case 'ي':
                 $st .= "b";
                 break;
             case 'أ':
                 $st .= "c";
                 break;
             case 'إ':
                 $st .= "d";
                 break;
             case 'ء':
                 $st .= "e";
                 break;
             case 'ئ':
                 $st .= "f";
                 break;
             case 'ى':
                 $st .= "g";
                 break;
             case 'ؤ':
                 $st .= "h";
                 break;
             case 'آ':
                 $st .= "i";
                 break;
             case 'ة':
                 $st .= "j";
                 break;
             default:
                 echo "Error";
                 break;
         }
     }
     $strEncrypt = "";
     for ($i = 0; $i < count($charArr); $i++)
     {
          $strEncrypt = $st;
     }
     return $strEncrypt;
    }
 
    public function decryptTxt($encryptString)
    {
     $st = "";
     //int x = st.Count();
     
     $charArr = mb_str_split($encryptString,1, "UTF-8");
     foreach ($charArr as $ch)
     {
         switch ($ch)
         {
             case '*':
                 $st .= " ";
                 break;
             case 'A':
                 $st .= "ا";
                 break;
             case 'B':
                 $st .= "ب";
                 break;
             case 'C':
                 $st .= "ت";
                 break;
             case 'D':
                 $st .= "ث";
                 break;
             case 'E':
                 $st .= "ج";
                 break;
             case 'F':
                 $st .= "ح";
                 break;
             case 'G':
                 $st .= "خ";
                 break;
             case 'H':
                 $st .= "د";
                 break;
             case 'I':
                 $st .= "ذ";
                 break;
             case 'J':
                 $st .= "ر";
                 break;
             case 'K':
                 $st .= "ز";
                 break;
             case 'L':
                 $st .= "س";
                 break;
             case 'M':
                 $st .= "ش";
                 break;
             case 'N':
                 $st .= "ص";
                 break;
             case 'O':
                 $st .= "ض";
                 break;
             case 'P':
                 $st .= "ط";
                 break;
             case 'Q':
                 $st .= "ظ";
                 break;
             case 'R':
                 $st .= "ع";
                 break;
             case 'S':
                 $st .= "غ";
                 break;
             case 'T':
                 $st .= "ف";
                 break;
             case 'U':
                 $st .= "ق";
                 break;
             case 'V':
                 $st .= "ك";
                 break;
             case 'W':
                 $st .= "ل";
                 break;
             case 'X':
                 $st .= "م";
                 break;
             case 'Y':
                 $st .= "ن";
                 break;
             case 'Z':
                 $st .= "ه";
                 break;
             case 'a':
                 $st .= "و";
                 break;
             case 'b':
                 $st .= "ي";
                 break;
             case 'c':
                 $st .= "أ";
                 break;
             case 'd':
                 $st .= "إ";
                 break;
             case 'e':
                 $st .= "ء";
                 break;
             case 'f':
                 $st .= "ئ";
                 break;
             case 'g':
                 $st .= "ى";
                 break;
             case 'h':
                 $st .= "ؤ";
                 break;
             case 'i':
                 $st.= "آ";
                 break;
             case 'j':
                 $st.= "ة";
                 break;
             default:
                 echo "Error";
                 break;
         }
     }
     $strDecrypt = "";
     for ($i = 0; $i < count($charArr); $i++)
     {
          $strDecrypt = $st;
     }
     return $strDecrypt;
    }

    // public function lessChar()
    // { 
    //     $a="2y$10d96k65BVHMTdYS.LKCbfaur3M3oYv.85za9//iGoJNx5LJzkckegi";
    //     $strToChar = str_split($a);
    //     foreach($strToChar as $item){
    //         //echo str_repeat($item,2);
            
    //     }
    //     $string = "one,two,three,four";
    //     $arr = explode(",", $string);
    //     print_r($arr);

    // }

    /////////////////////////////////////
    // public function lessCharOld()
    // {
    //     //$strTxt = "$2y$10$d96k65BVHMTdYS.LKCbfaur3M3oYv.85za9//iGoJNx5LJzkckegi";
    //     $strTxt ="Ahmed Razzaq";
    //     $len = Str::length($strTxt);

    //     for($i=0; $i< $len; $i++){
    //         if($strTxt[strlen($strTxt)-1] != $strTxt[$i]){
    //                             //get not repeat char
    //         if($strTxt[$i] != $strTxt[$i+1]){
    //             echo $strTxt[$i]."<br>";
    //         }

    //         //get repeat char
    //         if($strTxt[$i] == $strTxt[$i+1]){
    //             $rep = $strTxt[$i];
    //             $strToChar = str_split($strTxt);
    //             foreach($strToChar as $key => $item){
    //                 if($rep == $item){
    //                     echo $rep.' in the index= '. $key .'<br>';
    //                     //break;
    //                 }
    //             }

    //         }
    //         }else{
    //             break;
    //         }  
    //     }  


         
    //     //get last char
    //    // echo "last char: ". $strTxt[strlen($strTxt)-1];
 
        
    // }

}
