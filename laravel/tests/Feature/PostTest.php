<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use Laravel\Sanctum\Sanctum;


class PostTest extends TestCase
{
    public static User $testUser;
 
    public static function setUpBeforeClass() : void
    {
        parent::setUpBeforeClass();
    
        // Create test user (BD store later)
        $name = "test_" . time();
        self::$testUser = new User([
            "name"      => "{$name}",
            "email"     => "{$name}@mailinator.com",
            "password"  => "12345678"
        ]);
    }

    public function test_post_list()
    {
        // List all posts using API web service
        $response = $this->getJson("/api/posts");
        // Check OK response
        $this->_test_ok($response);
        // Check JSON dynamic values
        $response->assertJsonPath("data",
            fn ($data) => is_array($data)
        );

        self::$testUser->save();
    }
 
    public function test_post_create() : object
    {
        $user = self::$testUser;
        Sanctum::actingAs(
            $user,
            ['*'] // grant all abilities to the token
        );
        // Create fake file
        $name  = "avatar.png";
        $size = 500; /*KB*/
        $upload = UploadedFile::fake()->image($name)->size($size);
        $postBody = "Hola";
        $postLatitude = 12;
        $postLongitude = 12;
        $visibility_id = 1;
        $author_id = 1;

        // Upload fake post using API web service
        $response = $this->postJson("/api/posts", [
            "upload" => $upload,
            "body" => $postBody,
            "latitude" => $postLatitude,
            "longitude" => $postLongitude,
            "visibility_id" => $visibility_id,
            "author_id" => $author_id,
        ]);
        

        // Check OK response
        $this->_test_ok($response, 201);

        // Check validation errors
        $response->assertValid([
            "upload",
            "body",
            "latitude",
            "longitude",
            "visibility_id",
            "author_id",
        ]);

        // Check JSON dynamic values
        $response->assertJsonPath("data.id",
            fn ($id) => !empty($id)
        );

        // Read, update and delete dependency!!!
        $json = $response->getData();
        return $json->data;
    }
    
    public function test_post_create_error()
    {
        // Create fake post with invalid characters
        $name  = "avatar.png";
        $size = 5000; /*KB*/
        $upload = UploadedFile::fake()->image($name)->size($size);
        $postBody = 13;
        $postLatitude = 'Hola';
        $postLongitude = 'Hola';
        $visibility_id = 'Hola';
        $author_id = 'Hola';

        // Upload fake post using API web service
        $response = $this->postJson("/api/posts", [
            "upload" => $upload,
            "body" => $postBody,
            "latitude" => $postLatitude,
            "longitude" => $postLongitude,
            "visibility_id" => $visibility_id,
            "author_id" => $author_id,
        ]);
        // Check ERROR response
        $this->_test_error($response);
    }
    
    /**
        * @depends test_post_create
        */
    public function test_post_read(object $post)
    {
        // Read one post
        $response = $this->getJson("/api/posts/{$post->id}");
        // Check OK response
        $this->_test_ok($response);
    }
    
    public function test_post_read_notfound()
    {
        $id = "not_exists";
        $response = $this->getJson("/api/posts/{$id}");
        $this->_test_notfound($response);
    }
    
    /**
        * @depends test_post_create
        */
    public function test_post_update(object $post)
    {
        // Create fake file
        $name  = "avatar.png";
        $size = 500; /*KB*/
        $upload = UploadedFile::fake()->image($name)->size($size);
        $postBody = "Hola";
        $postLatitude = 12;
        $postLongitude = 12;
        $visibility_id = 1;
        $author_id = 1;

        // Upload fake post using API web service
        $response = $this->putJson("/api/posts/{$post->id}", [
            "upload" => $upload,
            "body" => $postBody,
            "latitude" => $postLatitude,
            "longitude" => $postLongitude,
            "visibility_id" => $visibility_id,
            "author_id" => $author_id,
        ]);
        

        // Check OK response
        $this->_test_ok($response, 201);

        // Check validation errors
        $response->assertValid([
            "upload",
            "body",
            "latitude",
            "longitude",
            "visibility_id",
            "author_id",
        ]);

        // Check JSON dynamic values
        $response->assertJsonPath("data.id",
            fn ($id) => !empty($id)
        );

        // Read, update and delete dependency!!!
        $json = $response->getData();
        return $json->data;
    }
    
    /**
        * @depends test_post_create
        */
    public function test_post_update_error(object $post)
    {
        // Create fake file with invalid max size
        $name  = "photo.jpg";
        $size = 3000; /*KB*/
        $upload = UploadedFile::fake()->image($name)->size($size);
        $postBody = "Hola";
        $postLatitude = 12;
        $postLongitude = 12;
        $visibility_id = 1;
        $author_id = 1;

        // Upload fake post using API web service
        $response = $this->putJson("/api/posts/{$post->id}", [
            "upload" => $upload,
            "body" => $postBody,
            "latitude" => $postLatitude,
            "longitude" => $postLongitude,
            "visibility_id" => $visibility_id,
            "author_id" => $author_id,
        ]);

        // Upload fake file using API web service
        $response = $this->putJson("/api/files/{$post->id}", [
            "upload" => $upload,
        ]);
        // Check ERROR response
        $this->_test_error($response);
    }
    
    public function test_post_update_notfound()
    {
        $id = "not_exists";
        $response = $this->putJson("/api/posts/{$id}", []);
        $this->_test_notfound($response);
    }
    
    /**
        * @depends test_post_create
        */
    public function test_post_delete(object $post)
    {
        // Delete one file using API web service
        $response = $this->deleteJson("/api/posts/{$post->id}");
        // Check OK response
        $this->_test_ok($response);
    }
    
    public function test_post_delete_notfound()
    {
        $id = "not_exists";
        $response = $this->deleteJson("/api/posts/{$id}");
        $this->_test_notfound($response);

    }
    
    protected function _test_ok($response, $status = 200)
    {
        // Check JSON response
        $response->assertStatus($status);
        // Check JSON properties
        $response->assertJson([
            "success" => true,
            "data"    => true // any value
        ]);
    }
    
    protected function _test_error($response)
    {
        // Check response
        $response->assertStatus(422);
        // Check validation errors
        $response->assertInvalid(["upload"]);
        // Check JSON properties
        $response->assertJson([
            "message" => true, // any value
            "errors"  => true, // any value
        ]);       
        // Check JSON dynamic values
        $response->assertJsonPath("message",
            fn ($message) => !empty($message) && is_string($message)
        );
        $response->assertJsonPath("errors",
            fn ($errors) => is_array($errors)
        );
    }
    
    protected function _test_notfound($response)
    {
        // Check JSON response
        $response->assertStatus(404);
        // Check JSON properties
        $response->assertJson([
            "success" => false,
            "message" => true // any value
        ]);
        // Check JSON dynamic values
        $response->assertJsonPath("message",
            fn ($message) => !empty($message) && is_string($message)
        );       
    }
}
