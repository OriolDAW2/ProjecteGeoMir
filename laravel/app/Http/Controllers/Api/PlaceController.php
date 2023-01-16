<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\File;
use App\Models\User;


class PlaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store');
        $this->middleware('auth:sanctum')->only('update');
        $this->middleware('auth:sanctum')->only('destroy');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $places = Place::all();
        return response()->json([
            'success' => true,
            'data' => $places,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar fitxer
        $validatedData = $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:2048'
        ]);

        // Obtenir dades del fitxer
        $upload = $request->file('upload');
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->getSize();
        $placeName = $request->get('name'); 
        $placeDescription = $request->get('description');
        $placeLatitude = $request->get('latitude');
        $placeLongitude = $request->get('longitude'); 
        $visibility_id = $request->get('visibility_id');
        \Log::debug("Storing file '{$fileName}' ($fileSize)...");
 
        // Pujar fitxer al disc dur
        $uploadName = time() . '_' . $fileName;
        $filePath = $upload->storeAs(
            'uploads',      // Path
            $uploadName ,   // Filename
            'public'        // Disk
        );
      
        if (\Storage::disk('public')->exists($filePath)) {
            \Log::debug("Local storage OK");
            $fullPath = \Storage::disk('public')->path($filePath);
            \Log::debug("File saved at {$fullPath}");

            // Desar dades a BD
            $file = File::create([
                'filepath' => $filePath,
                'filesize' => $fileSize,
            ]);
            $place = Place::create([
                'name' => $placeName,
                'description' => $placeDescription,
                'latitude' => $placeLatitude,
                'longitude' => $placeLongitude,
                'file_id' => $file->id,
                'author_id' => auth()->user()->id,
                'visibility_id' => $visibility_id,
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return response()->json([
                'success' => true,
                'data'    => $place
            ], 201);
        } else {
            \Log::debug("Local storage FAILS");
            return response()->json([
                'success'  => false,
                'message' => 'Error uploading place'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $place = Place::find($id);
        if($place == null)
        {
            return response()->json([
                'success'  => false,
                'message' => 'Error place not found'
            ], 404);
        } else {
            return response()->json([
                'success' => true,
                'data'    => $place
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $place = Place::find($id);

        if($place)
        {
            $file = File::find($place->file_id);
            // Validar fitxer
            $validatedData = $request->validate([
                'upload' => 'required|mimes:gif,jpeg,jpg,png|max:2048'
            ]);

            // Obtenir dades del fitxer
            $upload = $request->file('upload');
            $fileName = $upload->getClientOriginalName();
            $fileSize = $upload->getSize();
            $placeName = $request->get('name'); 
            $placeDescription = $request->get('description'); 
            $placeLatitude = $request->get('latitude');
            $placeLongitude = $request->get('longitude'); 
            $visibility_id = $request->get('visibility_id');
            \Log::debug("Storing file '{$fileName}' ($fileSize)...");
    
            // Pujar fitxer al disc dur
            $uploadName = time() . '_' . $fileName;
            $filePath = $upload->storeAs(
                'uploads',      // Path
                $uploadName ,   // Filename
                'public'        // Disk
            );
            if(\Storage::disk('public')->exists($filePath)){
                \Log::debug("Local storage OK");
                $fullPath = \Storage::disk('public')->path($filePath);
                \Log::debug("File saved at {$fullPath}");
                // Desar dades a BD
                $file->filepath = $filePath;
                $file->filesize = $fileSize;
                $file->save();

                $place->name = $placeName;
                $place->description = $placeDescription;
                $place->latitude = $placeLatitude;
                $place->longitude = $placeLongitude;
                $place->visibility_id = $visibility_id;
                $place->save();
                \Log::debug("DB storage OK");
                return response()->json([
                    'success' => true,
                    'data'    => $place
                ], 201);
            } else {
                \Log::debug("Local storage FAILS");
                return response()->json([
                    'success'  => false,
                    'message' => 'Error uploading place'
                ], 500);
            }
        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Error place not found'
            ], 404);
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $place = Place::find($id);
        
        if($place == null)
        {
            return response()->json([
                'success'  => false,
                'message' => 'Error Place not found'
            ], 404);
        }else{
            $file = File::find($place->file_id);
            \Storage::disk('public')->delete($place->id);
            $place->delete();
            return response()->json([
                'success' => true,
                'data'    => $place
            ], 200);
        }

        if ($file == null) {
            \Log::debug(" Alredy Exist");
            return response()->json([
                'success'  => false,
                'message' => 'Error place exist'
            ], 404);
        }else{
            \Storage::disk('public')->delete($file->filepath);
            $file->delete();
            \Log::debug("Place Delete");
            return response()->json([
                'success' => true,
                'data'    => $place
            ], 200);
        }  
    }

    public function favorite($id)
    {
        $place=place::find($id);
        if (Favorite::where([
                ['user_id', "=" , auth()->user()->id],
                ['place_id', "=" ,$id],
            ])->exists()) {
            return response()->json([
                'success'  => false,
                'message' => 'The place is already favorite'
            ], 500);
        }else{
            $favorite = favorite::create([
                'id_user' => auth()->user()->id,
                'id_place' => $place->id,
            ]);
            return response()->json([
                'success' => true,
                'data'    => $favorite
            ], 200);
        }        
    }

    public function unfavorite($id)
    {
        $place=place::find($id);
        if (favorite::where([['user_id', "=" ,auth()->user()->id],['place_id', "=" ,$place->id],])->exists()) {
            
            $favorite = favorite::where([
                ['user_id', "=" ,auth()->user()->id],
                ['place_id', "=" ,$id],
            ]);
            $favorite->first();
    
            $favorite->delete();

            return response()->json([
                'success' => true,
                'data'    => $place
            ], 200);
        }else{
            return response()->json([
                'success'  => false,
                'message' => 'The place is not favorite'
            ], 500);
            
        }  
        
    }
}
