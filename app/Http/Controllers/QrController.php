<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class QrController extends Controller
{
    public function index()
    {
        //Hash::make($value)
        $allData = "12345678_عبد الرحمن عبد العزيز عبد الصمد عبد الرحمنذكر_1957-10-22_1947927273سارة عبد الرحمن عبد الرحمنشركة سما اليمامة للخدمات العامة وتكنولوجيا المعلومات العراقيةبغداد - الكرادة";
        //$cipher = $this->lzw_compress('احمد علي');
        //$uncipher = $this->lzw_decompress($cipher);


        //mb_convert_encoding($string, "UTF-8");
        //$decryptQR = QrCode::encoding('UTF-8')->size(100)->generate(mb_convert_encoding($uncipher, "UTF-8"));

        //$encryptTxt = mb_convert_encoding($cipher, "UTF-8");
        //$decryptTxt = mb_convert_encoding($uncipher, "UTF-8");

        //$encryptQR = QrCode::encoding('UTF-8')->size(100)->generate(mb_convert_encoding($cipher, "UTF-8"));
        return view('card.test');

        // if(isset($_GET['text'])){
        //     $text =  $_GET['text'];
        //     $cipher = $this->lzw_compress($text);
        //     //return $cipher;
        //     $qrs = QrCode::encoding('UTF-8')->size(100)->generate(mb_convert_encoding($text, "UTF-8"));
        //     return view('card.test', compact('qrs', 'cipher'));
        // }
        return view('card.test');

        


    }

    public function myEnc()
    {
        $text = $_GET['myText'];
       
            //$text =  $_POST['text'];
             session(['mytext' => $text]);
            
            $cipher = $this->lzw_compress($text);
            $uncipher = $this->lzw_decompress($cipher);

            //return $cipher;
            $encryptQR = QrCode::encoding('UTF-8')->size(100)->generate(mb_convert_encoding($cipher, "UTF-8"));
            $decryptQR = QrCode::encoding('UTF-8')->size(100)->generate(mb_convert_encoding($uncipher, "UTF-8"));
             $encryptTxt = mb_convert_encoding($cipher, "UTF-8");
            $decryptTxt = mb_convert_encoding($uncipher, "UTF-8");
           return view('card.test', compact('encryptQR', 'cipher','uncipher','decryptQR','encryptTxt','decryptTxt'));
    

        $cipher = $this->lzw_compress($text);
         $uncipher = $this->lzw_decompress($cipher);

            return view('card.test',  compact('cipher','uncipher','encryptTxt','decryptTxt','encryptQR','decryptQR'));
    }

    
    

    public function myDec()
    {
            //$textDec =  $_POST['text'];
            //return $textDec;
            $myTEXT1 = session('mytext');
            $text1 =  $this->lzw_compress($myTEXT1);
            $text2 =  $this->lzw_decompress($text1);


             $encryptQR = QrCode::encoding('UTF-8')->size(100)->generate(mb_convert_encoding($text1, "UTF-8"));
            $decryptQR = QrCode::encoding('UTF-8')->size(100)->generate(mb_convert_encoding($text2, "UTF-8"));
            $encryptTxt = mb_convert_encoding($text1, "UTF-8");
            $decryptTxt = mb_convert_encoding($text2, "UTF-8");
            return view('card.test', compact('encryptQR','decryptQR','encryptTxt','decryptTxt','text1','text2'));
        
    }


    public function pairsqwer()
    {
        //return $decom;
        $str100 = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec qu";
         $cipher = $this->compress($str100);
         $uncipher = $this->decompress($cipher);

        return $cipher;
    }

    //public $str2 = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec qu";
    //compress
    

    function lzw_compress($string) {
        // compression
        $dictionary = array_flip(range("\0", "\xFF"));
        $word = "";
        $codes = array();
        for ($i=0; $i <= strlen($string); $i++) {
            $x = substr($string, $i, 1);
            if (strlen($x) && isset($dictionary[$word . $x])) {
                $word .= $x;
            } elseif ($i) {
                $codes[] = $dictionary[$word];
                $dictionary[$word . $x] = count($dictionary);
                $word = $x;
            }
        }
        
        // convert codes to binary string
        $dictionary_count = 256;
        $bits = 8; // ceil(log($dictionary_count, 2))
        $return = "";
        $rest = 0;
        $rest_length = 0;
        foreach ($codes as $code) {
            $rest = ($rest << $bits) + $code;
            $rest_length += $bits;
            $dictionary_count++;
            if ($dictionary_count >> $bits) {
                $bits++;
            }
            while ($rest_length > 7) {
                $rest_length -= 8;
                $return .= chr($rest >> $rest_length);
                $rest &= (1 << $rest_length) - 1;
            }
        }
        return $return . ($rest_length ? chr($rest << (8 - $rest_length)) : "");
    }
    
    /** LZW decompression
    * @param string compressed binary data
    * @return string original data
    */
    function lzw_decompress($binary) {
        static $word;
        // convert binary string to codes
        $dictionary_count = 256;
        $bits = 8; // ceil(log($dictionary_count, 2))
        $codes = array();
        $rest = 0;
        $rest_length = 0;
        for ($i=0; $i < strlen($binary); $i++) {
            $rest = ($rest << 8) + ord($binary[$i]);
            $rest_length += 8;
            if ($rest_length >= $bits) {
                $rest_length -= $bits;
                $codes[] = $rest >> $rest_length;
                $rest &= (1 << $rest_length) - 1;
                $dictionary_count++;
                if ($dictionary_count >> $bits) {
                    $bits++;
                }
            }
        }
        
        // decompression
        $dictionary = range("\0", "\xFF");
        $return = "";
        foreach ($codes as $i=> $code) {
            $element = $dictionary[$code];
            if (!isset($element)) {
                $element = $word . $word[0];
            }
            $return .= $element;
            if ($i) {
                $dictionary[] = $word . $element[0];
            }
            $word = $element;
        }
        return $return;
    }
}
