@extends('layouts.wholesaler_app')
@section('title', 'Place Order')

@section('content')
<div class="container py-4">
    <h2 class="mb-4" style="color:#a0522d;">
        <i class="bi bi-bag-plus me-2"></i>Place Order
    </h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('wholesaler.order.submit') }}" id="orderForm">
        @csrf
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-cart-plus me-2"></i>Order Details</h5>
            </div>
            <div class="card-body">
                <div id="orderItems"></div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-outline-primary" onclick="addOrderItem()">
                            <i class="bi bi-plus-circle me-2"></i>Add Product
                        </button>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="alert alert-info mb-0">
                            <strong>Total Amount: <span id="totalAmount">UGX 0</span></strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                    <i class="bi bi-check-circle me-2"></i>Submit Order
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    const products = @json($products ?? []);
    let itemCounter = 0;
    let totalAmount = 0;

    document.addEventListener('DOMContentLoaded', () => {
        if (products.length === 0) {
            document.getElementById('orderItems').innerHTML = `
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No products available for ordering. Please contact the administrator.
                </div>`;
            return;
        }
        addOrderItem();
    });

    function addOrderItem() {
        const container = document.getElementById('orderItems');
        const itemDiv = document.createElement('div');
        itemDiv.className = 'order-item border rounded p-3 mb-3';
        itemDiv.id = `item-${itemCounter}`;

        const options = products.map(product => `
            <option value="${product.id}" data-price="${product.price}" data-stock="${product.quantity_in_stock}">
                ${product.name} - UGX ${product.price.toLocaleString()} (Stock: ${product.quantity_in_stock})
            </option>`).join('');

        itemDiv.innerHTML = `
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Product <span class="text-danger">*</span></label>
                    <select name="products[${itemCounter}][id]" class="form-select product-select" required onchange="updatePrice(${itemCounter})">
                        <option value="">Select product</option>${options}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="products[${itemCounter}][quantity]" class="form-control quantity-input" 
                        min="1" value="1" required onchange="updateTotal(${itemCounter})" onkeyup="updateTotal(${itemCounter})">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit Price</label>
                    <div class="form-control-plaintext unit-price" id="unit-price-${itemCounter}">UGX 0</div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Item Total</label>
                    <div class="form-control-plaintext item-total" id="item-total-${itemCounter}">UGX 0</div>
                </div>
                <div class="col-md-12 mt-2">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOrderItem(${itemCounter})" ${itemCounter === 0 ? 'style="display:none;"' : ''}>
                        <i class="bi bi-trash me-1"></i>Remove
                    </button>
                </div>
            </div>`;
        
        container.appendChild(itemDiv);
        itemCounter++;
        updateRemoveButtons();
        updateSubmitButton();
    }

    function removeOrderItem(id) {
        const item = document.getElementById(`item-${id}`);
        if (item) {
            item.remove();
            calculateTotal();
            updateRemoveButtons();
            updateSubmitButton();
        }
    }

    function updatePrice(id) {
        const select = document.querySelector(`#item-${id} .product-select`);
        const unit = document.getElementById(`unit-price-${id}`);
        const qty = document.querySelector(`#item-${id} .quantity-input`);

        if (select.value) {
            const option = select.options[select.selectedIndex];
            const price = parseFloat(option.dataset.price);
            const stock = parseInt(option.dataset.stock);
            unit.textContent = `UGX ${price.toLocaleString()}`;
            qty.max = stock;
            updateTotal(id);
        } else {
            unit.textContent = 'UGX 0';
            updateTotal(id);
        }
    }

    function updateTotal(id) {
        const select = document.querySelector(`#item-${id} .product-select`);
        const qtyInput = document.querySelector(`#item-${id} .quantity-input`);
        const itemTotalDiv = document.getElementById(`item-total-${id}`);
        
        if (select.value && qtyInput.value) {
            const price = parseFloat(select.options[select.selectedIndex].dataset.price);
            const qty = parseInt(qtyInput.value);
            let total = price * qty;

            if (qty >= 50) {
                total *= 0.9;
                itemTotalDiv.innerHTML = `UGX ${total.toLocaleString()} <span class="badge bg-success ms-1">10% OFF</span>`;
            } else {
                itemTotalDiv.textContent = `UGX ${total.toLocaleString()}`;
            }
        } else {
            itemTotalDiv.textContent = 'UGX 0';
        }
        calculateTotal();
    }

    function calculateTotal() {
        totalAmount = 0;
        document.querySelectorAll('.item-total').forEach(div => {
            const amount = parseFloat(div.textContent.replace(/[^\d.]/g, ''));
            if (!isNaN(amount)) totalAmount += amount;
        });
        document.getElementById('totalAmount').textContent = `UGX ${totalAmount.toLocaleString()}`;
        updateSubmitButton();
    }

    function updateSubmitButton() {
        const submitBtn = document.getElementById('submitBtn');
        const selects = document.querySelectorAll('.product-select');
        const valid = [...selects].some(s => s.value !== '');
        submitBtn.disabled = !valid || totalAmount <= 0;
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.order-item');
        items.forEach((item, i) => {
            const btn = item.querySelector('.btn-outline-danger');
            if (btn) btn.style.display = items.length === 1 ? 'none' : 'inline-block';
        });
    }

    document.getElementById('orderForm').addEventListener('submit', function(e) {
        const selects = document.querySelectorAll('.product-select');
        const qtys = document.querySelectorAll('.quantity-input');
        let isValid = true;

        if (![...selects].some(s => s.value !== '')) {
            alert('Please select at least one product.');
            isValid = false;
        }

        selects.forEach((s, i) => {
            if (s.value && qtys[i].value <= 0) {
                alert('Enter valid quantity for selected products.');
                isValid = false;
            }
        });

        if (!isValid) e.preventDefault();
    });

    document.addEventListener('keydown', e => {
        if (e.ctrlKey && e.key === 'Enter') {
            const btn = document.getElementById('submitBtn');
            if (!btn.disabled) btn.click();
        }
    });
</script>

<style>
    .order-item { background-color: #f8f9fa; transition: 0.3s; border-left: 4px solid #dee2e6; }
    .order-item:hover { background-color: #e9ecef; border-left-color: #007bff; }
    .form-control-plaintext { font-weight: 600; color: #495057; }
    .item-total { color: #28a745 !important; }
    .unit-price { color: #6c757d !important; }
    .badge { font-size: 0.75em; }
    .quantity-input:invalid, .product-select:invalid { border-color: #dc3545; }
    .quantity-input:valid, .product-select:valid { border-color: #28a745; }
    #submitBtn:disabled { opacity: 0.6; cursor: not-allowed; }
    .alert-info { background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
</style>
@endsection
