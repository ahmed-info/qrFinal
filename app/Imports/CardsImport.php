<?php

namespace App\Imports;

use App\Models\CompanyCard;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Company;
//, WithChunkReading,ShouldQueue
class CardsImport implements ToModel, WithHeadingRow
{
    private $companies;
    public function __construct()
    {
        $this->companies = Company::all();
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $company = $this->companies->where('company_name', $row['company_name'])->first();
        $row['release_date'] = session('dateRelease');
        $row['expiry_date'] = session('dateExpiry');
        $allData = $row['ss_num'].PHP_EOL.$row['full_name'].PHP_EOL.$row['gender'].PHP_EOL.$row['birth_date'].PHP_EOL.$row['release_date'].PHP_EOL.$row['expiry_date'].PHP_EOL.$row['national_number'].PHP_EOL.$row['mother_name'].PHP_EOL.$row['company_name'].PHP_EOL.$row['location'];
        ////////////////////////////////////////////
         $cipher = $this->lzw_compress($allData);
        $uncipher = $this->lzw_decompress($cipher);
        
        $dateRelease = date('Y-m-d');
        $dateExpiry = Carbon::createFromFormat('Y-m-d',$dateRelease)->addDays(364);
        return new CompanyCard([
            //header
        'ss_num'            => $row['ss_num'],
        'full_name'         => $row['full_name'],
        'gender'            => $row['gender'],
        'birth_date'        => $row['birth_date'],
        'release_date'      => session('dateRelease'),
        'expiry_date'       => session('dateExpiry'),
        'national_number'   => $row['national_number'],
        'mother_name'       => $row['mother_name'],
        'company_name'      => $row['company_name'],
        'location'          => $row['location'],
        'card_img'          => $row['card_img'],
        'qr_code'           => mb_convert_encoding($uncipher, "UTF-8"),
        'company_id'        => $company->id ?? null,

        
        ]);
    }

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

    // public function chunkSize(): int
    // {
    //     return 1000;
    // }
}
