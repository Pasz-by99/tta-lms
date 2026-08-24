<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $certificate->student_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="no-print text-center py-4">
        <button onclick="window.print()" class="bg-green-700 text-white px-6 py-2 rounded-lg font-semibold">
            Print Certificate
        </button>
    </div>

    <div class="max-w-4xl mx-auto my-8 bg-white shadow-2xl border-8 border-green-700 relative overflow-hidden">
        {{-- Decorative corners --}}
        <div class="absolute top-0 left-0 w-24 h-24 border-t-8 border-l-8 border-green-600"></div>
        <div class="absolute top-0 right-0 w-24 h-24 border-t-8 border-r-8 border-green-600"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 border-b-8 border-l-8 border-green-600"></div>
        <div class="absolute bottom-0 right-0 w-24 h-24 border-b-8 border-r-8 border-green-600"></div>

        <div class="p-12 text-center">
            {{-- Logo --}}
            <img src="{{ asset('images/logo.png') }}" alt="TTA Logo" class="h-20 mx-auto mb-6">

            <h1 class="text-4xl font-bold text-green-800 tracking-wider mb-2">CERTIFICATE OF COMPLETION</h1>
            <p class="text-gray-500 mb-10">This is to certify that</p>

            <h2 class="text-3xl font-bold text-gray-900 mb-8 border-b-2 border-green-600 inline-block pb-2 px-8">
                {{ $certificate->student_name }}
            </h2>

            <p class="text-lg text-gray-700 mb-2">has successfully completed the training course</p>

            <h3 class="text-2xl font-semibold text-green-700 mb-8">
                {{ $certificate->course->title }}
            </h3>

            <div class="grid grid-cols-3 gap-6 mt-12 text-sm">
                <div>
                    <p class="text-gray-500">Certificate No.</p>
                    <p class="font-bold">{{ $certificate->certificate_number }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Date Issued</p>
                    <p class="font-bold">{{ $certificate->issued_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Issued By</p>
                    <p class="font-bold">{{ $certificate->issued_by }}</p>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t">
                <p class="font-bold text-lg">Albertina Nalukale Mewiliko Shiimi</p>
                <p class="text-sm text-gray-500">Founder – Tinahls Triad Agro CC</p>
            </div>
        </div>
    </div>

</body>
</html>