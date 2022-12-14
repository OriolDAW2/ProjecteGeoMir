<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        <a class="btn btn-primary" href="{{ route('files.index') }}" role="button">Files</a>
                        <a class="btn btn-primary" href="{{ route('posts.index') }}" role="button">Posts</a>
                        <a class="btn btn-primary" href="{{ route('places.index') }}" role="button">Places</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
