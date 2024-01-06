<?php
use Illuminate\Support\Facades\File;
use App\Models\Admin\Media;
// use App\Model\Admin\Setting;
// use App\Model\Admin\Country;

if (! function_exists('imageUpload')) {

    function imageUpload($image, $referencedImageId, $userId, $path) {
   
        $imageName =  time() . "_" . $image->getClientOriginalName();

            $image->move(public_path($path), $imageName);  // Upload imgae to specified folder

            $mediaRecord = Media::orderBy('sortOrder')->where('userId', $userId)->where('id', $referencedImageId)->first();

            //Check if Media record exits for particular if not then add other wise update

            if (empty($mediaRecord)) {

                $media = new Media();

            } else {

                $media = Media::find($mediaRecord->id);

                $image_path = $path . $media->name;  // Deleting image from directory

                if (File::exists($image_path)) {
                    
                    File::delete($image_path);
                }
            }

            $media->name = $imageName;

            if (empty($mediaRecord)) {

                $media->userId = $userId;
                $media->status = 1;
                $media->sortOrder = 1;
                $media->increment('sortOrder');
            }

            $media->save();

            return $media->id;

    }
}




// /////////

// if (! function_exists('pdfUpload')) {

//     function pdfUpload($fileName,$userId) {
   
//             $media = new Media();
//             $media->name = $fileName;
//             $media->userId = $userId;
//             $media->status = 1;
//             $media->sortOrder = 1;
//             $media->increment('sortOrder');

//             $media->save();

//             return $media->id;

//     }
// }

// /////////

// if (! function_exists('getSettingByUser')) {

//     function getSettingByUser($userId) {
   
//             $userSettingData = Setting::where('userId', $userId)->orderBy('sortOrder')->first();

//             return $userSettingData;

//     }
// }


// /////////

// if (! function_exists('findCountryIdByName')) {

//     function findCountryIdByName($name) {

//         $country = Country::where('name', $name)->first();
        
//         return $country->id;

//     }
// }


?>