@section('title', 'Submitted Documents')
<x-iacuc-layout>
    <main class="xl:ml-[335px] max-xl:ml-auto p-4 max-md:p-2">
        <h2 class="max-xl:hidden text-left bg-[#f2f2f2] shadow-lg p-[35px] rounded-[30px] font-medium text-[28px]">
            SUBMITTED DOCUMENTS
        </h2>
        <div class="w-full mx-auto my-8 px-4 py-6 bg-white rounded-lg border-2 border-gray">
            <h1 class="text-primary text-2xl max-md:text-lg font-semibold mb-4">Submission Details</h1>
            <h2 class="text-primary text-xl max-md:text-base font-medium">User: <span id="user-name"></span></h2>
            <div id="documents-list" class="max-sm:text-sm mt-6 space-y-4 h-64 overflow-y-auto">
                <!-- HARDCODED LAYOUT (NO BACKEND FOR REFERENCE) -->
                <div class="p-3 border border-darkgray bg-lightgray flex justify-between items-center rounded-lg">
                    <div>
                        <a href="#">
                            <h3 class="font-medium text-lg text-primary">Form: FORM3A</h3>
                            <p class="text-gray-700">Document: FORM3A.pdf</p>
                            <p class="text-gray-500 text-sm">Submitted: 10-21-25</p>
                        </a>
                    </div>
                    <div class="right">
                        <button type="button" onclick="deleteCard(this)"
                            class="bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded duration-200">
                            <i class="bi bi-trash3-fill text-xl max-sm:text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        
        </div>

        <div class="mt-8">
            <a href="{{ url('/iacuc/research-records') }}"
                class="bg-secondary hover:bg-primary text-lg max-xl:text-base text-primary hover:text-secondary uppercase tracking-widest px-4 py-2 rounded-md duration-200">
                Back
            </a>
        </div>

        <script>
            // Reads user_id from the URL (in research-records.blade.php file)
            const userId = new URLSearchParams(window.location.search).get('user_id');

            // Simple user data (dummy data)
            const users = {
                1: {
                    name: "John Doe", documents: [
                    ]
                }
            };

            const user = users[userId];
            if (user) {
                document.getElementById("user-name").textContent = user.name;

                const list = document.getElementById("documents-list");
                user.documents.forEach(doc => {
                    list.innerHTML += `
                    <div class="p-3 border border-darkgray bg-lightgray flex justify-between items-center rounded-lg">
                        <h3 class="font-medium flex justify-between">Document Type: ${doc.type}</h3>
                        <div class="right"> 
                            <a href="#">
                                <button type="button" class="bg-secondary hover:bg-primary text-primary hover:text-secondary px-3 py-1.5 uppercase tracking-widest rounded-md duration-200">
                                    View
                                </button>
                            </a>
                        </div>
                    </div>
                    `;
                });
            } else {
                document.getElementById("user-name").textContent = "User not found";
            }
        </script>
    </main>
</x-iacuc-layout>