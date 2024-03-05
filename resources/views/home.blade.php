<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    @include('Components.navbar')

    <div class="container mx-auto px-5 bg-white">
        <div class="flex lg:flex-row flex-col-reverse shadow-lg">
            <!-- left section -->
            <div class="w-full lg:w-3/5 min-h-screen shadow-lg">
                <!-- header -->
                <div class="flex flex-row justify-between items-center px-5 mt-5">
                    <div class="text-gray-800">
                        <div class="font-bold text-xl">GAME BOI</div>
                    </div>
                </div>
                <!-- end header -->
                <!-- products -->
                <div class="grid grid-cols-3 gap-4 px-5 mt-5 overflow-y-auto h-3/4">
                    @if(isset($products) && count($products) > 0)
                        @foreach ($products as $product)
                            @php
                                $discountPercentage = 0;
                                $discountedPrice = $product->harga;
                                // Cari diskon maksimum untuk setiap genre produk
                                if ($product->genres) {
                                    foreach ($product->genres as $genre) {
                                        if ($genre->discountDetails) {
                                            foreach ($genre->discountDetails as $discountDetail) {
                                                // Hitung diskon maksimum untuk setiap genre
                                                $maxDiscount = $discountDetail->discount->discount_amount;
                                                if ($maxDiscount > $discountPercentage) {
                                                    $discountPercentage = $maxDiscount;
                                                }
                                            }
                                        }
                                    }
                                }
                                // Hitung harga produk setelah diskon
                                if ($discountPercentage > 0) {
                                    $discountedPrice = $product->harga * (1 - $discountPercentage / 100);
                                }
                            @endphp
                            <div class="px-3 py-3 flex flex-col border border-gray-200 rounded-md h-44 justify-between">
                                <div>
                                    <div class="font-bold text-gray-800">{{ $product->nama_product }}</div>
                                    <div class="text-gray-500">
                                        @if ($product->genres)
                                            @foreach ($product->genres as $genre)
                                                {{ $genre->nama_genre }}
                                            @endforeach
                                        @endif
                                        @if (!$product->genres || count($product->genres) === 0)
                                            Genre tidak tersedia
                                        @endif
                                    </div> <!-- Menampilkan genre -->
                                </div>
                                <div class="flex flex-row justify-between items-center">
                                    @if ($discountPercentage > 0)
                                        <span class="self-end font-bold text-lg text-yellow-500">
                                            {{ $discountPercentage }}% OFF
                                            <span class="text-gray-500 line-through">@currency($product->harga)</span> <!-- Harga asli -->
                                            @currency($discountedPrice)
                                        </span>
                                    @else
                                        <span class="self-end font-bold text-lg">
                                            @currency($product->harga)
                                        </span>
                                    @endif
                                    <div>
                                        @if(filter_var($product->image, FILTER_VALIDATE_URL))
                                            <img src="{{ $product->image }}" alt="Product Image" width="100">
                                        @else
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100">
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded btn-add-to-cart"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->nama_product }}"
                                            data-product-price="{{ $discountedPrice }}"
                                            data-product-image="{{ $product->image }}"
                                            data-product-genre="{{ $genre ? $genre->nama_genre : 'Genre tidak tersedia' }}">
                                        Tambahkan
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500">No products available</div>
                    @endif
                </div>
                <!-- end products -->
            </div>
            <!-- end left section -->
            <!-- right section -->
            <div class="w-full lg:w-2/5">
                <!-- header -->
                <div class="flex flex-row items-center justify-between px-5 mt-5">
                    <div class="font-bold text-xl">Current Order</div>
                    <div class="font-semibold">
                        <button class="px-4 py-2 rounded-md bg-red-100 text-red-500 clear-all-btn">Clear All</button>
                        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-800">Setting</span>
                    </div>
                </div>
                <!-- end header -->
                <!-- order list -->
                <div class="px-5 py-4 mt-5 overflow-y-auto h-64" id="current-order">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Product
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="order-list-body">
                            <!-- Order items will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
                <!-- end order list -->
                <!-- totalItems -->
                <div class="px-5 mt-5">
                    <div class="py-4 rounded-md shadow-lg">
                        <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                            <span class="font-semibold text-lg">Total Price: <span id="totalAmount">Rp0.00</span></span>
                        </div>
                    </div>
                </div>
                <!-- end total -->
                {{-- <div class="px-5 mt-5">
                    <form id="discountForm">
                        <label for="discountInput" class="block text-sm font-medium text-gray-700">Masukkan diskon tambahan (persen):</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="number" min="0" max="100" step="1" id="discountInput" name="discountInput" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                            <button type="button" id="deleteDiscountBtn" class="ml-2 px-4 py-2 rounded-md bg-red-100 text-red-500">Delete Discount</button>
                        </div>
                    </form>
                </div> --}}
                <!-- cash -->
                <div class="px-5 mt-5">
                    <div class="rounded-md shadow-lg px-4 py-4">
                        <div class="flex flex-row justify-between items-center">
                            <div class="flex flex-col">
                                {{-- <span class="uppercase text-xs font-semibold">cashless credit</span>
                                <span class="text-xl font-bold text-yellow-500">$32.50</span> --}}
                            </div>
                            <div class="px-4 py-3 bg-gray-300 text-gray-800 rounded-md font-bold"> Cancel</div>
                        </div>
                    </div>
                </div>
                <!-- end cash -->
                <!-- button pay-->
                <div class="px-5 mt-5">
                    <div class="px-4 py-4 rounded-md shadow-lg text-center bg-yellow-500 text-white font-semibold">
                        <!-- Formulir untuk pembayaran -->
                        <form id="paymentForm">
                            @csrf <!-- Tambahkan token CSRF untuk melindungi form dari serangan CSRF -->
                            <!-- Input hidden untuk user_id -->
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            <!-- Tombol "BAYAR" -->
                            <button type="button" id="payButton">BAYAR</button>
                        </form>
                    </div>
                </div>
                <!-- Payment popup -->

                <!-- end button pay -->
            </div>
            <!-- end right section -->
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let total = 0;
        let discountAmount = 0;
        const totalElement = document.getElementById('totalAmount');
        const orderListBody = document.getElementById('order-list-body');

        function updateTotal() {
            const discountedTotal = total * (1 - (discountAmount / 100));
            totalElement.textContent = formatRupiah(discountedTotal);
        }

        function formatRupiah(amount) {
            return `Rp${amount.toLocaleString('id-ID')}`;
        }

        function addProductToCart(productId, productName, productPrice, discountPercentage) {
            const existingOrderItem = orderListBody.querySelector(`.order-item[data-product-id="${productId}"]`);
            if (existingOrderItem) {
                const quantityElement = existingOrderItem.querySelector('.quantity');
                const quantity = parseInt(quantityElement.textContent) + 1;
                quantityElement.textContent = quantity;
            } else {
                const newOrderItem = document.createElement('tr');
                newOrderItem.classList.add('order-item');
                newOrderItem.setAttribute('data-product-id', productId);

                const productNameCell = document.createElement('td');
                productNameCell.textContent = productName;
                productNameCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-900');

                const productPriceCell = document.createElement('td');
                productPriceCell.textContent = formatRupiah(productPrice);
                productPriceCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-900');

                const quantityCell = document.createElement('td');
                quantityCell.classList.add('px-6', 'py-4', 'whitespace-nowrap', 'text-sm', 'text-gray-900');

                const quantitySpan = document.createElement('span');
                quantitySpan.textContent = '1';
                quantitySpan.classList.add('px-2', 'inline-flex', 'text-xs', 'leading-5', 'font-semibold', 'rounded-full', 'bg-green-100', 'text-green-800');

                const addButton = document.createElement('button');
                addButton.textContent = '+';
                addButton.classList.add('px-2', 'py-1', 'rounded-md', 'bg-green-500', 'text-white', 'font-semibold', 'mr-2', 'btn-add');
                addButton.addEventListener('click', () => {
                    const quantity = parseInt(quantitySpan.textContent) + 1;
                    quantitySpan.textContent = quantity;
                    total += productPrice;
                    updateTotal();
                });

                const removeButton = document.createElement('button');
                removeButton.textContent = '-';
                removeButton.classList.add('px-2', 'py-1', 'rounded-md', 'bg-red-500', 'text-white', 'font-semibold', 'btn-remove');
                removeButton.addEventListener('click', () => {
                    const quantity = parseInt(quantitySpan.textContent);
                    if (quantity > 1) {
                        quantitySpan.textContent = quantity - 1;
                        total -= productPrice;
                        updateTotal();
                    } else {
                        orderListBody.removeChild(newOrderItem);
                        total -= productPrice;
                        updateTotal();
                    }
                });

                quantityCell.appendChild(removeButton);
                quantityCell.appendChild(quantitySpan);
                quantityCell.appendChild(addButton);

                newOrderItem.appendChild(productNameCell);
                newOrderItem.appendChild(productPriceCell);
                newOrderItem.appendChild(quantityCell);

                orderListBody.appendChild(newOrderItem);
            }

            total += productPrice;
            updateTotal();
        }

        const addToCartButtons = document.querySelectorAll('.btn-add-to-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const productName = this.getAttribute('data-product-name');
                const productPrice = parseFloat(this.getAttribute('data-product-price').replace(/[^\d,]/g, '').replace(/,/g, '.'));
                addProductToCart(productId, productName, productPrice);
            });
        });

        document.getElementById('payButton').addEventListener('click', function() {
            const orderItems = document.querySelectorAll('.order-item');
            if (orderItems.length === 0) {
                console.error('Tidak ada produk yang dipilih');
                return;
            }
            const products = [];
            orderItems.forEach(item => {
                const productId = item.getAttribute('data-product-id');
                const productName = item.querySelector('td:first-child').textContent;
                const productPrice = parseFloat(item.querySelector('td:nth-child(2)').textContent.replace(/[^\d,]/g, '').replace(/,/g, '.'));
                const quantity = parseInt(item.querySelector('td:last-child span').textContent);
                products.push({ productId, productName, productPrice, quantity });
            });

            const userData = {
                user_id: document.querySelector('input[name="user_id"]').value,
                products: products
            };

            fetch('{{ route("store-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(userData)
            })
            .then(response => {
                if (response.ok) {
                    console.log('Transaksi berhasil');
                    Swal.fire({
                        icon: 'success',
                        title: 'Transaksi Berhasil',
                        text: 'Transaksi Anda berhasil diselesaikan!',
                    }).then((result) => {
                        window.location.href = '{{ route("nota") }}';
                    });
                    orderItems.forEach(item => {
                        orderListBody.removeChild(item);
                    });
                    total = 0;
                    updateTotal();
                    document.getElementById('payButton').disabled = false;
                } else {
                    console.error('Transaksi gagal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
            this.disabled = true;
        });

        document.querySelector('.clear-all-btn').addEventListener('click', function() {
            orderListBody.innerHTML = '';
            total = 0;
            updateTotal();
        });
    });
</script>

    
    
</body>

</html>
