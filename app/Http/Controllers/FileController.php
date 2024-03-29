<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FileController extends Controller
{   
    static $default = 'avatar.avif';
    static $diskName = 'storeFiles';

    static $systemTypes = [
        'profiles' => ['png', 'jpg', 'jpeg', 'avif', 'webp'],
    ];

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }
    
    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }
    
    private static function getFileName (String $type, int $id) {   
        $fileName = null;
        switch($type) {
            case 'profiles':
                $fileName = User::find($id)->profile_image;
                break;
            }
    
        return $fileName;
    }
    
    static function get(String $type, int $userId) {
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type . '/' . $fileName);
        }
        return self::defaultAsset($type);
    }
    
    function upload(Request $request) {
        $file = $request->file('file');
        $type = $request->type;
        $id = $request->id;
        $extension = $file->getClientOriginalExtension();

        $fileName = $file->hashName(); 
        $user = User::find($id);
        $user->profile_image = $fileName;
        $user->save();
    
        $request->file->storeAs($type, $fileName, self::$diskName);
        return redirect()->back();
    }    
}
