<?php

use App\Models\CmsPage;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\UsersController;
use App\Http\Controllers\front\OrdersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
    //     return view('welcome');
    // });
use App\Http\Controllers\Front\PaypalController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\RatingsController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Front\FrontCmsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\front\NewsletterController;
use App\Http\Controllers\Admin\AdminOrdersController;
use App\Http\Controllers\Front\FrontRatingController;
use App\Http\Controllers\Front\FrontProductsController;
use App\Http\Controllers\Admin\AdminNewsletterController;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('/admin')->namespace('Admin')->group(function () {
    // All the admin route will be define here :-
    Route::match(['get', 'post'], '/', [AdminController::class, 'login']);
    Route::group(['middleware' => ['admin']], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::get('settings', [AdminController::class, 'settings']);
        Route::get('logout', [AdminController::class, 'logout']);
        Route::post('check-current-pwd', [AdminController::class, 'chkCurrentPassword']);
        Route::post('update-current-pwd', [AdminController::class, 'updateCurrentPassword']);
        Route::match(['get', 'post'], 'update-admin-details', [AdminController::class, 'updateAdminDetails']);
        
        //section
        Route::get('sections', [SectionController::class, 'sections']);
        Route::post('update-section-status', [SectionController::class, 'updateSectionStatus']);
        
        //Brand Section
        Route::get('brands', [BrandController::class, 'brands']);
        Route::post('update-brand-status', [BrandController::class, 'updateBrandStatus']);
        Route::match(['get', 'post'], 'add-edit-brand/{id?}', [BrandController::class, 'addEditBrand']);
        Route::get('delete-brand/{id}', [BrandController::class, 'deleteBrand']);
        
        //categories
        Route::get('categories', [CategoryController::class, 'categories']);
        Route::post('update-category-status', [CategoryController::class, 'updateCategoryStatus']);
        Route::match(['get', 'post'], 'add-edit-category/{id?}', [CategoryController::class, 'addEditCategory']);
        Route::post('append-categories-level', [CategoryController::class, 'appendCategoryLevel']);
        Route::get('delete-category-image/{id}', [CategoryController::class, 'deleteCategoryImage']);
        Route::get('delete-category/{id}', [CategoryController::class, 'deleteCategory']);
        
        //Products
        Route::get('products', [ProductsController::class, 'products']);
        Route::post('update-product-status', [ProductsController::class, 'updateProductStatus']);
        Route::get('delete-product/{id}', [ProductsController::class, 'deleteProduct']);
        Route::match(['get', 'post'], 'add-edit-product/{id?}', [ProductsController::class, 'addEditProduct']);
        Route::get('delete-product-image/{id}', [ProductsController::class, 'deleteProductImage']);
        Route::get('delete-product-video/{id}', [ProductsController::class, 'deleteProductVideo']);
        
        //Product Attribute 
        Route::match(['get', 'post'], 'add-attributes/{id}', [ProductsController::class, 'addAttributes']);
        Route::post('edit-attributes/{id}', [ProductsController::class, 'editAttributes']);
        Route::post('update-attribute-status', [ProductsController::class, 'updateAttributeStatus']);
        Route::get('delete-attribute/{id}', [ProductsController::class, 'deleteAttribute']);
        
        //product Image
        Route::match(['get', 'post'], 'add-images/{id?}', [ProductsController::class, 'addImages']);
        Route::post('update-image-status', [ProductsController::class, 'updateImageStatus']);
        Route::get('delete-image/{id}', [ProductsController::class, 'deleteImage']);
        
        //Banners Routes
        Route::match(['get', 'post'], 'add-edit-banner/{id?}', [BannersController::class, 'addEditBanner']);
        Route::get('banner', [BannersController::class, 'banners']);
        Route::post('update-banner-status', [BannersController::class, 'updateBannerStatus']);
        Route::get('delete-banner/{id}', [BannersController::class, 'deleteBanner']);
        
        //Coupon Routes  {id?} is only worek for the edit functionality
        Route::get('coupons', [CouponsController::class, 'coupons']);
        Route::post('update-coupons-status', [CouponsController::class, 'updateCouponStatus']);
        Route::match(['get', 'post'], 'add-edit-coupon/{id?}', [CouponsController::class, 'addEditCoupon']);
        Route::get('delete-coupon/{id}', [CouponsController::class, 'deleteCoupon']);
        
        //Order Routes  {id?} is only worek for the edit functionality
        Route::get('orders', [AdminOrdersController::class, 'orders']);
        Route::get('orders/{id}', [AdminOrdersController::class, 'orderDetails']);
        Route::post('update-order-status', [AdminOrdersController::class, 'updateOrderStatus']);
        Route::get('view-order-invoice/{id}', [AdminOrdersController::class, 'viewOrderInvoice']);
        Route::get('print-pdf-invoice/{id}', [AdminOrdersController::class, 'printPDFInvoice']);
        
        //Shipping Charges Routes
        Route::get('view-shipping-charges', [ShippingController::class, 'viewShippingCharges']);
        Route::match(['get', 'post'], 'edit-shipping-charges/{id?}', [ShippingController::class, 'editShippingCharges']);
        Route::post('update-shipping-status', [ShippingController::class, 'updateShippingStatus']);
        
        //Front User for  Route
        Route::get('users',[AdminUserController::class, 'users']);
        Route::post('update-user-status', [AdminUserController::class, 'updateUserStatus']);
        
        //CMS pages Route
        Route::get('cms-pages',[CmsController::class, 'cmspages']);
        Route::post('update-cms-pages-status', [CmsController::class, 'updateCmsPageStatus']);
        Route::match(['get', 'post'], 'add-edit-cms-page/{id?}', [CmsController::class, 'addEditCmsPage']);
        Route::get('delete-cms-page/{id}', [CmsController::class, 'deleteCmsPage']);
        
        // Admins / Sub-Admin
        Route::get('admins-subadmins',[AdminController::class, 'adminsSubadmins']);
        Route::match(['get', 'post'], 'add-edit-admin-subadmin/{id?}', [AdminController::class, 'addEditAdminSubadmin']);
        Route::post('update-admin-status', [AdminController::class, 'updateAdminStatus']);
        Route::get('delete-admin/{id}', [AdminController::class, 'deleteAdmin']);
        Route::match(['get', 'post'], 'update-role/{id?}', [AdminController::class, 'updateRole']);
        
        //Currencies
        Route::get('currencies',[CurrencyController::class, 'currencies']);
        Route::post('update-currency-status', [CurrencyController::class, 'updateCurrencyStatus']);
        Route::match(['get', 'post'], 'add-edit-currency/{id?}', [CurrencyController::class, 'addEditCurrency']);
        Route::get('delete-currency/{id}', [CurrencyController::class, 'deleteCurrency']);
        
        //Ratings 
        Route::get('ratings', [RatingsController::class, 'ratings']);
        Route::post('update-rating-status', [RatingsController::class, 'updateRatingStatus']);
        
        //Return Request
        Route::get('return-requests',[AdminOrdersController::class, 'returnRequests']);        
        Route::post('return-requests/update',[AdminOrdersController::class, 'returnRequestUpdate']);        
        
        //Exchange Request
        Route::get('exchange-requests',[AdminOrdersController::class, 'exchangeRequests']);
        Route::post('exchange-requests/update',[AdminOrdersController::class, 'exchangeRequestUpdate']);
        
        // Newsletter Subscriber
        Route::get('newsletter-subscribers',[AdminNewsletterController::class, 'newsletterSubscribers']);
        Route::post('update-subscriber-status', [AdminNewsletterController::class, 'updateSubscriberStatus']);
        Route::get('delete-subscriber/{id}', [AdminNewsletterController::class, 'deleteSubscriber']);
    });
});

