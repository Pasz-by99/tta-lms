@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="bg-tta text-white py-12">
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold">Contact Us to Enroll</h1>
        <p class="mt-2 text-green-100">We do not accept online payments. Please contact us to make payment and complete your enrollment.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-sm border p-8">
            <h2 class="text-xl font-bold mb-6 text-tta">Get in Touch</h2>
            <div class="space-y-5">
                <div>
                    <div class="text-sm text-gray-500">Phone / WhatsApp</div>
                    <div class="text-lg font-semibold">+264 81 318 8489</div>
                    <div class="text-lg font-semibold">+264 81 705 0652</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Email</div>
                    <div class="text-lg font-semibold">tinahsagrotriad@gmail.com</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Location</div>
                    <div class="text-lg font-semibold">Namibia</div>
                </div>
            </div>
        </div>

        <div class="bg-green-50 rounded-xl border border-green-100 p-8">
            <h2 class="text-xl font-bold mb-4">How to Enroll</h2>
            <ol class="list-decimal list-inside space-y-3 text-gray-700">
                <li>Choose the course you want from our Courses page.</li>
                <li>Contact us on the numbers above (Call or WhatsApp).</li>
                <li>Make your payment as instructed.</li>
                <li>We will confirm your enrollment and share the training details.</li>
            </ol>
        </div>
    </div>
</div>
@endsection