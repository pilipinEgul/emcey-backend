<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ContactInquiryController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\ServiceCategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:public-read')->group(function () {
        Route::get('service-categories', [ServiceCategoryController::class, 'index']);

        Route::get('services', [ServiceController::class, 'index']);
        Route::get('services/{slug}', [ServiceController::class, 'show']);

        Route::get('testimonials', [TestimonialController::class, 'index']);
        Route::get('faqs', [FaqController::class, 'index']);
        Route::get('gallery', [GalleryController::class, 'index']);
        Route::get('promos', [PromoController::class, 'index']);
    });

    Route::middleware('throttle:availability')->group(function () {
        Route::get('appointments/availability', [AppointmentController::class, 'availability']);
        Route::get('appointments/availability/range', [AppointmentController::class, 'availabilityRange']);
    });

    Route::post('appointments', [AppointmentController::class, 'store'])->middleware('throttle:booking');
    Route::post('contact', [ContactInquiryController::class, 'store'])->middleware('throttle:contact');
});
