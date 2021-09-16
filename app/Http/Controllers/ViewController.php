<?php
namespace App\Http\Controllers;
require_once "C:/laragon/www/labor/vendor/autoload.php";

use App\Exports\CardsExport;
use App\Models\Admin;
use App\Models\Company;
use App\Models\CompanyCard;
use App\Models\User;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Str;
use Fernet\Fernet;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use MNC\Fernet\Vx80Marshaller;
use MNC\Fernet\Vx80Key;
use function PHPSTORM_META\type;
use function Psy\bin;

//require 'vendor/autoload.php';



class ViewController extends Controller
{

    public function index(Request $req)
    {
        $faker = Factory::create();
        $method = $req->method();
        if ($req->isMethod('post'))
        {
            $from = $req->input('from');
            if ($req->has('search'))
            {
                // select search
                $cards = DB::select("SELECT * FROM company_cards WHERE company_name = '$from'");
                $companyDistinct = Company::all();
                $companies = $companyDistinct->unique('company_name');
                //return $cards;

                return view('card.select',compact('cards', 'companies'));
            } 


            
            elseif($req->has('exportExcel'))
                        
                // select Excel
                $this->validate($req,[
                    'qr_code'=>'nullable',
                    'ss_num'=>'number|unique'
                 ]);
                 $companyCards = CompanyCard::all();
                 foreach($companyCards as $companyCard){
                    //$dateNow = Carbon::createFormat('Y-m-d');
                    $dateRelease = date('Y-m-d');
                    $dateExpiry = Carbon::createFromFormat('Y-m-d',$dateRelease)->addDays(364);
                    //add date release and expiry
                    $companyCard->release_date = $dateRelease;
                    $companyCard->expiry_date = $dateExpiry;
                    $companyCard->save();
                 }
                 //$companyName = $companyCards[0]->company_name;
                 
                //  $search_text = $_GET['form'];
            // return Excel::download(new CardsExport, 'F:\myExport\Export_Excel_'.date('Y-m-d').'.xlsx');
            Excel::store(new CardsExport($from), 'Export_excel_'.date('Y-m-d').'.xlsx');
            return redirect()->route('card.index')->with([
                'message' => 'Exporting started successfully',
                 'alert-type' =>'success'
             ]);
            {
        } 
        }
        else
        {
            //select all
            $cards = DB::select('SELECT * FROM company_cards');
            $cards = CompanyCard::paginate();
            $companyDistinct = Company::all();
            $companies = $companyDistinct->unique('company_name');

            return view('card.select',['cards' => $cards, 'companies' => $companies]);
        }
    }

    public function logAdminIndex(Request $request)
    {
        return view('card.logAdminIndex');
    }

