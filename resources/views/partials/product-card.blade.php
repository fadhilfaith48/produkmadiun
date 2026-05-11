{{-- =====================================================
FILE: resources/views/partials/product-card.blade.php
Komponen kartu produk (dipakai berulang)
===================================================== --}}
{{-- Simpan di: resources/views/partials/product-card.blade.php --}}
<div class="card product-card h-100 shadow-sm">
    <a href="{{ route('products.show', $product->slug) }}">
        <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/no-image.png') }}"
             class="card-img-top" alt="{{ $product->name }}">
    </a>
    <div class="card-body p-3">
        <span class="badge badge-category mb-1">{{ $product->category->name }}</span>
        <h6 class="card-title fw-semibold mb-1" style="font-size:.9rem">
            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                {{ Str::limit($product->name, 45) }}
            </a>
        </h6>
        <p class="text-muted small mb-1">{{ $product->store->store_name }}</p>
        <p class="fw-bold mb-2" style="color:var(--pm-green)">{{ $product->formatted_price }}</p>
        <div class="d-flex gap-1">
            <form action="{{ route('cart.add') }}" method="POST" class="flex-fill">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="qty" value="1">
                <button class="btn btn-pm btn-sm w-100"><i class="bi bi-cart-plus"></i></button>
            </form>
            <a href="{{ $product->getWhatsappOrderLink() }}" target="_blank"
               class="btn btn-wa btn-sm px-3">
                <i class="bi bi-whatsapp"></i>
            </a>
        </div>
    </div>
</div>