@section('title', 'Classification of Accounts')
<x-superadmin-layout>
    <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <button id="closeModalBtn" class="absolute top-2 right-2 text-gray hover:text-black text-xl">&times;</button>
        <!-- Modal layout -->
        <div
            class="bg-white rounded-md mt-6 max-sm:mx-2 px-6 py-4 border-4 border-gray max-w-lg w-full max-h-[80vh] overflow-y-auto relative max-sm:max-h-[75vh] max-sm:overflow-y-auto max-sm:relative">
            <h2 class="mb-6 font-semibold text-2xl max-sm:text-[18px]">Classification of Accounts</h2>
            <!-- Form -->

            <form method="POST" id="modalForm">
                @csrf
                <!-- User ID -->
                <div class="mt-2">
                    <div class="grid gap-x-20 gap-y-3 max-w-full">
                        <div class="font-semibold max-sm:text-sm">Principal Investigator:</div>
                        <div class="max-sm:mb-2 max-sm:text-sm" id="piName">John Doe</div>

                        <div class="font-semibold max-sm:text-sm">Co-Investigators:</div>
                        <div class="max-sm:mb-2 max-sm:text-sm" id="coInvestigators">John Doe</div>
                        <div class="font-semibold max-sm:text-sm">Research Title:</div>
                        <div class="max-sm:mb-2 max-sm:text-sm" id="piTitle"></div>

                        <div class="font-semibold max-sm:text-sm">Endorsement Letter:</div>
                        <div class="max-sm:mb-2 max-sm:text-sm" id="endorsementLetter">
                            <a href="#">
                                <button type="button" class="border-2 px-[10px] py-[5px] hover:bg-gray">View</button>
                            </a>
                        </div>
                        <div class="font-semibold max-sm:text-sm">Classification of Accounts:</div>
                        <!-- tanggalin nalang to pag papalitan na ng backend -->
                        <div class="max-sm:mb-2 max-sm:text-sm grid grid-cols-3">
                            <div class="flex gap-x-1">
                                <input type="checkbox" class="mt-1 max-sm:w-[14px] max-sm:h-[14px]">
                                <span>ERB</span>
                            </div>
                            <div class="flex gap-x-1">
                                <input type="checkbox" class="mt-1 max-sm:w-[14px] max-sm:h-[14px]">
                                <span>IACUC</span>
                            </div>
                            <div class="flex gap-x-1">
                                <input type="checkbox" class="mt-1 max-sm:w-[14px] max-sm:h-[14px]">
                                <span>aweawe</span>
                            </div>
                        </div>
                        <div class="max-sm:mb-2 max-sm:text-sm" id="reviewClassification" name="reviewClassification">
                            <x-combobox-classify-accounts id="reviewClassification" name="reviewClassification"
                                class="max-sm:text-sm" />
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-primary-button class="mt-4 max-sm:text-sm">
                        Submit
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
    <!-- Main Content -->
    <main class="xl:ml-[335px] max-xl:ml-auto p-4 max-md:p-2">
        <h2 class="max-xl:hidden text-left bg-[#f2f2f2] shadow-lg p-[35px] rounded-[30px] font-medium text-[28px]">
            ACCOUNTS CLASSIFICATION
        </h2>
        <br>

        <table id="myTable" class="display overflow-scroll border-collapse w-full">
            <!-- Table header -->
            <thead class="bg-primary text-white text-lg/7 max-lg:text-base/7">
                <tr class="header-table">
                    <th class="w-[25.00%]">P.I. Name</th>
                    <th class="w-[25.00%]">Research Title</th>
                    <!-- binago lang ung column header from "Classification of Accounts" to "Classify Account" -->
                    <th class="w-[25.00%]">Classify Account</th>
                    <th class="w-[25.00%]">Date Classified</th>
                </tr>
            </thead>
            <!-- Table body -->
            <tbody class="text-base/7 max-lg:text-sm/6">
                @foreach ($pi as $user)
                    <tr>
                        <td>
                            <!-- edit nyo nalang ung data-name (pag clinick si PI kung ano nakalagay sa data-name, un ung madidisplay) -->
                            <input type="checkbox" class="user-checkbox w-[14px] h-[14px] mb-1" value="1"
                                data-name="aweawe">
                            <span>{{ $user->user_Fname }} {{ $user->user_MI ? $user->user_MI : '' }}
                                {{ $user->user_Lname }}</span>
                        </td>
                        <td>{{ $user->researchInformation?->research_title }}</td>
                        <td>
                            <button type="button" class="openModalBtn border-2 px-[10px] py-[5px] hover:bg-gray"
                                data-id="{{ $user->user_ID }}"
                                data-pi-name="{{ $user->user_Fname }} {{ $user->user_MI ? $user->user_MI : '' }} {{ $user->user_Lname }}"
                                data-pi-title="{{ $user->researchInformation?->research_title }}"
                                data-co-investigators="{{ $user->researchInformation?->research_CoInvestigator ?? 'N/A' }}"
                                data-endorsement="{{ $user->researchInformation?->research_Endorsement ? asset('storage/' . $user->id . '/' . $user->researchInformation->research_Endorsement) : '#' }}"
                                data-classification="{{ $user->classification ?? '' }}">View</button>
                        </td>
                        <td>{{ optional($user->classifications?->classificationDate)->format('n/j/Y') ?? 'N/A' }}</td>
                    </tr>
                @endforeach
                <!-- sample data lang to -->
                <tr>
                    <td>
                        <input type="checkbox" class="user-checkbox w-[14px] h-[14px] mb-1" value="2"
                            data-name="aweawe">
                        <span>aweawewe</span>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="grid grid-cols-2 max-md:grid-cols-1 max-md:block gap-x-5">
            <div class="max-md:mt-3 bg-lightgray shadow-md rounded-md p-3">
                <h3 class="text-lg font-semibold max-md:text-base mb-3">USERS LIST</h3>
                <div class="gap-x-2 gap-y-3">
                    <ul id="selectedUsers" class="max-md:text-sm flex grid grid-cols-3 max-md:grid-cols-2 list-disc pl-5"></ul>
                </div>
            </div>
            <div class="max-md:mt-3 bg-lightgray shadow-md rounded-md p-3">
                <h3 class="text-lg font-semibold max-md:text-base mb-3">CLASSIFY ACCOUNTS</h3>
                <div class="max-sm:mb-2 max-sm:text-sm grid grid-cols-3">
                    <div class="flex gap-x-1">
                        <input type="checkbox" class="mt-1 w-[14px] h-[14px] max-sm:w-[12px] max-sm:h-[12px]">
                        <span>ERB</span>
                    </div>
                    <div class="flex gap-x-1">
                        <input type="checkbox" class="mt-1 w-[14px] h-[14px] max-sm:w-[12px] max-sm:h-[12px]">
                        <span>IACUC</span>
                    </div>
                    <div class="flex gap-x-1">
                        <input type="checkbox" class="mt-1 w-[14px] h-[14px] max-sm:w-[12px] max-sm:h-[12px]">
                        <span>ERB/IACUC</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-start mt-4 mx-4 max-md:mx-0">
            <button id="submitBtn"
                class="bg-secondary hover:bg-primary text-primary hover:text-secondary px-4 py-2 rounded-md uppercase tracking-widest duration-200 max-sm:text-sm"
                type="button">
                Submit
            </button>
        </div>
    </main>