    public function logUserIndex(Request $request)
    {
        return view('card.logUserIndex');
    }
    

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'qr_code'=>'nullable',
         ]);
         $companyCard = CompanyCard::find($id);
         $companyCard->qr_code = Hash::make($request->qr_code);
          $companyCard->save();
         return redirect()->route('card.select');

    }

    public function search()
    {
        $search_text = $_GET['getSearch'];
        return $search_text;
        $companyDistinct = Company::where('company_name','LIKE','%'. $search_text.'%')->get();
        $companies =$companyDistinct->unique('company_name');
        //$companies = Company::all();

        $cards = CompanyCard::where('company_name','LIKE','%'. $search_text.'%')->paginate();

        return view('card.search', compact('cards', 'companies'));
    }

    public function getCompany()
    {
        $getCompany = $_GET['getCompany'];
        return $getCompany;
        

        return view('card.search', compact('cards', 'companies'));
    }
    public function login()
    {
        return view('myauth.login');
    }

    public function logout()
    {
        if(session()->has('LoggedUser')){
            session()->pull('LoggedUser');
            return redirect()->route('myauth.login');
        }
    }

    public function check(Request $request)
    {
        $dateRelease = date('Y-m-d');
        $dateExpiry = Carbon::createFromFormat('Y-m-d',$dateRelease)->addDays(364);
        
        session(['dateRelease' => $dateRelease]);
        session(['dateExpiry' => $dateExpiry]);
        //$request->session()->get('email');

        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:5|max:12'
        ]);

        $adminInfo = Admin::where('email','=' ,$request->email)->first();
        $userInfo = User::where('email','=' ,$request->email)->first();
        if(!$adminInfo && (!$userInfo)){
            return back()->with('fail', 'we dont recongnize your email address');
        }
        elseif($adminInfo){
            if(Hash::check($request->password, $adminInfo->password)){
                $request->session()->put('LoggedUser', $adminInfo->id);
                session(['email' => 'admin@admin.com']);
                //dd($data);
                $request->session()->get('email');

                return redirect()->route('log.admin.index');
            }
            
            return back()->with('fail', 'incorrect password');
        
        }

        //////////////////////////////////////////////////////////

        if(!$adminInfo && !$userInfo){
            return back()->with('fail', 'we dont recongnize your email address');
        }elseif($userInfo){
            if(Hash::check($request->password, $userInfo->password)){
                $request->session()->put('LoggedUser', $userInfo->id);
                session(['email' => 'user@user.com']);
                $request->session()->get('email');

                return redirect()->route('log.user.index');
            }
            
            return back()->with('fail', 'incorrect password');
        
        }
    }

    function test(){
        $str = 'my name is ahmed i am programmer in sama alyamama company , i live in iraq baghdad , my age 28 years old, iam gradute collage depaartment inforamation technology';

        //less letters count
        /**======================ghazwan========================*/

        $compressed = gzcompress($str, 9);      //1
        $encrypted = encrypt($compressed);      //2
        $mydecrypt = decrypt($encrypted);       //3
        $toOrginal = gzuncompress($mydecrypt);  //4
        // //return $toOrginal;
        //  $myStr = '12345678_عبد الرحمن عبد العزيز عبد الصمد عبد الرحمنذكر_1957-10-22_1947927273سارة عبد الرحمن عبد الرحمنشركة سما اليمامة للخدمات العامة وتكنولوجيا المعلومات العراقيةبغداد - الكرادة';
        //  $token = Crypt::encryptString($myStr);
        //  $myenc = substr($token, -15);
        //  return $myenc;
        
        /*========================== Ahmed ==============================*/
        $str = "Ahmed";
        $hex1 = bin2hex($str);
        $hex2 = bin2hex($str);
        //return $hex1.'<br>'.$hex2;
        //////////////////////////////////////////////////////////////////////
         $encrypt = bin2hex($str);
            $compress1 = gzcompress($encrypt);
             //return Str::length($compress1);        
        $qr = QrCode::errorCorrection('L')->size(250)->encoding('UTF-8')->generate($encrypted);
        $dateRelease = date('Y-m-d');

        $dateExpiry = Carbon::createFromFormat('Y-m-d',$dateRelease)->addDays(364);
        return $dateRelease.'<br>'.$dateExpiry;
        return view('card.test', compact('qr'));
    }
    public function myqr()
    {
        return view('myqr');
    }

    //public static $alphabet = "abcdefghijklmnopqrstuvwxyz";
    //public static $originalString = "ahmedali";
    //public static $arr = str_split($originalString,1);
    // public function encrypt($arr)
    // {
    //     static $alphabet = "abcdefghijklmnopqrstuvwxyz";
    //     $returnString = "";
    //         $shift = strlen($alphabet);

    //         foreach ($arr as $c)
    //         {
    //             $nextIndex = strpos($alphabet,$c) + $shift;

    //             if ($nextIndex > strlen($alphabet))
    //                 $nextIndex = $nextIndex - strlen($alphabet);

    //             $returnString += $alphabet[$nextIndex];
    //             $shift = strpos($alphabet,$alphabet[$nextIndex]);
    //         }

    //         return $returnString;
    // }
    
    // public static function myfernet()
    // {
    //     $str = "عبد الرحمن عبد العزيز عبد الكريم عبد الباقي";
    //     $myBase64 =  base64_encode($str);
    //     $key = [$myBase64];
    //     $dfdgv = $dfdgv = new Fernet($myBase64);

    // }

    public function pairsqwer()
    {
        //return $decom;
        $str100 = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec qu";
         $cipher = $this->compress($str100);
         $uncipher = $this->decompress($cipher);

        return $cipher;
    }

    public $str2 = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec qu";
    //compress
    public function compress($input, $ascii_offset = 38){
        $input = strtoupper($input);
        $output = '';
        //We can try for a 4:3 (8:6) compression (roughly), 24 bits for 4 chars
        foreach(str_split($input, 4) as $chunk) {
            $chunk = str_pad($chunk, 4, '=');
    
            $int_24 = 0;
            for($i=0; $i<4; $i++){
                //Shift the output to the left 6 bits
                $int_24 <<= 6;
    
                //Add the next 6 bits
                //Discard the leading ascii chars, i.e make
                $int_24 |= (ord($chunk[$i]) - $ascii_offset) & 0b111111;
            }
    
            //Here we take the 4 sets of 6 apart in 3 sets of 8
            for($i=0; $i<3; $i++) {
                $output = pack('C', $int_24) . $output;
                $int_24 >>= 8;
            }
        }
    
        return $output;
    }
    
    //decompress
    function decompress($input, $ascii_offset = 38) {
    
        $output = '';
        foreach(str_split($input, 3) as $chunk) {
    
            //Reassemble the 24 bit ints from 3 bytes
            $int_24 = 0;
            foreach(unpack('C*', $chunk) as $char) {
                $int_24 <<= 8;
                $int_24 |= $char & 0b11111111;
            }
    
            //Expand the 24 bits to 4 sets of 6, and take their character values
            for($i = 0; $i < 4; $i++) {
                $output = chr($ascii_offset + ($int_24 & 0b111111)) . $output;
                $int_24 >>= 6;
            }
        }
    
        //Make lowercase again and trim off the padding.
        return strtolower(rtrim($output, '='));
    }

    // static $compr = compress($str);

    // static $decom = decompress($compr);


}
