<!DOCTYPE html>
<html>
<head>
    <title>Product Categories</title>
    <style>
        .category-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border: 2px solid #ccc;
            border-radius: 10px;
            cursor: pointer;
            background-color: #f4f4f4;
            text-decoration: none;
            color: black;
        }
        .active-category {
            background-color: #ff7f50;
            color: white;
            border-color: #ff7f50;
        }
        .product-card {
            border: 1px solid #ddd;
            margin: 10px;
            padding: 15px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <h2>Select a Category</h2>

    <div>
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category]) }}"
               class="category-button {{ $selectedCategory == $category ? 'active-category' : '' }}">
               {{ $category }}
            </a>
        @endforeach
    </div>

    <h3>Showing: {{ $selectedCategory }}</h3>

    <div style="display: flex; flex-wrap: wrap;">
        @forelse($products as $product)
            <div class="product-card">
                <h4>{{ $product->name }}</h4>
                <p>${{ number_format($product->price, 2) }}</p>
                <p>{{ $product->description }}</p>
                <form method="POST" action="{{ route('cart.add', $product->id) }}">
                    @csrf
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        @empty
            <p>No products found in this category.</p>
        @endforelse
    </div>
</body>
</html>