</x-superadmin-layout>
<script>
    // Prevent checkbox and button clicks from expanding
    document.addEventListener('click', function (e) {
        if (e.target.closest('input[type="checkbox"]') || e.target.closest('button')) {
            e.stopPropagation();
        }
    }, true);

    const userCheckboxes = document.querySelectorAll(".user-checkbox");
    const selectedUsersList = document.getElementById("selectedUsers");

    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", () => {
            const userId = checkbox.value;
            const userName = checkbox.dataset.name;

            const existing = selectedUsersList.querySelector(`[data-user-id="${userId}"]`);

            if (checkbox.checked && !existing) {
                // Add to list
                const li = document.createElement("li");
                li.textContent = userName;
                li.setAttribute("data-user-id", userId);
                selectedUsersList.appendChild(li);
            } else if (!checkbox.checked && existing) {
                // Remove from list
                existing.remove();
            }
        });
    });

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("openModalBtn")) {
            const btn = e.target;

            // Set modal content
            document.getElementById("piName").innerText = btn.dataset.piName;
            document.getElementById("piTitle").innerText = btn.dataset.piTitle;
            document.getElementById("coInvestigators").innerText = btn.dataset.coInvestigators;

            document.getElementById("endorsementLetter").innerHTML =
                `<a href="${btn.dataset.endorsement}" target="_blank">
                <button type="button" class="border-2 px-[10px] py-[5px] hover:bg-gray">View</button>
            </a>`;

            // Set combobox value
            const combo = document.getElementById("reviewClassification");
            combo.value = btn.dataset.classification;

            // ✅ Set the form action dynamically for this PI
            document.getElementById("modalForm").action = `/superadmin/classifications/${btn.dataset.id}/update`;

            // Show modal
            const modal = document.getElementById("modalOverlay");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }
    });

    // Close modal with X button
    document.getElementById("closeModalBtn").addEventListener("click", () => {
        const modal = document.getElementById("modalOverlay");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    });

    // Close modal when clicking outside content
    document.getElementById("modalOverlay").addEventListener("click", (e) => {
        if (e.target.id === "modalOverlay") {
            const modal = document.getElementById("modalOverlay");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    });

    const rooms = document.querySelectorAll(".room");
    const assignedList = document.getElementById("assignedList");
    const submitBtn = document.getElementById("submitBtn");

    rooms.forEach(room => {
        room.addEventListener("click", () => {
            const formId = room.dataset.room;
            const formCode = room.textContent;
            const existingItem = assignedList.querySelector(`[data-room="${formId}"]`);

            if (existingItem) {
                // Remove if already assigned
                existingItem.remove();
            } else {
                // Add new item
                const li = document.createElement("li");
                li.textContent = formCode;
                li.setAttribute("data-room", formId);
                assignedList.appendChild(li);
            }
        });
    });

    // Example action for submit
    submitBtn.addEventListener("click", (e) => {
        console.log("Submit button clicked ✅");

        const selectedUsers = [...document.querySelectorAll(".user-checkbox:checked")].map(cb => cb.value);
        const selectedForms = [...assignedList.querySelectorAll("li")].map(li => li.dataset.room);

        console.log("Users:", selectedUsers);
        console.log("Forms:", selectedForms);

        if (selectedUsers.length === 0 || selectedForms.length === 0) {
            alert("Please select at least one user and one form.");
            return;
        }

        fetch("{{ route('assign.forms.ajax') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                user_ids: selectedUsers,
                form_ids: selectedForms
            })
        })
            .then(res => res.json())
            .then(data => {
                console.log("Server response:", data);
                if (data.success) {
                    alert(data.message);

                    // Reset UI
                    document.querySelectorAll(".user-checkbox").forEach(cb => cb.checked = false);
                    assignedList.innerHTML = "";
                } else {
                    alert("Something went wrong.");
                }
            })
            .catch(err => console.error("Fetch error:", err));
    });
</script>