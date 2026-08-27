<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ContactInquiryController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\ServiceCategoryController;
use App\Http\Controllers\Api\PageContentController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Api\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\ClosureController as AdminClosureController;
use App\Http\Controllers\Api\Admin\ContactInquiryController as AdminContactInquiryController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Api\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Api\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Api\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Api\Admin\ServiceCategoryController as AdminServiceCategoryController;
use App\Http\Controllers\Api\Admin\PageContentController as AdminPageContentController;
use App\Http\Controllers\Api\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Api\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Api\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Api\Admin\UploadController as AdminUploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:public-read')->group(function () {
        Route::get('service-categories', [ServiceCategoryController::class, 'index']);

        Route::get('services', [ServiceController::class, 'index']);
        Route::get('services/{slug}', [ServiceController::class, 'show']);

        Route::get('site-settings', [SiteSettingController::class, 'show']);
        Route::get('page-content', [PageContentController::class, 'show']);

        Route::get('testimonials', [TestimonialController::class, 'index']);
        Route::get('faqs', [FaqController::class, 'index']);
        Route::get('gallery', [GalleryController::class, 'index']);
        Route::get('promos', [PromoController::class, 'index']);
        Route::get('announcements', [AnnouncementController::class, 'index']);
    });

    Route::middleware('throttle:availability')->group(function () {
        Route::get('appointments/availability', [AppointmentController::class, 'availability']);
        Route::get('appointments/availability/range', [AppointmentController::class, 'availabilityRange']);
    });

    // Public booking tracking + self-service cancellation (by reference).
    Route::get('appointments/track', [AppointmentController::class, 'track'])
        ->middleware('throttle:public-read');
    Route::post('appointments/cancel', [AppointmentController::class, 'cancel'])
        ->middleware('throttle:booking');

    Route::post('appointments', [AppointmentController::class, 'store'])->middleware('throttle:booking');
    Route::post('contact', [ContactInquiryController::class, 'store'])->middleware('throttle:contact');
});

// -------------------------------------------------------------------------
// Admin API — bearer-token auth (see App\Http\Middleware\EnsureAdmin).
// -------------------------------------------------------------------------
Route::prefix('v1/admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:login');

    Route::middleware('admin.auth')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::get('dashboard', [AdminDashboardController::class, 'index']);
        Route::get('reports/bookings', [AdminReportController::class, 'bookings']);

        // Contact form messages
        Route::get('contact-inquiries/unread', [AdminContactInquiryController::class, 'unread']);
        Route::get('contact-inquiries', [AdminContactInquiryController::class, 'index']);
        Route::match(['put', 'patch'], 'contact-inquiries/{contactInquiry}', [AdminContactInquiryController::class, 'update']);
        Route::delete('contact-inquiries/{contactInquiry}', [AdminContactInquiryController::class, 'destroy']);
        Route::post('uploads', [AdminUploadController::class, 'store']);
        Route::post('uploads/video', [AdminUploadController::class, 'storeVideo']);

        // Appointments
        Route::get('appointments', [AdminAppointmentController::class, 'index']);
        Route::get('appointments/{appointment}', [AdminAppointmentController::class, 'show']);
        Route::match(['put', 'patch'], 'appointments/{appointment}', [AdminAppointmentController::class, 'update']);
        Route::delete('appointments/{appointment}', [AdminAppointmentController::class, 'destroy']);

        // Booking schedule (studio hours + slot interval)
        Route::get('settings', [AdminSettingController::class, 'index']);
        Route::match(['put', 'patch'], 'settings', [AdminSettingController::class, 'update']);

        // Business info (name, address, contact, socials) shown across the site
        Route::get('site-settings', [AdminSiteSettingController::class, 'index']);
        Route::match(['put', 'patch'], 'site-settings', [AdminSiteSettingController::class, 'update']);

        // Editable page content (About, Home extras, Testimonials copy…)
        Route::get('page-content', [AdminPageContentController::class, 'index']);
        Route::match(['put', 'patch'], 'page-content', [AdminPageContentController::class, 'update']);

        // Closures / holidays
        Route::get('closures', [AdminClosureController::class, 'index']);
        Route::post('closures', [AdminClosureController::class, 'store']);
        Route::delete('closures/{closure}', [AdminClosureController::class, 'destroy']);

        // Service categories (explicit routes so the {serviceCategory} binding matches)
        Route::get('service-categories', [AdminServiceCategoryController::class, 'index']);
        Route::post('service-categories', [AdminServiceCategoryController::class, 'store']);
        Route::match(['put', 'patch'], 'service-categories/{serviceCategory}', [AdminServiceCategoryController::class, 'update']);
        Route::delete('service-categories/{serviceCategory}', [AdminServiceCategoryController::class, 'destroy']);

        // Content resources
        Route::apiResource('services', AdminServiceController::class);
        Route::apiResource('testimonials', AdminTestimonialController::class)->except(['show']);
        Route::apiResource('promos', AdminPromoController::class)->except(['show']);
        Route::apiResource('announcements', AdminAnnouncementController::class)->except(['show']);
        Route::apiResource('faqs', AdminFaqController::class)->except(['show']);
        Route::apiResource('gallery', AdminGalleryController::class)->except(['show']);
    });
});
