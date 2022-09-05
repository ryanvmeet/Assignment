<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;



class UserController extends Controller
{
    /**
     * Function to save a new user
     */
    public function createUser(Request $request) {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'confirm' => 'required'
            ]
        );

        // check if email already exists

        // check if passwords are the same

        // create a new user
        $user = new User;

        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = $request->input("password");

        $user->save();

        return redirect("/login");
    }

    public function loginUser(Request $request) {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        $user = Auth::getProvider()->retrieveByCredentials($request->only(['email', 'password']));
        if(!$user){
            return redirect()->to('login')
                ->withErrors("user not found");
        }

        auth()->login($user);
        return redirect("profile");
        


    }

     /**
     * Function to update a user
     */
    public function updateUser(Request $request, User $user) {
        
    }

     /**
     * Function to delete a user
     */
    public function deleteUser(User $user) {
        $user->delete();

        return true;
    }
     /**
     * Function to upload a csv
     */
    public function uploadCsv(Request $request) {

        $user = auth()->user();
        $csv = array_map('str_getcsv', file($request->file('csv')));
        foreach($csv[0] as $row) {
            $values = explode(";", $row);
            
            $address = $this->validateAddress($values[0], $values[1], $values[2], $values[3], $values[4], $values[5]);

            if($address[0]->Matches[0]->AQI == "A") {
                $address = new Address;
                // $address->street = $values[0];
                $address->housenumber = $values[1];
                $address->additional = $values[2];
                $address->postal_code = $values[3];
                $address->country = $values[5];
                $address->user_id = $user->id;
                $address->save();
            }
        }

        return redirect()->back();
    }

    private function validateAddress(string $street, $number, $addition, $postal, $city, $country) {

        $url = urlencode($number . $addition . " " . $street . " " . $city);
        
        $data = [
            "Address" => "",
            "Address1"=> $street . " " . $number . $addition,
            "Address2"=> $city,
            "Address3"=>"",
            "Address4"=>"",
            "Address5"=>"",
            "Address6"=>"",
            "Address7"=>"",
            "Address8"=>"",
            "Country"=>$country,
            "SuperAdministrativeArea"=>"",
            "AdministrativeArea"=>"",
            "SubAdministrativeArea"=>"",
            "Locality"=>"",
            "DependentLocality"=>"",
            "DoubleDependentLocality"=>"",
            "Thoroughfare"=>"",
            "DependentThoroughfare"=>"",
            "Building"=>"",
            "Premise"=>"",
            "SubBuilding"=>"",
            "PostalCode"=>$postal,
            "Organization"=>"",
            "PostBox"=>"",
        ];

        $fullData = [
            'Key' => env("API_KEY"),
            'Geocode' => false,
            "Options"=> [
                "Process"=> "Verify",
                "Certify" => true
            ],
            'Addresses' => array($data)
        ];
        

        $response = Http::withBody(json_encode($fullData), 'application/json')->post('https://api.addressy.com/Cleansing/International/Batch/v1.00/json4.ws');

        $result = json_decode($response->body());

        return $result;

    }

    private function addAddressToUser() {

    }

     /**
     * Function to get a user with the addresses
     */
    public function getUser() {

        $user = auth()->user();
        return view("profile", ["user" => $user]);

    }

    public function logoutUser()
    {
        Session::flush();
        
        Auth::logout();

        return redirect('login');
    }
}
