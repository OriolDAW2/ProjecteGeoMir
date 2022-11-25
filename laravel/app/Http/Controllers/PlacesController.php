<?php

namespace App\Http\Controllers;

// use App
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Place;
use Illuminate\Http\UploadedFile;

class PlacesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("places.index", [
            "places" => Place::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("places.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // VALIDAR CAMPOS
        $validarDate = $request->validate([
            "name" => "required",
            "upload" => "required|mimes:jpeg,jpng,png,mp4|max:1024",
            "description" => "required",
            "latitude" => "required",
            "longitude" => "required",
        ]);

        //OBTENER DATOS POR POST
        $name = $request->get("name");
        $upload = $request->file("upload");
        $description = $request->get("description");
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->GetSize();
        $latitude = $request->get("latitude");
        $longitude = $request->get("longitude");
        $visibility_id = $request->get("visibility_id");
        // \Log::debug($fileName, $Size);
        // \Log::debug("visibility_id", $visibility_id);
        
        // Pujar fitxer al disc dur
        $uploadName = time() . '_' . $fileName;
        $filePath = $upload->storeAs(
            'uploads',      // Path
            $uploadName ,   // Filename
            'public'        // Disk
        );
        
        if (\Storage::disk("public")->exists($filePath)) {
            $fullPath = \Storage::disk('public')->path($filePath);

            // GUARDAR BASE DE DATOS
            $file = File::create([
                "filepath" => $filePath,
                "filesize" => $fileSize,
            ]);

            $place = Place::create([
                'name' => $name,
                'description' => $description,
                'file_id' => $file->id,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'author_id' => auth()->user()->id,
                'visibility_id' => $visibility_id,
            ]);
            \Log::debug("Base de Datos storage BIEN!!!");
            return redirect()->route("places.show", $place)->with('Hecho places!');
        } else {
            \Log::debug("storage ha fallado");
            return redirect()->route("places.create", $place)->with('Error places');

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        return view("places.show", [
            "place" => $place,
            "file" => $place->file(),
            "user" => $place->user()
        ])->with('success', __('Place Exist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        //
        return view("places.edit", [
            "place" => $place,
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        // Validar Place
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $file = File::find($place->file_id);

        // Obtenir dades del Post
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
            // Patró PRG amb missatge d'èxit
            return redirect()->route('places.show', $place)
            ->with('success', __('Place successfully updated'));
        } else {
            \Log::debug("Local storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("places.create")
            ->with('error', __('ERROR uploading place'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        //
        $file=File::find($place->file_id);
        \Storage::disk("public")->delete($place->id);
        $place->delete();
        \Storage::disk("public")->delete($file->filepath);
        $file->delete();

        if (\Storage::disk('public')->exists($file->filepath)) {
            \Log::debug("Place Alredy Exist");
            return redirect()->route('places.show', $place)
            ->with('error', __('ERROR place alredy exist'));
        }else{
            \Log::debug("Place Delete");
            return redirect()->route("places.index")
            ->with('success', __('Place Deleted'));
        }
    }
    
}
