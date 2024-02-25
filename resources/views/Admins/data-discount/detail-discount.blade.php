<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel | Data-Discount</title>
    @vite('resources/css/app.css')
</head>
<body>
    <!-- Include the sidebar -->
    @include('Components.sidebar')

    <!-- Create a container for the table to apply styling -->
    <!-- Detail Discount -->
<div class="p-4 sm:ml-64">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Start Date</th>
                    <th scope="col" class="px-6 py-3">End Date</th>
                    <th scope="col" class="px-6 py-3">Genres</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4">{{ $discount->id }}</td>
                    <td class="px-6 py-4">{{ $discount->nama_discount }}</td>
                    <td class="px-6 py-4">{{ $discount->start_date }}</td>
                    <td class="px-6 py-4">{{ $discount->end_date }}</td>
                    <td class="px-6 py-4">
                        <ul>
                            @if($genres)
                                @foreach($genres as $genre)
                                    <li>
                                        {{ $genre->nama_genre }}
                                        <form action="{{ route('admin.discount.genre.delete', ['discount_id' => $discount->id, 'genre_id' => $genre->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Hapus</button>
                                        </form>
                                    </li>
                                @endforeach
                            @else
                                <li>No genres found</li>
                            @endif
                        </ul>
                        <!-- Form for adding new genre -->
                        <form action="{{ route('add.discount-genre', $discount->id) }}" method="POST">
                            @csrf
                            <!-- Form to add genre -->
                            <!-- Tambahkan input untuk memilih genre -->
                            <select name="genre">
                                @foreach($availableGenres as $genre)
                                    <option value="{{ $genre->id }}">{{ $genre->nama_genre }}</option>
                                @endforeach
                            </select>
                            <button type="submit">Add Genre</button>
                        </form>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.discount.edit', $discount->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