//FRONT END ROUTE
Route::namespace('Front')->group(function(){
    // Home Page route
    Route::get('/', [IndexController::class, 'index']);
    
    // Listing/Categories Route
    // Get category url's
    $catUrls = Category::select('url')->where('status',1)->get()->pluck('url')->toArray();
    foreach ($catUrls as $url) {
        Route::get('/'.$url, [FrontProductsController::class, 'listing']);
    }

    // CMS Route
    $cmsUrls = CmsPage::select('url')->where('status',1)->get()->pluck('url')->toArray();
    foreach ($cmsUrls as $url) {
        Route::get('/'.$url, [FrontCmsController::class, 'cmsPage']);
    }
    // product Details route
    Route::get('/product/{id}', [FrontProductsController::class, 'detail']);

    // Get Product Attribute Price
    Route::post('/get-product-price', [FrontProductsController::class, 'getProductPrice']);

    // Add To cart Route
    Route::post('/add-to-cart', [FrontProductsController::class, 'addToCart']);
    //Cart Route
    Route::get('/cart', [FrontProductsController::class, 'cart']);

    //update cart item Quantity
    Route::post('/update-cart-item-qty', [FrontProductsController::class, 'updateCartItemQty']);
    
    //delete cart item 
    Route::post('/delete-cart-item', [FrontProductsController::class, 'deleteCartItem']);

    //login/Register Routes
    Route::get('/login-register', [UsersController::class, 'loginRegister']);

    //Login User Routes
    Route::post('/login',[UsersController::class, 'loginUser']);

    //Register User Routes
    Route::post('/register', [UsersController::class, 'registerUser']);

    //Check if email already exists
    Route::match(['get','post'],'/check-email', [UsersController::class, 'checkEmail']);

    //Logout User Routes
    Route::get('/logout', [UsersController::class, 'logoutUser']);
    
    //Confirm Account Route
    Route::match(['GET','POST'],'/confirm/{code}', [UsersController::class, 'confirmAccount']);
    
    //forgot password Route
    Route::match(['GET','POST'],'/forgot-password', [UsersController::class, 'forgotPassword']);
    
    //Search Functionality Route
    Route::get('/search-products', [FrontProductsController::class, 'listing']);
    
    //Contact us Route
    Route::match(['GET','POST'],'/contact', [FrontCmsController::class, 'contact']);

    //Add Rating Route
    Route::post('/add-rating', [FrontRatingController::class, 'addRating']);

    //Newsletter Subscriber Route
    Route::post('/add-subscriber-email', [NewsletterController::class, 'addSubscriber']);

    Route::group(['middleware'=>['auth']],function(){
        //Users Account Route
        Route::match(['GET','POST'],'/account', [UsersController::class, 'account']);

        //Users Orders
        Route::get('/orders', [OrdersController::class, 'orders']);
        
        //user order details
        Route::get('/orders/{id}', [OrdersController::class, 'orderDetails']);
        
        //user order cancel
        Route::match(['GET','POST'],'/orders/{id}/cancel', [OrdersController::class, 'orderCancel']);
        
        //user order retrun
        Route::match(['GET','POST'],'/orders/{id}/return', [OrdersController::class, 'orderReturn']);

        //get-product-sizes for exchange
        Route::post('get-product-sizes', [OrdersController::class, 'getProductSizes']);

        // Check User password
        Route::post('/check-user-pwd', [UsersController::class, 'chkUserPassword']);
        
        //Update User Password
        Route::post('/update-user-pwd', [UsersController::class, 'updateUserPassword']);
        
        // Apply coupon route
        Route::post('/apply-coupon', [FrontProductsController::class, 'applyCoupon']);
        
        //Checkout page Route
        Route::match(['GET','POST'],'/checkout', [FrontProductsController::class, 'checkout']);

        // Add/Edit Delivery Address
        Route::match(['get', 'post'], 'add-edit-delivery-address/{id?}', [FrontProductsController::class, 'addEditDeliveryAddress']);

        //Delete Delivery Address
        Route::get('/delete-delivery-address/{id}', [FrontProductsController::class, 'deleteDeliveryAddress']);

        //Thanks page Route
        Route::get('/thanks', [FrontProductsController::class, 'thanks']);
        
        //Paypal page Route
        Route::get('/paypal', [PaypalController::class, 'paypal']);

        //paypal Success
        Route::get('/paypal/success', [PaypalController::class, 'success']);

        //paypal Fail
        Route::get('/paypal/fail', [PaypalController::class, 'fail']);

        //paypal IPN
        Route::post('/paypal/ipn', [PaypalController::class, 'ipn']);

        //Update Wishlist 
        Route::post('/update-wishlist', [FrontProductsController::class, 'updateWishlist']);

        //User Wishlist product 
        Route::get('/wishlist', [FrontProductsController::class, 'wishlist']);
        
        #Delete Wishlist Item
        Route::post('/delete-wishlist-item', [FrontProductsController::class, 'deleteWishlistItem']);
    });



    
});
