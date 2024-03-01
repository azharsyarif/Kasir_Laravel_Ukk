<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Data Discount</title>
    <!-- Include CSS -->
    @vite('resources/css/app.css')

    <link rel="stylesheet" href="path/to/app.css">
</head>
<body>
    <!-- Include the sidebar -->
    @include('Components.sidebar')

    <div class="p-4 sm:ml-64">
        <form class="max-w-sm mx-auto" method="POST" action="{{ route('discount.add') }}" onsubmit="return validateDates()">
            @csrf
            <div class="mb-5">
                <label for="discount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Discount</label>
                <input type="text" id="discount" name="nama_discount" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
            </div>
            <div class="mb-5">
                <label for="discount_amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Diskon</label>
                <input type="number" id="discount_amount" name="discount_amount" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
            </div>
            <div class="mb-5">
                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
            </div>
            <div class="mb-5">
                <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Berakhir</label>
                <input type="date" id="end_date" name="end_date" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" required>
                <span id="date_error" class="text-red-500 text-sm"></span> <!-- Penambahan elemen untuk pesan kesalahan -->
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
        </form>
    </div>

    <script>
        function validateDates() {
            var startDate = document.getElementById('start_date').value;
            var endDate = document.getElementById('end_date').value;

            // Ubah tanggal ke format objek Date untuk membandingkan
            var startDateObj = new Date(startDate);
            var endDateObj = new Date(endDate);

            // Periksa apakah tanggal berakhir lebih kecil dari tanggal mulai
            if (endDateObj < startDateObj) {
                document.getElementById('date_error').textContent = 'Tanggal berakhir tidak boleh kurang dari tanggal mulai.';
                return false; // Mencegah pengiriman formulir jika validasi gagal
            }

            // Bersihkan pesan kesalahan jika validasi berhasil
            document.getElementById('date_error').textContent = '';
            return true; // Izinkan pengiriman formulir jika validasi berhasil
        }
    </script>
</body>
</html>
