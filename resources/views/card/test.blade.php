<!DOCTYPE html>
<?php 
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
        foreach ($codes as $i => $code) {
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
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj"
      crossorigin="anonymous"
    ></script>
    <title>Document</title>
</head>
<body>
    <div class="container mt-3">


      <br>
      <br>
      
      <form action="{{route('myEnc')}}" method="GET">
        <div class="row align-items-stretch mb-1">
          
          <h3 class="colorLogo">Encrypt</h3>
          <div class="form-group">
              <!-- Name input-->
              <input type="Search" class="form-control side-search"  name="myText" placeholder="Type the text to encrypt"/>

              <div class="invalid-feedback" data-sb-feedback="name:required">A name is required.</div>
          </div>
          <div class="col-md-12">
            <div class="text-left" style="position: inline-block;"><button class="btn btn-primary btn-xl mt-0"  id="enc">Encrypt</button></div>
        </div>

        <br>
          <div class="form-group mb-3">
              <!-- textarea input-->
             
              <textarea class="form-control" id="message" name="message" placeholder="@lang('Your Message *')" style="height: 112px">
                {{$cipher= ""}}
                @if(isset($_GET['myText']))
                {{ $encryptTxt }}
                @endif

              </textarea>
              <br>
              <br>
              <textarea class="form-control" id="message" name="message2" placeholder="@lang('Your Message *')" style="height: 112px">
                {{$cipher= ""}}
                @if(isset($_GET['myText']))
                {{ $decryptTxt }}
                @endif

              </textarea>
          </div>
      </form>


      

          
          

          

      
        
              
              
       
      
  </div>
        
    </div>

    <script>
      document.querySelector('#form1').addEventListener('submit', () => {
        const text = document.querySelector('#text').value 
        console.log(text)
        
      
      })
    </script>
</body>
</html>

