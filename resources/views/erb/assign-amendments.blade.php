@section('title', 'Assigned Amendments')
<x-erb-layout>
    <!-- Main Content -->
    <main class="xl:ml-[335px] max-xl:ml-auto p-4 max-md:p-2">
        <h2 class="max-xl:hidden text-left bg-[#f2f2f2] shadow-lg p-[35px] rounded-[30px] font-medium text-[28px]">
            ASSIGN AMENDMENTS
        </h2>
        <br>

        <!-- CSS NG SEARCH BAR -->
        <div class="top-controls">
            <div class="search-wrapper mt-1 flex max-sm:justify-center max-sm:items-center"></div>
        </div>

        <table id="myTable" class="display overflow-scroll border-collapse w-full">
            <thead class="bg-primary text-white text-lg/7 max-lg:text-base/7">
                <tr class="header-table">
                    <th class="w-[33.33%]">P.I. Name</th>
                    <th class="w-[33.33%]">Research Title</th>
                    <th class="w-[33.33%]">Date Assigned</th>
                </tr>
            </thead>
            <tbody class="text-base/7 max-lg:text-sm/6">
                @forelse($approvedProtocols as $approved)
                    <tr>
                        <td>
                            <input type="checkbox" class="user-checkbox w-[14px] h-[14px] mb-1"
                                value="{{ $approved->user_ID }}" data-protocol-id="{{ $approved->Protocol_ID }}">
                            <span>
                                {{ $approved->user->user_Fname ?? '' }}
                                {{ $approved->user->user_MI ?? '' }}
                                {{ $approved->user->user_Lname ?? '' }}
                            </span>
                        </td>
                        <td>
                            {{ $approved->protocol->researchInformation->research_title ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $approved->created_at->format('m/d/Y') }}<br>
                            {{ $approved->created_at->format('H:i:s') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">No approved protocols found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Selected Users -->
        <div class="flex mx-4 gap-6 grid mt-6">
            <!-- Selected Users -->
            <div class="bg-lightgray p-4 shadow-md rounded-md">
                <h3 class="font-semibold text-lg max-md:text-base mb-3">SELECTED PROTOCOLS FOR ASSIGNMENT</h3>
                <div class="h-16 overflow-y-auto">
                    <ul id="selectedUsers"
                        class="list-disc pl-5 flex grid grid-cols-4 max-md:grid-cols-1 max-md:text-sm"></ul>
                </div>
            </div>
            <div class="flex justify-start mx-4">
                <button id="submitBtn"
                    class="bg-secondary hover:bg-primary text-primary hover:text-secondary px-4 py-3 rounded-md uppercase tracking-widest duration-200"
                    type="button">
                    Assign
                </button>
            </div>
        </div>
    </main>
</x-erb-layout>
<script>
    $(document).ready(function () {
        // Initialize DataTable with simpler column configuration
        const dataTable = $('#myTable').DataTable({
            order: [[0, 'asc']]
            language: {
                emptyTable: 'No approved protocols found.'
            },
            // Tell DataTables not to auto-detect data sources
            autoWidth: false,
            deferRender: true,
            // Use the existing HTML as-is
            columnDefs: [
                { targets: '_all', defaultContent: '' }
            ]
        });

        // Modal controls
        const userCheckboxes = document.querySelectorAll(".user-checkbox");
        const selectedUsersList = document.getElementById("selectedUsers");
        const submitBtn = document.getElementById("submitBtn");

        // Function to update selected users list
        function updateSelectedList() {
            const selectedItems = Array.from(selectedUsersList.querySelectorAll('li'));

            // Update submit button state
            submitBtn.disabled = selectedItems.length === 0;

            // Show/hide empty state
            if (selectedItems.length === 0) {
                selectedUsersList.innerHTML = '<li class="text-gray-500">No protocols selected</li>';
            }
        }

        // Add event listeners to checkboxes
        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener("change", function () {
                const userId = this.value;
                const protocolId = this.dataset.protocolId;
                const userName = this.closest('td').querySelector('span').textContent.trim();
                const existing = selectedUsersList.querySelector(`[data-user-id="${userId}"][data-protocol-id="${protocolId}"]`);

                if (this.checked && !existing) {
                    // Add to list
                    const li = document.createElement("li");
                    li.className = "text-sm mb-1";
                    li.textContent = `${userName} (Protocol: ${protocolId})`;
                    li.setAttribute("data-user-id", userId);
                    li.setAttribute("data-protocol-id", protocolId);

                    // Remove empty state if it exists
                    if (selectedUsersList.querySelector('.text-gray-500')) {
                        selectedUsersList.innerHTML = '';
                    }

                    selectedUsersList.appendChild(li);
                } else if (!this.checked && existing) {
                    // Remove from list
                    existing.remove();
                }

                updateSelectedList();
            });
        });

        // Submit button functionality
        submitBtn.addEventListener('click', function () {
            const selectedItems = Array.from(selectedUsersList.querySelectorAll('li:not(.text-gray-500)'));

            if (selectedItems.length === 0) {
                alert('Please select at least one protocol to assign.');
                return;
            }

            const selectedData = selectedItems.map(li => ({
                user_id: li.getAttribute('data-user-id'),
                protocol_id: li.getAttribute('data-protocol-id'),
                user_name: li.textContent.split(' (Protocol: ')[0]
            }));

            console.log('Selected protocols:', selectedData);

            // Disable button during processing
            this.disabled = true;
            this.textContent = 'Assigning...';

            // Send via AJAX
            fetch('{{ route("assign.amendments") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ protocols: selectedData })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Protocols assigned successfully!');
                        location.reload();
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error assigning protocols: ' + error.message);
                })
                .finally(() => {
                    // Re-enable button
                    this.disabled = false;
                    this.textContent = 'Assign';
                });
        });

        // Initialize selected list state
        updateSelectedList();

        // Add search functionality enhancement
        $('#myTable_filter input').on('keyup', function () {
            // Clear selections when searching (optional)
            // userCheckboxes.forEach(cb => cb.checked = false);
            // selectedUsersList.innerHTML = '<li class="text-gray-500">No protocols selected</li>';
            // updateSelectedList();
        });
    });
</script>