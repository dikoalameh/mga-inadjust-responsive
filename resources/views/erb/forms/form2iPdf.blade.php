<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>FORM-2I</title>
    @vite('resources/css/app.css') {{-- loads Tailwind --}}
</head>
<style>
    .page-break {
        break-before: page;
    }
</style>

<body class="p-8 text-sm font-arial">
    <h1 class="text-xl font-times font-black text-center mb-6">MCUERB FORM 2(I) Certificate of Exemption of Review</h1>

    <x-formbanner>MCUERB FORM 2(I) Certificate of Exemption of Review</x-formbanner>

    <h1 class="text-base font-bold text-center underline my-4">CERTIFICATE OF EXEMPTION OF REVIEW TEMPLATE</h1>

    <div class="mx-8">
        <p class="italic">Date:</p>
        <p class="mt-2 h-4">
            {{-- date --}}
        </p>

        <div class="mt-4">
            <p class="font-bold italic uppercase">
                < title, name, surname of PI>
                    {{-- title, name, surname of PI --}}
            </p>
            <p>
                Principal Investigator
                {{-- pi name --}}
            </p>
            <p class="italic">
                < Institution/Affiliation>
                    {{-- institution/affiliation --}}
            </p>
            <p class="italic">
                < Address>
                    {{-- address --}}
            </p>
        </div>
        <div class="mt-6">
            <p>
                Re: Study Protocol Title
                {{-- study protocol title --}}
            </p>
            <p class="font-bold mt-6">
                MCUERB Code:
                <label class="font-normal">
                    {{-- mcuerb code --}}
                </label>
            </p>
            <p class="font-bold mt-4">
                Subject: Certificate of Exemption from Review
            </p>
            <p class="mt-3">
                Dear < TITLE OF PI>{{-- title of PI --}} < SURNAME> {{-- surname --}}
            </p>
            <p class="mt-2">
                This is to acknowledge submission of the following documents (include version numbers and dates)
            <ul class="mx-8 mt-2">
                <li class="list-disc"></li>
                <li class="list-disc"></li>
                <li class="list-disc"></li>
            </ul>
            </p>
            <p class="mt-2">
                After a preliminary review of the above documents, MCUERB deemed it appropriate that the above proposal
                be EXEMPTED FROM REVIEW.
            </p>
            <p class="mt-4">
                This means that the study may be implemented without undergoing expedited or full review. Neither will
                the proponents be required to submit further documents to the committee as long as there is no amendment
                nor alteration in the protocol that will change the nature of the study nor the level of risks involved.
                A Final Report (MCUERB FORM 3(L) Final Report) must be submitted 30 days after the completion of
                research together with the final manuscript.
            </p>
            <p class="mt-4">
                Very truly yours,<br>
                ERB Chair
                <p>
                    {{-- erb chair --}}
                </p>
            </p>
        </div>
    </div>
</body>

</html>