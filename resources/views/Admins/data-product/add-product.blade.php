<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Product</title>
    @vite('resources/css/app.css')

    <!-- Include CSS files here -->
</head>
<body>
    <!-- Include sidebar component here -->
    @include('Components.sidebar')

    <div class="p-4 sm:ml-64">
        <form method="POST" action="{{ route('products.add') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-5">
                <label for="nama_product" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Product</label>
                <input type="text" id="nama_product" name="nama_product" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
            </div>
            <div class="mb-5">
                <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                <input type="file" id="image" name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
            </div>
            <div class="mb-5">
                <label for="genre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genre</label>
                <select id="genre" name="genre_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
                    <option value="" disabled selected>Choose a genre</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->nama_genre }}</option>
                    @endforeach
                </select>                               
            </div>        
            <div class="mb-5">
                <label for="platform" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Platform</label>
                <select id="platform" name="platform" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light">
                    <option disabled selected>Choose a platform</option>
                    <option value="PS4">PS4</option>
                    <option value="PS5">PS5</option>
                    <option value="NINTENDO SWITCH">Nintendo Switch</option>
                </select>                
            </div>            
            <div class="mb-5">
                <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                <input type="text" id="harga" name="harga" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Product</button>
        </form>
    </div>
</body>
</html>
