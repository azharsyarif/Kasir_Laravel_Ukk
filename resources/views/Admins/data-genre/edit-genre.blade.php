<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit User</title>
    @vite('resources/css/app.css')
    <!-- Include CSS stylesheets -->
</head>
<body>
    @include('Components.sidebar')
    <div class="p-4 sm:ml-64">

        <h1 class="flex items-center text-5xl font-bold dark:text-white">EDIT<span class="bg-blue-100 text-blue-800 text-2xl font-semibold me-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-2">GENRE</span></h1>
            <form action="{{ route('update.genre', $genre->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label for="nama_genre">Name:</label><br>
                <input type="text" id="nama_genre" name="nama_genre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $genre->nama_genre }}">
            </div>


            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save Changes</button>
        </form>
    </div>
</body>
</html>
