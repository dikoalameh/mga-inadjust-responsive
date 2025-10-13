@section('title', 'Submitted Tickets')
<x-iacuc-layout>
    <!-- Main Content -->
    <main class="xl:ml-[335px] max-xl:ml-auto p-4 max-md:p-2">
        <h2 class="max-xl:hidden text-left bg-[#f2f2f2] shadow-lg p-[35px] rounded-[30px] font-medium text-[28px]">
            SUBMITTED TICKETS
        </h2>
        <br>

        <!-- CSS NG SEARCH BAR -->
        <div class="top-controls">
            <div class="search-wrapper mt-1 flex max-sm:justify-center max-sm:items-center"></div>
        </div>

        <table id="myTable" class="display overflow-scroll border-collapse w-full">
            <thead class="bg-primary text-white text-lg/7 max-lg:text-base/7">
                <tr class="header-table">
                    <th>P.I. Name</th>
                    <th>Research Title</th>
                    <th>Subject</th>
                    <th>Date Submitted</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody class="text-base/7 max-lg:text-sm/6">
                <tr>
                    <td>John Doe</td>
                    <td>MCU-RRS</td>
                    <td>Applying for Amendments</td>
                    <td>
                        10/25/25<br>
                        22:30:20
                    </td>
                    <td>
                        <a href="{{ url('iacuc/tickets') }}">
                            <button type="button" class="border-2 p-[5px] hover:bg-gray">
                                View
                            </button>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
</x-iacuc-layout>
<script>
    $(document).ready(function () {
        // Only initialize if not already initialized
        if (!$.fn.dataTable.isDataTable('#myTable')) {
            const table = new DataTable('#myTable', {
                responsive: true,
                paging: false,
                scrollY: '300px',
                order: [[0, 'asc']]
            });

            // ✅ Move the DataTables search bar into our custom search-wrapper
            const dtSearch = $('div.dt-search');
            $('.search-wrapper').append(dtSearch);
        }
    });

    const modal = document.getElementById("modal");
    function openModal() {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    }

    function closeModal() {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }

    // Close modal on background click
    window.onclick = (e) => {
        if (e.target === modal) closeModal();
    };
</script>