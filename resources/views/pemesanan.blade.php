<!-- resources/views/order/create.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
</head>
<body>
    @include('Components.navbar')
    <h1>Order Form</h1>
    <form method="POST" action="{{ route('create-product') }}">
        @csrf


        <label for="productId">Product ID:</label><br>
        <input type="text" id="productId" name="products[0][product_id]" value="1"><br>

        <label for="qty">Quantity:</label><br>
        <input type="text" id="qty" name="products[0][qty]" value="2"><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
