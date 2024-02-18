<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>add-data-genre</title>
    @vite('resources/css/app.css')
</head>
<body>
@include('Components.sidebar')
<div class="p-4 sm:ml-64">
    <form class="max-w-sm mx-auto" method="POST" action="{{ route('add-genre') }}">
        @csrf
        <div class="mb-5">
        <label for="genre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Genre</label>
        <input type="text" id="genre" name="nama_genre" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light"required>
        </div>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
    </form>
    </div>

</body>
</html>