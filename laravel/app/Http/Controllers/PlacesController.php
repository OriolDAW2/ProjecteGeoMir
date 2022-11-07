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
        // FICHERO
        $validarDate = $request->validate([
            "category_id" => "required",
            "visibility_id" => "required",
            "name" => "required",
            "upload" => "required|mimes:jpeg,jpng,png,mp4|max:1024",
            "description" => "required",
            "latitude" => "required",
            "longitude" => "required",
        ]);

        //OBTENER DATOS FICHERO
        $category_id = $request->get("category_id");
        $visibility_id = $request->get("visibility_id");
        $name = $request->get("name");
        $upload = $request->file("upload");
        $description = $request->get("description");
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->GetSize();
        $latitude = $request->get("latitude");
        $longitude = $request->get("longitude");
        // \Log::debug($fileName, $Size)
        
        //SUBIR FICHERO
        $uploadName = time(). '_' .$fileName;
        $filePath = $upload->storeAs(
            "uploads",
            "public",
            $uploadName,
        );
        
        if (\Storage::disk("public")->exists(filePath)) {
            $fullPath = \Storage::disk('public')->path($filePath);

            // GUARDAR BASE DE DATOS
            $file = File::create([
                "filepath" => $filePath,
                "fileSize" => $fileSize,
            ]);

            $place = Place::create([
                "name" => $name,
                "description" => $description,
                "file_id" => $file_id,
                "latitude" => $latitude,
                "longitude" => $longitude,
                "category_id" => $category_id,
                "visibility_id" => $visibility_id,
                "author_id" => $author_id,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        //
        $file=File::find($place->file_id);
        return view("places.show", [
            "place" => $place,
            "file" => $file,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        //
        $file=File::find($place->file_id);
        return view("places.edit", [
            "place" => $place,
            "file" => $file,
        ]);
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
        //
        $validateData = $request->validate([
            "upload" => "mimes:jpeg,jpng,png,mp4|max:1024",
        ]);

        $file=File::find($place->file_id);

        $upload = $request->file("upload");
        $controlNull = false;

        if (! is_null($upload)) {
            $fileName = $upload->getClientOriginalName();
            $fileSize = $upload->getSize();

            \Log::debug($fileName,$fileSize);

            $uploadName = time() . "_" . $fileName;
            $filePath = $upload->storeAs(
                "uploads",
                "public",
                $uploadName,
            );
        }
        else{
            $filePath = $file->filepath;
            $fileSize = $file->filesize;
            $controlNull = true;
        }

        if (\Storage::disk("public")->exists($filePath)) {
            if ($controlNull == false) {
                \Storage::disk("public")->delete($file->filepath);
                \Log::debug("Todo bien");
                $fullPath = \Storage::disk("public")->path($filepath);
                \Log::debug("Archivo guardado ",$fullPath);

            }

            // GUARDAR BASE DA DATOS
            $file->filepath=$filePath;
            $file->filesize=$fileSize;
            file->save();
            $place->name=$request->input("name");
            $place->description=$request->input("description");
            $place->latitude=$request->input("latitude");
            $place->longitude=$request->input("longitude");
            $place->category_id=$request->input("category_id");
            $place->visibility_id=$request->input("visibility_id");
            $place->save();

            return redirect()->route("places.edit", $place)->with('Error places guardar');
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
        \Storage::disk("public")->delete($place-> id);
        $place->delete();
        \Storage::disk("public")->delete($file-> filepath);
        $file->delete();

        if (condition) {
            return redirect()->route("places.show", $place)->with('Error, places Existe');
        }
        else{
            \Log::debug("Place borrado");
            return redirect()->route("places.index", $place)->with('Error, places Borrado');
        }
    }
}
