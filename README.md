# Laravel Breeze School Supplies UI

This package contains working Blade templates, partials, components, CSS and JS that you can drop into a Laravel Breeze project to get a functional Bootstrap-based frontend for a school-supplies e-commerce site. The templates are Blade-ready and designed to work out-of-the-box for development and prototyping.

## Files included (paths relative to project root)
- resources/views/layouts/app.blade.php
- resources/views/partials/header.blade.php
- resources/views/partials/footer.blade.php
- resources/views/components/product-card.blade.php
- resources/views/components/product-carousel.blade.php
- resources/views/home.blade.php
- resources/views/product/show.blade.php
- resources/views/cart/index.blade.php
- resources/views/checkout/index.blade.php
- resources/views/seller/products/index.blade.php
- resources/views/seller/products/create.blade.php
- resources/views/admin/dashboard.blade.php
- public/css/theme.css
- public/js/site.js
- README.md (this file)

## Quick install
1. Copy the files into your Laravel project (paths above).
2. Ensure Laravel Breeze is installed and configured.
3. Add simple routes in `routes/web.php` for previewing the views (examples below).
4. Run `php artisan serve` and visit the paths described below.

Example preview routes (for quick local preview):

```php
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'));
Route::view('/product', 'product.show');
Route::view('/cart', 'cart.index');
Route::view('/checkout', 'checkout.index');
Route::view('/seller/products', 'seller.products.index');
Route::view('/seller/products/create', 'seller.products.create');
Route::view('/admin/dashboard', 'admin.dashboard');
```
